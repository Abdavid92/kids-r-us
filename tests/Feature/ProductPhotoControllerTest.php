<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\User;
use App\Security\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductPhotoControllerTest extends TestCase
{
    /**
     * Prueba para subir una foto de un producto.
     * Para ejecutar esta prueba debe tener la extension 'gd' instalada en el intérprete de php.
     * Puede ir al archivo php.ini, buscarla y descomentarla.
     */
    public function test_store()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        Storage::fake('public');

        $photo = UploadedFile::fake()->image('product.jpeg');

        $id = Product::query()->first()->id;

        $response = $this->actingAs($user)
            ->post(route('products.store-photo', ['product' => $id]), [
               'photo' => $photo
            ]);

        $response->assertCreated();
    }

    /**
     * Prueba de eliminación de una foto de un producto por el id.
     */
    public function test_destroy()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $id = ProductPhoto::query()->first()->id;

        $response = $this->actingAs($user)
            ->delete(route('products.destroy-photo', ['photo' => $id]));

        $response->assertOk();
    }
}
