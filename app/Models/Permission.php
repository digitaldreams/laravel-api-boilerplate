<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['name', 'slug'];

    /**
     * Get all roles that have this permission
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    /**
     * Get all the users those have this permisson
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'permission_role', 'permission_id', 'role_id');
    }

    /**
     * Convert normal string into slug
     *
     * @param $value String
     */
    public function setSlugAttribute($value)
    {
        if (!empty($value)) {
            $value = str_slug($value);
        }
        $this->attributes['slug'] = $value;

    }

    /**
     * Text search into columns
     *
     * @param $query Illuminate\Database\Eloquent\Builder
     *
     * @param $search String
     * @return Illuminate\Database\Eloquent\Builder
     */

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->orWhere('method', $search)
                ->orWhere('class', 'LIKE', '%' . $search . '%');
        });
    }

}
