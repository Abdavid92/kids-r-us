<?php

namespace App\Models;

use App\Traits\HasProductPhotos;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\Pure;
use OpenApi\Annotations as OA;

/**
 * Class Product
 * @package App\Models
 *
 * @OA\Schema(
 *     schema="Product",
 *     @OA\Property(property="id", type="int", description="Identificador único"),
 *     @OA\Property(property="name", type="string", description="Nombre"),
 *     @OA\Property(property="price", type="number", description="Precio"),
 *     @OA\Property(property="old_price", type="number", description="Precio antiguo (Opcional)"),
 *     @OA\Property(property="stock", type="int", description="Existencias"),
 *     @OA\Property(property="tags", type="array", description="Etiquetas", @OA\Items(title="Blouse"), @OA\Items(title="Girls")),
 *     @OA\Property(property="description", type="string", description="Descripción"),
 *     @OA\Property(property="additional_information", type="array", description="Información adicional", @OA\Items(title="color"), @OA\Items(title="material")),
 *     @OA\Property(property="assessment", type="number", description="Valoración"),
 *     @OA\Property(property="category_name", type="string", description="Nombre de la categoría a la que pertenece el producto"),
 *     example={
 *         "id": 1,
 *         "name": "Blue Blouse",
 *         "price": 22.00,
 *         "old_price": null,
 *         "stock": 9,
 *         "tags": {
 *             "Blouse",
 *             "Girls"
 *         },
 *         "description": "Alguna descripción...",
 *         "additional_information": {
 *             "color": "blue",
 *             "material": "cotton",
 *             "age": "6 years"
 *         },
 *         "assessment": 4.5,
 *         "category_name": "For Girls"
 *     }
 * )
 *
 * @property int $id
 * @property string $name
 * @property double $price
 * @property double $old_price
 * @property int $stock
 * @property array $tags
 * @property string $description
 * @property array $additional_information
 * @property float $assessment
 * @property int $category_id
 * @property Category $category
 * @property string $category_name
 * @property Collection $reviews
 * @property Collection $productPhotos
 * @property Collection $sales
 * @method static Builder search(int|string $key, mixed $value, string $boolean = 'and')
 * @method static create(array $array)
 */
class Product extends Model
{
    use HasFactory, HasProductPhotos;

    protected $fillable = [
        'name',
        'price',
        'old_price',
        'stock',
        'tags',
        'description',
        'additional_information',
        'category_id',
        'category_name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
        'photo_paths' => 'array',
        'additional_information' => 'array'
    ];

    protected $hidden = [
        'category'
    ];

    protected $appends = [
        'category_name'
    ];

    protected $with = [
        'productPhotos'
    ];

    /**
     * Nombre de la categoría.
     *
     * @return string
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->category->name;
    }

    /**
     * Valoración.
     *
     * @return float
     */
    #[Pure]
    public function getAssessmentAttribute(): float
    {
        $total = 0;
        $count = $this->reviews->count();

        if ($count == 0) {
            return 0.0;
        }

        foreach ($this->reviews as $review) {
            $total += $review->assessment;
        }

        return (float) $total / $count;
    }

    /**
     * Fotos.
     *
     * @return HasMany
     */
    public function productPhotos(): HasMany
    {
        return $this->hasMany(ProductPhoto::class);
    }

    /**
     * Categoría.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Reseñas.
     *
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Ventas.
     *
     * @return HasMany
     */
    public function sales(): HasMany
    {
        return $this->hasMany(SoldProduct::class);
    }

    /**
     * Función scope para buscar productos por sus atributos.
     *
     * @param Builder $query
     * @param $attribute
     * @param $value
     * @param string $boolean
     * @return Builder
     */
    public function scopeSearch(Builder $query, $attribute, $value, string $boolean = 'and'): Builder
    {
        if ($attribute && $value) {
            return $query->where($attribute, 'like', "%$value%", $boolean);
        }

        return $query;
    }
}
