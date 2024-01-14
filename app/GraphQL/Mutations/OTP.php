<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Ferdous\OtpValidator\Models\Otps;
use Illuminate\Http\Response;

final class OTP
{
    use ApiResponse;

    private $response, $token, $type, $typeOrToken, $typeOrTokenValue, $user, $username, $userType;

    /**
     * Show the form for creating a new resource.
     *
     * @return $response
     */
    public function create($rootValue, array $args, $context, $resolveInfo)
    {
        
        $this->construct($args);
        if ($this->user) {
            if ($this->token) {
                if (isset($args['resend']) && $args['resend']) {
                    $this->resendOTP($this->token);
                } else {
                    $this->response = $this->failed([], "Valid OTP exists, try resend", Response::HTTP_FORBIDDEN);
                }
            } else {
                $this->createOTP();
            }
        } else {
            $this->response = $this->failed([], "User not found", Response::HTTP_FORBIDDEN);
        }
        return $this->response;
    }

    /**
     * resend OTP
     *
     * @return $response
     */
    public function resend($rootValue, array $args, $context, $resolveInfo)
    {
        $this->construct($args);
        if ($this->token) {
            $this->resendOTP($this->token);
        } else {
            if (isset($args['recreate']) && $args['recreate']) {
                $this->createOTP();
            } else {
                $this->response = $this->failed([], "Valid OTP not found, try recreate", Response::HTTP_FORBIDDEN);
            }
        }
        return $this->response;
    }

    /**
     * Display the specified resource.
     *
     * @return $response
     */
    public function verify($rootValue, array $args, $context, $resolveInfo)
    {
        $this->construct($args);
        $otp = Otps::where([["otp", $args['otp']], ["status", 'new'], [$this->typeOrToken, $this->typeOrTokenValue], [$this->userType, $this->username]])->latest()->first();
        $data = verifyOTP($args['otp'] ?? "", $otp->uuid ?? "");
        if ($data['validate']['code'] === 200) {
            $this->response = $this->success(
                [
                    'token' => $otp->otp,
                    'type' => $otp->type,
                ],
                $data['validate']['message'],
                $data['validate']['code']
            );
            $type = $otp->type;
            if ($type === "AccountVerification") {
                $this->user->update(['user_verified_at' => Carbon::now()]);
            } elseif ($type === "ResetPassword") {
                $this->response['data'] = [
                    'token' => $this->user->createToken('temporary', ['reset'], expiresAt: Carbon::now()->addHour())->plainTextToken,
                    'type' => "Bearer",
                ];
                $this->response['message'] = "Token valid for 1 hour";
            } elseif ($type === "OrderVerification") {
            }
        } else {
            $this->response = $this->failed(
                [],
                $data['validate']['message'],
                $data['validate']['code']
            );
        }
        return $this->response;
    }

    /**
     * Create OTP
     *
     * @param  array $args
     */
    private function createOTP()
    {
        $otp = createOTP($this->user, $this->type);
        if ($otp['code'] === 201) {
            $this->response = $this->success(
                [
                    'token' => $otp['uniqueId'],
                    'user' => [
                        'id' => $this->user->id,
                        'email' => $this->user->email,
                    ],
                ],
                $otp['message'],
                $otp['code']
            );
            $this->notify();
        } else {
            $this->response = $this->failed(
                [],
                $otp['message'],
                $otp['code']
            );
        }
    }
    /**
     * Resend OTP
     *
     * @param  array $args
     */
    private function resendOTP(string $uniqueId)
    {
        $otp = resendOTP($uniqueId);
        if (isset($otp['code']) && $otp['code'] === 201) {
            $this->response = $this->success(
                [
                    'token' => $otp['uniqueId'],
                    'user' => [
                        'id' => $this->user->id,
                        'email' => $this->user->email,
                    ],
                ],
                $otp['message'],
                $otp['code']
            );
            $this->notify();
        } else {
            $this->response = $this->failed(
                [],
                $otp['message'],
                $otp['code']
            );
        }
    }

    /**
     * Fetch User
     *
     * @param  array $args
     */
    public function construct(array $args)
    {
        if (isset($args['email'])) {
            $this->user = User::where('email', $args['email'])->first();
            $this->userType = "email";
            $this->username = $args['email'];
        } else {
            // If email is not provided, you might want to handle this case or log a message.
            // For now, we'll set these values to default values, but you should adapt this part based on your logic.
            $this->user = null;
            $this->userType = "unknown";
            $this->username = "unknown";
        }
        if (isset($args['token'])) {
            $this->typeOrToken = "uuid";
            $this->typeOrTokenValue = $args['token'];
        } else {
            $this->typeOrToken = "type";
            $this->typeOrTokenValue = $args['type'];
        }
        $this->type = $args['type'] ?? "";

            // Check if token exists in Otps table
        $latestOtp = Otps::where([
            ['status', 'new'],
            [$this->typeOrToken, $this->typeOrTokenValue],
            [$this->userType, $this->username]
        ])->latest()->first();

        // Set $this->token based on whether the latest OTP exists
        $this->token = $latestOtp ? $latestOtp->uuid : null;
        $this->response = $this->failed();
        
    }
    /**
     * OTP notification.
     */
    public function notify()
    {
        if ($this->type == "AccountVerification") {
            (new NotificationService($this->user, $this->channelOverride))->accountVerificationNotification();
        } elseif ($this->type === "ResetPassword") {
            (new NotificationService($this->user, $this->channelOverride))->passwordResetNotification();
        } elseif ($this->type === "OrderVerification") {
            // (new NotificationService($this->user,$this->channelOverride))->OrderVerificationNotification();
        }
    }
}
