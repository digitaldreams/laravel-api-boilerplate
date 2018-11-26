<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id id
 * @property varchar $slug slug
 * @property varchar $name name
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 */
class Role extends Model
{
    const USER = 'user';
    const SUPER_ADMIN = 'super_admin';
    /**
     * Database Table Name
     * @var string
     */
    protected $table = 'roles';

    /**
     * Protected column that will not be mass assignable
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * ALl the users that have this role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    /**
     * Filter by slug
     * @param $query Builder
     * @param $slug string
     * @return  Builder
     */
    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Filter by name
     * @param $query Builder
     * @param $name string
     * @return  Builder
     */
    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * General search throughout the table searchable columns
     *
     * @param $query Builder
     * @param $q string
     * @return Builder
     */
    public function scopeQ($query, $q)
    {
        return $query->where(function ($sq) use ($q) {
            $sq->orWhere('slug', 'LIKE', '%' . $q . '%')
                ->orWhere('name', 'LIKE', '%' . $q . '%');
        });
    }
}