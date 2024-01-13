<?php

namespace App\GraphQL\Mutations;

use App\Traits\ApiResponse;
use Illuminate\Http\Response;

final class ResetPassword
{
    use ApiResponse;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {

        if ($user = auth('sanctum')->user()) {
            if (
                $user->currentAccessToken()->name === "temporary" &&
                $user->currentAccessToken()->abilities === ['reset']
            ) {
                $user->update(['password' => $args['password']]);
                $user->currentAccessToken()->forceFill([
                    'expires_at' => \Carbon::now()
                ])->save();
                $response = $this->success(['user' => $user], "Updated successfully");
            } else {
                $response = $this->failed(message: "");
            }
        }
        return $response ?? $this->failed();
    }
}
