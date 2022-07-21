<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;
use Throwable;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Obtiene todos los usuarios.",
     *     description="Obtiene todos los usuarios en la base de datos. Este endpoint requiere permisos
     *                  de administrador.",
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @return LengthAwarePaginator
     * @throws AuthorizationException
     */
    public function index(): LengthAwarePaginator
    {
        $this->authorize('viewAny', User::class);

        return User::query()->paginate(10);
    }

    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Obtiene el usuario autenticado.",
     *     description="Obtiene el usuario autenticado.",
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\JsonContent(oneOf={
     *             @OA\Schema(ref="#components/schemas/User")
     *         })
     *     )
     * )
     * @param Request $request
     * @return mixed
     */
    public function user(Request $request): mixed
    {
        return $request->user();
    }

    /**
     * @OA\Get(
     *     path="/api/users/{user}",
     *     summary="Obtiene un usuario por el id.",
     *     description="Obtiene un usuario por el id. Este enpoint requiere permisos de administrador",
     *     @OA\PathParameter(name="user", allowEmptyValue=false, description="Id del usuario"),
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\JsonContent(oneOf={
     *             @OA\Schema(ref="#components/schemas/User")
     *         })
     *     )
     * )
     * @param User $user
     * @return Response
     * @throws AuthorizationException
     */
    public function show(User $user): Response
    {
        $this->authorize('view', $user);

        return response($user);
    }

    /**
     * @OA\Patch(
     *     path="/api/users/{user}/assign-role",
     *     summary="Asigna un rol dado a un usuario",
     *     description="Asigna un rol dado a un usuario. Este endpoint requiere permisos de administrador.",
     *     @OA\PathParameter(name="user", allowEmptyValue=false, description="Id del usaurio"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="role", type="string", description="Rol a asignar"),
     *                 example={
     *                     "role": "edit"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function assignRole(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $this->validate($request, [
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')]
        ]);

        $user->assignRole($request->input('role'));

        return new JsonResponse();
    }

    /**
     * @OA\Patch(
     *     path="/api/users/{user}/remove-role",
     *     summary="Elimina un rol a un usaurio.",
     *     description="elimina un rol a un usuario. Este endpoint requiere permisos de administrador. No se puede
     *                  eliminar un rol al usaurio autenticado.",
     *     @OA\PathParameter(name="user", allowEmptyValue=false, description="Id del usaurio"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="role", type="string", description="Rol a asignar"),
     *                 example={
     *                     "role": "edit"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function removeRole(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $this->validate($request, [
            'role' => ['required', 'string', 'exists:roles,name']
        ]);

        if (user()->id == $user->id) {
            abort(422, __('user.remove_role_failed'));
        }

        $user->removeRole($request->input('role'));

        return new JsonResponse();
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{user}",
     *     summary="Elimina un usuario de la base de datos.",
     *     description="Elimina un usuario de la base de datos. Este endpoint requiere permisos de administrador.",
     *     @OA\PathParameter(name="user", allowEmptyValue=false, description="Id del usaurio"),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param User $user
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        if (user()->id == $user->id) {
            abort(422, __('user.remove_user_failed'));
        }

        $user->deleteOrFail();

        return new JsonResponse();
    }
}
