<?php

namespace App\Services;


use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use ReflectionMethod;

class PermitGenerator
{
    /**
     * @var array
     */
    protected $policies = [];
    /**
     * @var array
     */
    protected $data = [];
    protected $magicMethods = ['__construct', '__get', '__set', '__call', '__sleep', '__invoke'];

    /**
     * PermitGenerator constructor.
     */
    public function __construct()
    {
        $this->policies = Gate::policies();
    }

    /**
     *
     */
    public function process()
    {
        $arr = [];
        foreach ($this->policies as $model => $policy) {
            if (class_exists($policy)) {
                $cls = new ClassInspector($policy);
                $publicMethods = $cls->publicMethods;
                $guardedMethod = property_exists($policy, 'guarded') ? $cls->getProperty('guarded') : [];
                $guardedMethod = array_merge($guardedMethod, $this->magicMethods);
                $methods = $cls->reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $method) {
                    if (!in_array($method->getName(), $guardedMethod)) {
                        $arr[] = $this->save($method, $cls->reflection);
                    }
                }
            }
        }
        return $arr;
    }

    /**
     * @param \ReflectionMethod $method
     * @param \ReflectionClass $rc
     * @return Permission
     */
    protected function save(\ReflectionMethod $method, \ReflectionClass $rc)
    {
        preg_match("/\/\*\*.*? @/s", $method->getDocComment(), $matches);
        $title = isset($matches[0]) ? trim($matches[0]) : '';
        $title = str_replace(["/**", "*", "@"], "", $title);

        $permission = Permission::firstOrNew([
            'method' => $method->getName(),
            'class' => $rc->getName()
        ]);

        if (empty($permission->title) && !empty($title)) {
            $permission->title = $title;
        }
        $permission->save();
        return $permission;
    }

}