<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class ProfileInformationController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/user/profile-information",
     *     summary="Endpoint para actualizar los datos del usuario autenticado.",
     *     description="Endpoint para actualizar los datos del usuario autenticado.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Nombre de usuario"),
     *                 @OA\Property(property="email", type="string", description="Dirección de correo"),
     *                 @OA\Property(property="photo", nullable=true, description="Foto de perfil"),
     *                 example={
     *                     "name": "Juan",
     *                     "email": "juan@gmail.com",
     *                     "photo": "Alguna foto de perfil menor de 2048 bytes..."
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        $user = user();

        if ($request->exists('photo')) {
            $user->updateProfilePhoto($request->file('photo'));
        }

        $email = $request->input('email');

        if ($user->email !== $email && $user instanceof MustVerifyEmail) {

            $this->updateVerifiedUser($user, $request->all());
        } else {

            $user->forceFill([
                'name' => $request->input('name'),
                'email' => $email
            ])->save();
        }

        return new JsonResponse();
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    private function updateVerifiedUser(User $user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
