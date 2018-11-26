<?php
/**
 * Created by PhpStorm.
 * User: Tuhin
 * Date: 6/10/2018
 * Time: 1:13 PM
 */

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Services\Notification as NotificationService;

class Database
{
    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send($notifiable, Notification $notification)
    {
        $response = $this->getData($notifiable, $notification);
        $model = $response['model'] ?? false;
        $data = $response['data'] ?? [];
        $notification = new NotificationService($notifiable, $model, get_class($notification));
        return $notification->save($data);
    }

    /**
     * @param $notifiable
     * @param $notification
     * @return array
     */
    public function getData($notifiable, $notification)
    {
        $response = [];
        if (method_exists($notification, 'toDatabase')) {
            $response = $notification->toDatabase($notifiable);
        }
        return $response;
    }
}
