<?php


namespace App\Http\Responses;

use App\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use App\Models\User;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{

    /**
     * @OA\Schema(
     *     schema="LoginResponse",
     *     @OA\Property(property="token", type="string"),
     *     @OA\Property(property="expiration_in_minutes", type="int"),
     *     example={"token": "3|fswsfwhoicushwuihiwuhfd38fsuihf", "expiration_in_minutes": 30}
     * )
     * @param User $user
     * @return Response
     */
    public function toResponse(User $user): Response
    {
        $token = $user->createToken('login_token');

        return response()->json([
            'token' => $token->plainTextToken,
            'expiration_in_minutes' => config('sanctum.expiration')
        ]);
    }
}
