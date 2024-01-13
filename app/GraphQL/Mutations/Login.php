<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;

final readonly class Login
{
    use ApiResponse;
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        if (!\Auth::attempt(\Arr::only($args, ['email', 'password']))) {
            $response = $this->failed([], "Invalid credentials.", Response::HTTP_FORBIDDEN);
        } else {
            $user = auth('sanctum')->user();
            if ($user->user_verified_at) {
                $response = $this->success([
                    'token'  => $user->createToken($args['device'])->plainTextToken,
                    'user' => $user
                ], "", Response::HTTP_ACCEPTED);
            } else {
                $response = $this->failed([], "Account Verification required", Response::HTTP_UNAUTHORIZED);
            }
        }

        return $response;
    }
}
