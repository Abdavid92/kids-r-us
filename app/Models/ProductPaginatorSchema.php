<?php


namespace App\Models;

use OpenApi\Annotations as OA;

/**
 * Class ProductPaginatorSchema
 * @package App\Models
 *
 * @OA\Schema(
 *     schema="ProductPaginatorSchema",
 *     @OA\Property(property="current_page", type="int", description="Página actual"),
 *     @OA\Property(property="data", type="array", description="Datos", @OA\Items(ref="#components/schemas/Product")),
 *     @OA\Property(property="first_page_url", type="string", description="Url de la primera página"),
 *     @OA\Property(property="last_page", type="int", description="Última página"),
 *     @OA\Property(property="last_page_url", type="string", description="Url de la última página"),
 *     @OA\Property(property="links", type="object", description="Enlaces"),
 *     @OA\Property(property="next_page_url", type="string", description="Url de la próxima página"),
 *     @OA\Property(property="per_page", type="int", description="Elementos por página"),
 *     @OA\Property(property="prev_page_url", type="string", description="Url de la página anterior"),
 *     @OA\Property(property="from", type="int", description="Número inicial de la cantidad de elementos mostrados"),
 *     @OA\Property(property="to", type="int", description="Número final de la cantidad de elementos mostrados"),
 *     @OA\Property(property="total", type="int", description="Cantidad total de elementos")
 * )
 */
class ProductPaginatorSchema
{
    //Clase vacía para crear el esquema de Paginación de productos.
}
