<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminProfileDeletionNotice extends Notification
{
    use Queueable;

    protected User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $userProfileUrl = url('/admin/users/' . $this->user->id . '/edit');

        return (new MailMessage)
            ->subject('Profile Deletion Review Required - V U S')
            ->greeting('Hello Admin,')
            ->line('A user has received their second profile maintenance reminder and has not confirmed their profile.')
            ->line('**User Details:**')
            ->line('- Name: ' . $this->user->name)
            ->line('- Email: ' . $this->user->email)
            ->line('- Reminder Count: ' . ($this->user->profile_reminder_count ?? 0))
            ->line('- Last Reminder: ' . ($this->user->last_profile_reminder_at ? $this->user->last_profile_reminder_at->format('Y-m-d') : 'Never'))
            ->line('Please review this user\'s profile and determine if they are still active in the company.')
            ->line('If the user is not active, you may delete their profile from the admin panel.')
            ->action('View User Profile', $userProfileUrl)
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
        ];
    }
}
