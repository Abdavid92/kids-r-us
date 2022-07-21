<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;
use Throwable;

class ProductController extends Controller
{
    /**
     * Número de elementos por página.
     *
     * @var int
     */
    public static int $perPage = 10;

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Obtiene los productos.",
     *     description="Obtiene los productos por páginas de 10 elementos",
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/ProductPaginatorSchema")
     *         )
     *     )
     * )
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return Product::query()->paginate(static::$perPage);
    }

    /**
     * @OA\Get(
     *     path="/api/products/out-stock",
     *     summary="Obtiene los productos agotados (out of stock)",
     *     description="Obtiene los productos agotados (out of stock)",
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref="#components/schemas/Product")
     *             )
     *         )
     *     )
     * )
     * @return Response
     */
    public function outStock(): Response
    {
        return response(Product::query()
            ->where('stock', 0)
            ->get());
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Crea un nuevo producto",
     *     description="Crea un nuevo producto. El esquema de la petición en igual al del producto excepto por
     *                  category_name que es remplazado por category.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/Product")
     *         )
     *     )
     * )
     * @param StoreProductRequest $request
     * @return Response
     * @throws AuthorizationException
     */
    public function store(StoreProductRequest $request): Response
    {
        $this->authorize('create', Product::class);

        $category = Category::query()->where('name', $request->input('category'))
            ->firstOrFail();

        $product = Product::query()->create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'old_price' => $request->input('old_price'),
            'stock' => $request->input('stock'),
            'tags' => $request->input('tags'),
            'description' => $request->input('description'),
            'additional_information' => $request->input('additional_information'),
            'category_id' => $category->id
        ]);

        if ($request->exists('photo')) {
            $product->addPhoto($request->file('photo'));
        }

        return response($product->toJson(), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{product}",
     *     summary="Obtiene un producto",
     *     description="Obtiene un producto por el id",
     *     @OA\PathParameter(name="product", allowEmptyValue=false),
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/Product")
     *         )
     *     )
     * )
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        return response($product->load('reviews'));
    }

    /**
     * @OA\Put(
     *     path="/api/products/{product}",
     *     summary="Actualiza los datos de un producto",
     *     description="Actualiza los datos de un producto. La actualización puede ser parcial o total.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        $this->validate($request, [
            'name' => ['string', 'max:255'],
            'price' => ['numeric'],
            'old_price' => ['numeric'],
            'stock' => ['integer'],
            'tags' => ['array'],
            'description' => ['string'],
            'additional_information' => ['array'],
            'category' => [
                'string',
                Rule::exists('categories', 'name')
            ]
        ]);

        $category = Category::query()->where('name', $request->input('category'))
            ->first();

        if (! $category) {
            $category = $product->category;
        }

        $product->forceFill([
            'name' => $request->input('name', $product->name),
            'price' => $request->input('price', $product->price),
            'old_price' => $request->input('old_price', $product->old_price),
            'stock' => $request->input('stock', $product->stock),
            'tags' => $request->input('tags', $product->tags),
            'description' => $request->input('description', $product->description),
            'additional_information' => $request->input('additional_information', $product->additional_information),
            'category_id' => $category->id
        ])->save();

        return new JsonResponse();
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{product}",
     *     summary="Elimina un producto",
     *     description="Elimina un producto por el id",
     *     @OA\PathParameter(name="product", allowEmptyValue=false),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param Product $product
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $product->deleteOrFail();

        return new JsonResponse();
    }
}
