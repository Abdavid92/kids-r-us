<?php


namespace App\Http\Responses;

use App\Http\Responses\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PasswordUpdateResponse implements PasswordUpdateResponseContract
{

    /**
     * @param User $user
     * @return Response
     */
    function toResponse(User $user): Response
    {
        return new JsonResponse('', 200);
    }
}
