<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductPhoto
 * @package App\Models
 *
 * @property int $id
 * @property string $path
 * @property string $photo_url
 * @property Product $product
 */
class ProductPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'product_id'
    ];

    protected $hidden = [
        'path'
    ];

    protected $appends = [
        'photo_url'
    ];

    public function getPhotoUrlAttribute(): string
    {
        return Storage::disk(Product::productPhotoDisk())->url($this->path);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
