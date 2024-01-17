<?php

namespace App\Services\Notification;


use App\Models\User;
use App\Services\Notification\EmailNotificationService;
use App\Services\Notification\NotificationsInterface;

class NotificationService implements NotificationsInterface
{
    private $user, $channel;

    /**
     * Send notification to User
     * @param User|null $user
     */
    public function __construct(User $user = null, array $channel = [])
    {
        $this->user = $user;
        $this->channel = $channel;
    }
    /**
     * @param EmailNotificationService $preferred
     */
    private function preferredNotifier(?string $preferred_means_of_notification)
    {
        $preferred = new EmailNotificationService($this->user);
        if ($this->user?->email && ($preferred_means_of_notification === "Email" )) {
            $preferred = new EmailNotificationService($this->user);
        } 
        return $preferred;
    }
    /**
     * @param User $userType
     */
    public function accountVerificationNotification($userType = null)
    {
        try {
            $this->preferredNotifier($userType?->preferred_means_of_notification)->accountVerificationNotification($userType);
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

    /**
     * @param $userType
     */
    public function passwordResetNotification()
    {
        if ($this->user?->email) {
            (new EmailNotificationService($this->user))->passwordResetNotification();
        }

    }
}
