<?php
/**
 * Created by PhpStorm.
 * User: Tuhin
 * Date: 8/13/2018
 * Time: 5:56 PM
 */

namespace Permit\Notifications\Channels;

namespace Permit\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Permit\Services\ModelNotificationService as NotificationService;


class Model
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
        $actor = $response['actor'] ?? false;
        $data = $response['data'] ?? [];
        $verb = $response['verb'] ?? false;
        $notification = new NotificationService($notifiable, $model, get_class($notification));
        return $notification->save($data, $actor, $verb);
    }

    /**
     * @param $notifiable
     * @param $notification
     * @return array
     */
    public function getData($notifiable, $notification)
    {
        $response = [];

        if (method_exists($notification, 'toModel')) {
            $response = $notification->toModel($notifiable);
        }
        return $response;
    }

}