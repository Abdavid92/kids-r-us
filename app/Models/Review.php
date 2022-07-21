<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * Class Review
 * @package App\Models
 *
 * @OA\Schema(
 *     schema="Review",
 *     @OA\Property(property="assessment", type="int", description="Valoración"),
 *     @OA\Property(property="comment", type="string", description="Comentario"),
 *     @OA\Property(property="user_id", type="int", description="Is del usuario al que pertenece la reseña"),
 *     @OA\Property(property="product_id", type="int", description="Id del producto al que pertenece la reseña")
 * )
 *
 * @property int $id
 * @property int $assessment
 * @property string $comment
 * @property string $photo
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property User $user
 * @property Product $product
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment',
        'comment',
        'product_id',
        'user_id'
    ];

    protected $appends = [
        'photo'
    ];

    public function getPhotoAttribute(): string
    {
        return $this->user->getProfilePhotoUrlAttribute();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
