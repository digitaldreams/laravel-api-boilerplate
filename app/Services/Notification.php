<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\Notification as NotificationModel;

class Notification
{
    /**
     * @var User
     */
    protected $notifiable;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var DatabaseNotification;
     */
    protected $notificationModel;

    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @var string
     */
    protected $verb;
    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $actor = [];

    /**
     * Notification constructor.
     * @param $notifiable
     * @param Model $model
     */
    public function __construct($notifiable, Model $model, $type)
    {
        $this->notifiable = $notifiable;
        $this->model = $model;
        $this->type = $type;
        $this->notificationModel = $this->getRecord();
    }

    /**
     * @param array $data
     * @return DatabaseNotification
     */
    public function save($data = [])
    {
        $ids = $this->notificationModel->data['ids'] ?? [];
        if (is_array($ids)) {
            array_push($ids, $this->model->id);
        } else {
            $ids[] = $this->model->id;
        }
        $this->notificationModel->data = array_merge([
            'message' => $this->message(),
            'link' => '',
            'ids' => $ids,
        ], $data);
        $this->notificationModel->save();
        return $this->notificationModel;
    }

    protected function message()
    {
        $other = $this->andOther();

        return $this->message;
    }

    protected function andOther()
    {
        return $this->total > 0 ? 'and ' . $this->total . ' others' : '';
    }

    /**
     * @return DatabaseNotification
     */
    public function getRecord()
    {
        $m = (new NotificationModel());
        $query = $m->where('type', $this->type)
            ->where('notifiable_id', $this->notifiable->id)
            ->where('notifiable_type', get_class($this->notifiable))
            ->whereBetween('created_at', [
                Carbon::now()->subHours(24)->toDateTimeString(),
                Carbon::now()->toDateTimeString()
            ])
            ->orderBy('updated_at', 'desc');
        $totalQuery = clone $query;
        $model = $query->first();
        $this->total = $totalQuery->count();
        if (!$model) {
            $model = (new NotificationModel());
            $model = $model->create([
                'id' => str_random(32),
                'type' => $this->type,
                'notifiable_id' => $this->notifiable->id,
                'notifiable_type' => get_class($this->notifiable),
                'data' => [
                    'message' => '',
                    'link' => '',
                    'ids' => []
                ]
            ]);
        }
        return $model;
    }

    /**
     * @return array
     */
    protected function getActor()
    {
        $this->actor = [];
        return $this->actor;
    }
}
