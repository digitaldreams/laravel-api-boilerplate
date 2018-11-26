<?php

namespace App\Models;

use App\Notifications\NewUserNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Gate;

/**
 * @property int $id id
 * @property varchar $first_name first name
 * @property varchar $last_name last name
 * @property varchar $email email
 * @property varchar $address address
 * @property varchar $phone phone
 * @property varchar $password password
 * @property varchar $status status
 * @property varchar $remember_token remember token
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_BLOCKED = 'blocked';
    /**
     * Database table name
     * @var string
     */
    protected $table = 'users';

    /**
     * Protected columns that excluded on mass assignment
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'phone',
        'status',
        'role_id'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * User Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    /**
     * When trying to get name attribute then return getFullName function
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->getFullName();
    }

    /**
     * Get all users who's role is client
     *
     * @param $query Builder
     * @return Builder
     */
    public function scopeUsers($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('slug', Role::USER);
        });
    }

    /**
     * Get all users who's role is client
     *
     * @param $query Builder
     * @return Builder
     */
    public function scopeSuperAdmin($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('slug', Role::SUPER_ADMIN);
        });
    }

    /**
     * General search throughout the searchable columns
     *
     * @param $query Builder
     * @param $q string
     * @return Builder
     */
    public function scopeQ($query, $q)
    {
        return $query->where(function ($sq) use ($q) {
            $sq->orWhere('first_name', 'LIKE', '%' . $q . '%')
                ->orWhere('last_name', 'LIKE', '%' . $q . '%')
                ->orWhere('email', 'LIKE', '%' . $q . '%')
                ->orWhere('address', 'LIKE', '%' . $q . '%');

        });
    }

    /**
     * Filter by Role id
     * @param Builder $query
     * @param $roleId
     * @return Builder
     */
    public function scopeRoleId($query, $roleId)
    {
        if (@empty($roleId)) {
            return $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('id', $roleId);
            });
        }
        return $query;
    }

    /**
     * Whether the user has the specified role or not.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role)
    {
        return (bool)$this->roles()->where('slug', $role)->count();
    }

    /**
     * Shortcut to hasRole Client
     * @return bool
     */
    public function isClient()
    {
        return $this->hasRole(Role::CLIENT);
    }

    /**
     * Shortcut to hasRole Super Admin
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Make a fullname and return
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Does current user active
     * @return bool
     */
    public function isActive()
    {
        return $this->status == static::STATUS_ACTIVE;
    }

    /**
     * Get super admin user id
     * @return null
     */
    public static function getAdminId()
    {
        $admin = static::superAdmin()->select(['id'])->first();
        return !empty($admin) ? $admin->id : null;
    }

    /**
     * Admin Notification
     *
     * @param Model $model
     */
    public static function notifyAdmins(Model $model)
    {
        $admins = static::superAdmin()->get();
        switch (get_class($model)) {
            case User::class:
                Notification::send($admins, new NewUserNotification($model));
                break;
        }
    }

    /**
     * Get phone number
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function can($ability, $arguments = [])
    {
        $result = parent::can($ability, $arguments);
        if ($result === true) {
            return true;
        }
        $policies = Gate::policies();
        if (is_object($arguments) && $arguments instanceof Model) {
            $policyClass = $policies[get_class($arguments)];
        } elseif (is_string($arguments) && class_exists($arguments)) {
            $policyClass = $policies[$arguments];
        } else {
            return $result;
        }

        $roles = $this->roles()->whereHas('permissions', function ($q) use ($ability, $policyClass) {
            $q->where('method', $ability)->where('class', $policyClass);
        })->count();
        return $roles > 0 ? true : false;
    }
}