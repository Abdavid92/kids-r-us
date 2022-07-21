<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Responses\Contracts\LoginResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;
use OpenApi\Annotations as OA;

/**
 * Class AuthenticationController
 * @package App\Http\Controllers
 */
class AuthenticationController extends Controller
{

    /**
     * Inicia sesión y retorna un token de acceso con una duración de 30 minutos.
     *
     * @OA\Post(
     *     path="/api/login",
     *     summary="Inicia sessión",
     *     description="Inicia sesión con las credenciales de una cuenta existente.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", type="string"),
     *                 example={
     *                     "email": "juan@mail.com",
     *                     "password": "abcd1234"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\JsonContent(oneOf={
     *             @OA\Schema(ref="#components/schemas/LoginResponse")
     *         })
     *     )
     * )
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = User::query()
            ->where('email', $request->input('email'))
            ->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {

            throw ValidationException::withMessages([
               'email' => [__('auth.auth_failed')]
            ]);
        }

        return app(LoginResponse::class)->toResponse($user);
    }
}
