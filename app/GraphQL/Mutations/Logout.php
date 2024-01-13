<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use App\Traits\ApiResponse;

final readonly class Logout
{
    use ApiResponse;
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        $response = $this->success(['user' => $user]);
        return $response;
    }
}
