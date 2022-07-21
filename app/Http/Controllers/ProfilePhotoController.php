<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * Class ProfilePhotoController
 * @package App\Http\Controllers
 */
class ProfilePhotoController extends Controller
{
    /**
     * @OA\Delete(
     *     path="/api/user/profile-photo",
     *     summary="Elimina la foto de perfil.",
     *     description="Elimina la foto de perfil del usaurio autenticado.",
     *     @OA\Response(
     *         response="200",
     *         description="Retorna la url predeterminada de la foto de perfil.",
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = user();

        $user->deleteProfilePhoto();

        return new JsonResponse($user->getProfilePhotoUrlAttribute());
    }
}
