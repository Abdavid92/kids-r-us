<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * Class SoldProduct
 * @package App\Models
 *
 * @OA\Schema(
 *     schema="SoldProduct",
 *     @OA\Property(property="sake_price", type="number", description="Precio de venta"),
 *     @OA\Property(property="product_id", type="int", description="Id del producto vendido"),
 *     @OA\Property(property="product", description="Producto relacionado")
 * )
 *
 * @property int $id
 * @property double $sale_price
 * @property Product $product
 */
class SoldProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_price',
        'product_id'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['product'];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
