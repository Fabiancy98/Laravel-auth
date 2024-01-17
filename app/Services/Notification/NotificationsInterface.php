<?php

namespace App\Services\Notification;



interface NotificationsInterface
{
    public function accountVerificationNotification( $userType);

    public function passwordResetNotification();
}
