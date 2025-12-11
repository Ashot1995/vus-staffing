<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\AdminProfileDeletionNotice;
use App\Notifications\ProfileReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendProfileReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send profile maintenance reminders to users every 11 months';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for users who need profile reminders...');

        // Find users who need reminders:
        // 1. Users who have never been reminded OR
        // 2. Users whose last reminder was 11 months ago OR more
        $elevenMonthsAgo = now()->subMonths(11);
        
        $usersToRemind = User::where('is_admin', false)
            ->where(function ($query) use ($elevenMonthsAgo) {
                $query->whereNull('last_profile_reminder_at')
                    ->orWhere('last_profile_reminder_at', '<=', $elevenMonthsAgo);
            })
            ->get();

        $reminderCount = 0;
        $adminNoticesCount = 0;

        foreach ($usersToRemind as $user) {
            $reminderCount++;
            
            // Increment reminder count
            $newReminderCount = ($user->profile_reminder_count ?? 0) + 1;
            
            // Update user reminder tracking
            $user->update([
                'last_profile_reminder_at' => now(),
                'profile_reminder_count' => $newReminderCount,
            ]);

            // Send reminder notification
            $user->notify(new ProfileReminderNotification($newReminderCount));

            // If this is the second reminder, notify admin
            if ($newReminderCount >= 2) {
                $adminNoticesCount++;
                $this->notifyAdmins($user);
            }
        }

        $this->info("Sent {$reminderCount} reminder(s) to users.");
        if ($adminNoticesCount > 0) {
            $this->warn("Sent {$adminNoticesCount} notice(s) to admins for users requiring profile deletion review.");
        }

        return Command::SUCCESS;
    }

    /**
     * Notify all admins about a user who needs profile deletion review
     */
    protected function notifyAdmins(User $user): void
    {
        $admins = User::where('is_admin', true)->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new AdminProfileDeletionNotice($user));
        }
    }
}
