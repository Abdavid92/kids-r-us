<?php


namespace App\Http\Responses\Contracts;


use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

interface PasswordUpdateResponse
{
    /**
     * @param User $user
     * @return Response
     */
    function toResponse(User $user): Response;
}
