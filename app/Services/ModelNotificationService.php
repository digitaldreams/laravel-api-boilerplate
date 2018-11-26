<?php

namespace App\Services;

use App\Contracts\ModelNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\Notification as NotificationModel;

class ModelNotificationService extends Notification
{
    protected $actor;


    /**
     * @param array $data
     * @param bool $actor
     * @param bool $verb
     * @return DatabaseNotification
     */
    public function save($data = [], $actor = false, $verb = false)
    {
        $this->actor = $actor;
        $this->verb = $verb;

        $ids = $this->notificationModel->ids ?? [];
        $id = is_object($this->actor) ? $this->actor->id : false;

        if (is_array($ids)) {
            array_push($ids, $id);
        } else {
            $ids[] = $id;
        }

        $this->notificationModel->data = array_merge([
            'message' => $this->message(),
            'link' => $this->link(),
        ], $data);
        $this->notificationModel->ids = $ids;
        $this->notificationModel->save();
        return $this->notificationModel;
    }

    protected function message()
    {
        $message = is_object($this->actor) ? $this->actor->name : 'Someone';
        $message .= ' ' . $this->andOther();
        if ($this->verb) {
            $message .= ' ' . "<b>" . $this->verb . "</b>";
        }
        if ($this->model instanceof ModelNotification) {
            $message .= ' ' . "<em>" . $this->model->getNotificationMessage() . "</em>";
        }
        $this->message = $message;
        return $this->message;
    }

    protected function link()
    {
        if ($this->model instanceof ModelNotification) {
            return $this->model->getNotificationLink();
        }
        return false;
    }

    /**
     * @return DatabaseNotification
     */
    public function getRecord()
    {
        $m = (new NotificationModel());
        $query = $m->where('type', $this->type)
            ->where('model_id', $this->model->id)
            ->where('model_type', get_class($this->model))
            ->whereBetween('created_at', [
                Carbon::now()->subHours(24)->toDateTimeString(),
                Carbon::now()->toDateTimeString()
            ])
            ->orderBy('updated_at', 'desc');
        $model = $query->first();

        if (!$model) {
            $model = (new NotificationModel());
            $model = $model->create([
                'id' => str_random(32),
                'type' => $this->type,
                'notifiable_id' => $this->notifiable->id,
                'notifiable_type' => get_class($this->notifiable),
                'model_id' => $this->model->id,
                'model_type' => get_class($this->model),
                'data' => [
                    'message' => '',
                    'link' => '',
                    'ids' => []
                ]
            ]);
        }
        $this->total = is_array($model->ids) ? count($model->ids) : 0;
        return $model;
    }

}
