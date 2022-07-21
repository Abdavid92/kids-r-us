<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;
use Throwable;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Obtiene todas las categorías.",
     *     description="Obtiene todas las categorías",
     *     @OA\Response(
     *         response="200",
     *         description="Una lista con todas las categorías"
     *     )
     * )
     * @return Collection
     * @throws AuthorizationException
     */
    public function index(): Collection
    {
        $this->authorize('viewAny', Category::class);

        return Category::all();
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Crea una nueva categoría.",
     *     description="Crea una nueva categoría.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\JsonContent(oneOf={
     *             @OA\Schema(ref="#components/schemas/Category")
     *         })
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Category::class);

        $this->validateRequest($request);

        $category = Category::query()->create([
            'name' => $request->input('name')
        ]);

        return response()->json($category, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{category}",
     *     summary="Obtiene una categoría por el id.",
     *     description="Obtiene una categoría por el id.",
     *     @OA\PathParameter(name="category", allowEmptyValue=false, description="Id de la categoría"),
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\JsonContent(oneOf={
     *              @OA\Schema(ref="#components/schemas/Category")
     *         })
     *     )
     * )
     * @param Category $category
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Category $category): JsonResponse
    {
        $this->authorize('view', $category);

        return response()->json($category);
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{category}",
     *     summary="Actualiza una categoría.",
     *     description="Actualiza una categoría.",
     *     @OA\PathParameter(name="category", description="Id de la categoría"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param Request $request
     * @param Category $category
     * @return JsonResponse
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $this->authorize('update', $category);

        $this->validateRequest($request);

        $category->forceFill([
            'name' => $request->input('name')
        ])->save();

        return new JsonResponse();
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{category}",
     *     summary="Elimina una categoría",
     *     description="Elimina una categoría. Tenga en cuenta que si esta categoría tiene relaciones con
     *                  algún producto no se podrá eliminar.",
     *     @OA\PathParameter(name="category", description="Id de la categoría"),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param Category $category
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->authorize('delete', $category);

        if (! $category->delete()) {
            abort(422);
        }

        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    private function validateRequest(Request $request)
    {
        $this->validate($request, [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
            ]
        ]);
    }
}
