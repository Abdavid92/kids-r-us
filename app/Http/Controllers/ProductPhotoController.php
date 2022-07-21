<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

/**
 * Class ProductPhotoController
 * @package App\Http\Controllers
 */
class ProductPhotoController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/products/photo/{product}",
     *     summary="Guarda una nueva foto de un producto",
     *     description="Guarda una nueva foto de un producto",
     *     @OA\PathParameter(name="product", allowEmptyValue=false, description="Id del producto"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="photo", type="object", description="Foto a subir")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Created"
     *     )
     * )
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        $this->validate($request, [
            'photo' => ['required', 'image', 'max:2048']
        ]);

        $product->addPhoto($request->file('photo'));

        return new JsonResponse('', 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/photo/{photo}",
     *     summary="Elimina una foto de un producto",
     *     description="Elimina una foto de un producto",
     *     @OA\PathParameter(name="photo", allowEmptyValue=false, description="Id de la foto a eliminar"),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param ProductPhoto $photo
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(ProductPhoto $photo): JsonResponse
    {
        $this->authorize('update', $photo->product);

        $photo->product->removePhoto($photo->id);

        return new JsonResponse();
    }
}
