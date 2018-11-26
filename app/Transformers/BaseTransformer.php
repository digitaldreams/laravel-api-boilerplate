<?php

namespace App\Transformers;


use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    protected $fields;

    public function __construct($fields = [])
    {
        $this->fields = $fields;
    }

    /**
     * @param $data
     * @return array
     */
    protected function filterFields($data)
    {
        return is_array($this->fields) && !empty($this->fields) ? array_intersect_key($data, array_flip($this->fields)) : $data;
    }
}