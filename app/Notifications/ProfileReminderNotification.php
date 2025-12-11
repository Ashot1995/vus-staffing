<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileReminderNotification extends Notification
{
    use Queueable;

    protected int $reminderCount;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $reminderCount = 1)
    {
        $this->reminderCount = $reminderCount;
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
        $confirmUrl = route('profile.reminder.confirm', [
            'user' => $notifiable->id,
            'token' => $this->generateToken($notifiable),
        ]);

        $message = (new MailMessage)
            ->subject('Profile Maintenance Reminder - V U S')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('It has been 11 months since your last profile update. We would like to confirm that you wish to maintain your profile with us.')
            ->line('Please click the button below to confirm that you want to keep your profile active:')
            ->action('Confirm Profile Maintenance', $confirmUrl);

        if ($this->reminderCount >= 2) {
            $message->line('**This is your second reminder.** If you do not confirm, your profile may be deleted if you are not active in the company.');
        }

        $message->line('Thank you for being part of V U S!');

        return $message;
    }

    /**
     * Generate a secure token for profile confirmation
     */
    protected function generateToken(object $notifiable): string
    {
        return hash('sha256', $notifiable->id . $notifiable->email . config('app.key') . now()->format('Y-m'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reminder_count' => $this->reminderCount,
        ];
    }
}
