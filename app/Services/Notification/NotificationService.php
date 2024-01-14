<?php

namespace App\Services\Notification;

use App\Models\Store;
use App\Models\User;
use App\Services\Notification\EmailNotificationService;
use App\Services\Notification\NotificationsInterface;
use App\Services\Notification\SMSNotificationService;

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
     * @param EmailNotificationService|SMSNotificationService $preferred
     */
    private function preferredNotifier(?string $preferred_means_of_notification)
    {
        $preferred = new EmailNotificationService($this->user);
        if ($this->user?->email && ($preferred_means_of_notification === "Email" || !($this->user->phone['primary'] ?? null))) {
            $preferred = new EmailNotificationService($this->user);
        } elseif (($this->user->phone['primary'] ?? null) && ($preferred_means_of_notification === "SMS" || !$this->user?->email)) {
            $preferred = new SMSNotificationService($this->user);
        } else {
            //Whatsapp to be done
        }
        return $preferred;
    }
    /**
     * @param Artisan|Buyer|Store $userType
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
     * @param Artisan|Buyer|Store $userType
     */
    public function passwordResetNotification()
    {
        if ($this->user?->email) {
            (new EmailNotificationService($this->user))->passwordResetNotification();
        }

        if ($this->user->phone['primary'] ?? null) {
            (new SMSNotificationService($this->user))->passwordResetNotification();
        }

    }

    public function OrderVerificationNotification($userType)
    {
        $this->preferredNotifier($userType->preferred_means_of_notification)->passwordResetNotification($this->user);
    }
}
