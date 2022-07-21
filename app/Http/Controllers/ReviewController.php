<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;
use Throwable;

class ReviewController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reviews/{product}",
     *     summary="Obtiene las reseñas de un producto",
     *     description="Obtiene las reseñas de un producto.",
     *     @OA\PathParameter(name="product", allowEmptyValue=false, description="Id del producto"),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param Product $product
     * @return LengthAwarePaginator
     */
    public function index(Product $product): LengthAwarePaginator
    {
        return $product->reviews()->paginate(ProductController::$perPage);
    }

    /**
     * @OA\Post(
     *     path="/api/reviews",
     *     summary="Guarda una nueva reseña",
     *     description="Guarda una nueva reseña de un producto.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/Review")
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/Review")
     *         )
     *     )
     * )
     * @param StoreReviewRequest $request
     * @return Response
     */
    public function store(StoreReviewRequest $request): Response
    {
        $review = Review::query()->create([
            'assessment' => $request->input('assessment'),
            'comment' => $request->input('comment'),
            'product_id' => $request->input('product_id'),
            'user_id' => user()->id
        ]);

        return response($review, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/reviews/{review}",
     *     summary="Actualiza una reseña",
     *     description="Actualiza una reseña del usaurio autenticado.",
     *     @OA\PathParameter(name="review", allowEmptyValue=false, description="Id de la reseña"),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/Review")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param Request $request
     * @param Review $review
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, Review $review): JsonResponse
    {
        if (user()->id != $review->user->id) {
            abort(404, 'Review not belong to user');
        }

        $this->validate($request, [
            'assessment' => ['integer', 'min:1', 'max:5'],
            'comment' => ['string']
        ]);

        $review->forceFill([
            'assessment' => $request->input('assessment', $review->assessment),
            'comment' => $request->input('comment', $review->comment)
        ])->save();

        return new JsonResponse();
    }

    /**
     * @OA\Delete(
     *     path="/api/reviews/{review}",
     *     summary="Elimina un reseña",
     *     description="elimina una reseña del usuario autenticado",
     *     @OA\PathParameter(name="review", allowEmptyValue=false, description="Id de la reseña"),
     *     @OA\Response(
     *         response="200",
     *         description="Ok"
     *     )
     * )
     * @param Review $review
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Review $review): JsonResponse
    {
        if (user()->id != $review->user->id) {
            abort(404, 'Review not belong to user');
        }

        $review->deleteOrFail();

        return new JsonResponse();
    }
}
