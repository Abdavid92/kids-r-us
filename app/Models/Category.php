<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Annotations as OA;
use Ramsey\Collection\Collection;

/**
 * Class Category
 * @package App\Models
 *
 * @OA\Schema(
 *     schema="Category",
 *     @OA\Property(property="name", type="string", description="Nombre de la categoría"),
 *     example={
 *         "name": "For Boys"
 *     }
 * )
 *
 * @property int $id
 * @property string $name
 * @property Collection $products
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
