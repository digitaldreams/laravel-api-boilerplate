<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserNotification extends Notification
{
    use Queueable;

    /**
     * @var User
     */
    public $user;

    /**
     * Create a new notification instance.
     * @param User $user
     * @return mixed
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our Permitlication!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Format message for database notification
     *
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'link' => route('users.show', $this->user->id),
            'message' => 'New user <em>' . $this->user->getFullName() . '</em> joined as ' . $this->user->role->name
        ];
    }

    /**
     * Format Slack Message
     *
     * @param $notifiable
     * @return $this
     */
    public function toSlack($notifiable)
    {
        $user = $this->user;
        return (new SlackMessage())
            ->from(config('Permit.name'))
            ->content('New user signed up')
            ->attachment(function ($attachment) use ($user) {
                $attachment->title($user->getFullName(), route('users.show', $user->id))
                    ->fields([
                        'First Name' => $user->first_name,
                        'Last Name' => $user->last_name,
                        'Email' => $user->email,
                    ]);
            });
    }
}
