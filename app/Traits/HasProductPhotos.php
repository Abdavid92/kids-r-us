<?php


namespace App\Traits;


use App\Models\ProductPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasProductPhotos
{
    /**
     * Obtiene la url de una foto del producto.
     *
     * @param string $path
     * @return string|null
     */
    public function getProductPhotoUrl(string $path): ?string
    {
        $productPhoto = $this->productPhotos()->where('path', $path)
            ->first();

        if ($productPhoto == null)
            return null;

        return Storage::disk(static::productPhotoDisk())->url($path);
    }

    /**
     * Añade una nueva foto al producto.
     *
     * @param UploadedFile $photo
     */
    public function addPhoto(UploadedFile $photo)
    {
        ProductPhoto::query()->create([
            'path' => $photo->storePublicly(
                'product-photos', ['disk' => static::productPhotoDisk()]
            ),
            'product_id' => $this->id
        ]);
    }

    /**
     * Elimina una foto del producto.
     *
     * @param int $id
     */
    public function removePhoto(int $id)
    {
        $productPhoto = $this->productPhotos()->find($id);

        if ($productPhoto == null)
            return;

        Storage::disk(static::productPhotoDisk())->delete($productPhoto->path);

        $productPhoto->delete();
    }

    /**
     * Get the disk that product photos should be stored on.
     *
     * @return string
     */
    public static function productPhotoDisk(): string
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public';
    }
}
