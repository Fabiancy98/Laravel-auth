<?php

use App\Models\User;
use Ferdous\OtpValidator\Object\OtpRequestObject;
use Ferdous\OtpValidator\Object\OtpValidateRequestObject;
use Ferdous\OtpValidator\OtpValidator;




if (!function_exists('unreadNotes')) {

    function unreadNotes($type, ?User $user=null): int | string
    {
        $user = $user ?: User::find(1);
        return count(
            Arr::where($user->unreadNotifications->toArray(), function ($value, int $key) use ($type) {
                return $value['type'] === "App\Notifications\\$type";
            }),
        ) ?: "";
    }
}


if (!function_exists('createOTP')) {
    /**
     * @param string $user, $type, $client_req
     *
     */
    function createOTP(User $user, string $type = "")
    { //NB:notification is disabled in env as it will be sent through the NotificationService
        $otp = [];
        try {
            $otp = OtpValidator::requestOtp(
                new OtpRequestObject($user->id, $type, $user->email ?? 'fabiancy25@gmail.com')
            );
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return $otp;
    }
}
if (!function_exists('resendOTP')) {
    function resendOTP($uniqueId)
    {
        return OtpValidator::resendOtp($uniqueId);
    }
}
if (!function_exists('verifyOTP')) {
    function verifyOTP(string $otp, string $token)
    {
        $data = [];
        try {
            $data = [
                'resp' => [
                    200 => 'Order Confirmed !!!',
                    204 => 'Too Many Try, are you human !!!',
                    203 => 'Invalid OTP given',
                    404 => 'Request not found'
                ],
                'validate' =>  OtpValidator::validateOtp(
                    new OtpValidateRequestObject($token, $otp)
                )
            ];
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return $data;
    }
}
if (!function_exists('OTPLink')) {
    /**
     * @param string $username => user email or phone
     * @param string $userType => email or phone
     * @param string $code => otp code
     * @param string $token => otp unique Id
     * @param string $type => otp type
     * @return  string verification link
     */
    function OTPLink(string $username, string $userType, string $code, string $token, string $type)
    {
        return env('APP_URL') . "/auth/otp?type=$type&code=$code&user$userType=$username&token=$token";
    }
}
