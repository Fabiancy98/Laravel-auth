<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;


use App\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Http\Response;

final class Register
{

    use ApiResponse;

    private $message, $status;

    public function __construct()
    {
        $this->message = "";
        $this->status = 204;
    }


    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = User::create($args);
            
        $response = $this->success(
            ['user' => $user->fresh()],
            $this->message,
            Response::HTTP_CREATED,
        );
        return $response;
    } 
}
