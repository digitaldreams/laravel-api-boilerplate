<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $casts = [
        'ids' => 'array',
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * @param $query
     * @param null $user
     * @return mixed
     */
    public function scopeForUser($query, $user = null)
    {
        return $query->where('notifiable_id', auth()->user()->id)
            ->where('notifiable_type', get_class(auth()->user()));
    }
}
