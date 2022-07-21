<?php

namespace App\Http\Controllers;

use App\Events\ProductSold;
use App\Models\Product;
use App\Models\SoldProduct;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class PurchaseController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/buy",
     *     summary="Endpoint para vender un producto",
     *     description="Endpoint para vender un producto",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="product_id", type="int", description="Id del producto a vender"),
     *                 example={
     *                     "product_id": 1
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
     * @return JsonResponse
     * @throws ValidationException
     */
    public function buy(Request $request): JsonResponse
    {
        $this->validate($request, [
            'product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')
            ]
        ]);

        $product = Product::query()->find($request->input('product_id'));

        if ($product->stock == 0) {
            abort(422, __('product.out_stock'));
        }

        $this->ensureUserCanBuy();

        $product->forceFill([
            'stock' => $product->stock - 1
        ])->save();

        ProductSold::dispatch($product);

        return new JsonResponse();
    }

    /**
     * @OA\Get(
     *     path="/api/sales",
     *     summary="Endpoint para obtener todos los productos vendidos",
     *     description="Endpoint para obtener todos los productos vendidos",
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref="#components/schemas/SoldProduct")
     *             )
     *         )
     *     )
     * )
     * @return Response
     * @throws AuthorizationException
     */
    public function sales(): Response
    {
        $this->authorize('viewAny', SoldProduct::class);

        return response(SoldProduct::all()->toJson());
    }

    /**
     * Verifica que el usuario autenticado cumpla los requisitos necesarios para
     * poder comprar.
     */
    private function ensureUserCanBuy()
    {
        //Not implemented.
    }
}
