<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class SearchProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products/search",
     *     summary="Busca los productos por las características",
     *     description="Realiza una búsqueda de los productos por las características dadas.",
     *     @OA\Parameter(name="name", in="query", description="Nombre del producto a buscar"),
     *     @OA\Parameter(name="category", in="query", description="Categoría del producto"),
     *     @OA\Parameter(name="tags", in="query", description="Etiquetas que pueda tener el producto"),
     *     @OA\Parameter(name="price", in="query", description="Precio"),
     *     @OA\Parameter(name="stock", in="query", description="Existencias"),
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#components/schemas/ProductPaginatorSchema")
     *         )
     *     )
     * )
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function search(Request $request): LengthAwarePaginator
    {
        $builder = $this->searchBuilder($request);

        if ($builder) {
            return $builder->paginate(ProductController::$perPage);
        }

        return Product::query()->paginate(ProductController::$perPage);
    }

    /**
     * @OA\Get(
     *     path="/api/products/search-count",
     *     summary="Busca los productos por las características",
     *     description="Realiza una búsqueda de los productos por las características dadas.",
     *     @OA\Parameter(name="name", in="query", description="Nombre del producto a buscar"),
     *     @OA\Parameter(name="category", in="query", description="Categoría del producto"),
     *     @OA\Parameter(name="tags", in="query", description="Etiquetas que pueda tener el producto"),
     *     @OA\Parameter(name="price", in="query", description="Precio"),
     *     @OA\Parameter(name="stock", in="query", description="Existencias"),
     *     @OA\Response(
     *         response="200",
     *         description="Retorna la cantidad de productos encontrados",
     *     )
     * )
     * @param Request $request
     * @return int
     */
    public function searchCount(Request $request): int
    {
        $builder = $this->searchBuilder($request);

        if ($builder) {
            return $builder->count();
        }

        return 0;
    }

    private function searchBuilder(Request $request): Builder|null
    {
        $query = $request->query();

        if ($query && count($query) > 0) {

            foreach ($query as $key => $value) {

                if ($key === 'category') {
                    $category = Category::query()->where('name', $value)
                        ->first();

                    if ($category) {

                        $key = 'category_id';
                        $value = $category->id;
                    }
                }

                if (is_numeric($value)) {
                    $value_field = $value;
                } else
                    $value_field = "%$value%";

                if (! isset($builder)) {
                    $builder = Product::query()->where($key, 'like', $value_field);
                } else {
                    $builder = $builder->orWhere($key, 'like' , $value_field);
                }
            }
        }

        return $builder ?? null;
    }
}
