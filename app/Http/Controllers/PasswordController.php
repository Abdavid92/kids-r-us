<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Responses\Contracts\PasswordUpdateResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

/**
 * Class PasswordController
 * @package App\Http\Controllers
 */
class PasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/password-update",
     *     summary="Endpoint para actualizar la contraseña.",
     *     description="Endpoint para actualizar la contraseña del usuario autenticado.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="current_password", type="string", description="Contraseña actual."),
     *                 @OA\Property(property="password", type="string", description="Nueva contraseña."),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string",
     *                     description="Confirmación de la nueva contraseña."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param UpdatePasswordRequest $request
     * @return mixed
     * @throws ValidationException
     */
    public function update(UpdatePasswordRequest $request): JsonResponse
    {
        $user = user();

        if (! Hash::check($request->input('current_password'), $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => [__('auth.password')]
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($request->input('password'))
        ])->save();

        return app(PasswordUpdateResponse::class)->toResponse($user);
    }
}
