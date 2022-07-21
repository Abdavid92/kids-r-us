<?php

namespace App\Http\Controllers;

use App\Models\SoldProduct;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * Class ProfitsController
 * @package App\Http\Controllers
 */
class ProfitsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profits",
     *     summary="Obtiene las ganancias totales",
     *     description="Obtiene las ganancias totales. Se requieres permisos de administrador o editor.",
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="profits", type="number", description="Ganancias totales"),
     *                 example={
     *                     "profits": 435
     *                 }
     *             )
     *         )
     *     )
     * )
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function profits(): JsonResponse
    {
        $this->authorize('viewAny', SoldProduct::class);

        return response()->json([
            'profits' => $this->totalProfits()
        ]);
    }

    private function totalProfits(): float
    {
        $total = 0.0;

        foreach (SoldProduct::all() as $soldProduct) {

            $total += $soldProduct->sale_price;
        }

        return $total;
    }
}
