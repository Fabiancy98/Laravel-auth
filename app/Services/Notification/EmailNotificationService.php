<?php

namespace App\Services\Notification;

use App\Models\User;
use Ferdous\OtpValidator\Models\Otps;
use \App\Mail\SendMail;

class EmailNotificationService implements NotificationsInterface
{

    private $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function accountVerificationNotification($userType = null)
    {
        if ($otp = Otps::where([["email", $this->user->email], ["type", "AccountVerification"]])->latest()->first()) {
            $code = $otp->otp;
            $token = $otp->uuid;
            \Mail::to($this->user->email)->send(new SendMail(
                [
                    "Account Verification", "noreply@moses.com", "notifications.emails.accountVerification",
                    [
                        'username' => $this->user->username,
                        'code' => $code,
                        'link' => OTPLink($this->user->email, 'Email', $code, $token, 'AccountVerification'),
                    ],
                ]
            ));
        }
    }

    public function passwordResetNotification()
    {
        if ($otp = Otps::where([["email", $this->user->email], ["type", "ResetPassword"]])->latest()->first()) {
            $code = $otp->otp;
            $token = $otp->uuid;
            \Mail::to($this->user->email)->queue(new SendMail([
                "Password Reset", "noreply@moses.com", "notifications.emails.forgotPassword",
                [
                    'username' => $this->user->first_name,
                    'code' => $code,
                    'link' => OTPLink($this->user->email, 'Email', $code, $token, 'ResetPassword'),
                ],
            ]));
        }
    }
}
