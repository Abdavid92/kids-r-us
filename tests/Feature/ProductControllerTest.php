<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Security\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class ProductControllerTest
 * @package Tests\Feature
 */
class ProductControllerTest extends TestCase
{
    use WithFaker;

    /**
     * Prueba de petición de los productos.
     */
    public function test_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('products.index'));

        $response->assertOk();

        print_r($response->content());
    }

    public function test_out_stock()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('products.out-stock'));

        $response->assertOk();

        print_r($response->content());
    }

    public function test_store()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $response = $this->actingAs($user)
            ->post(route('products.store'), [
                'name' => 'Black Blouse',
                'price' => 17.00,
                'stock' => 0,
                'tags' => ['Girls', 'Blouse'],
                'description' => fake()->paragraph(),
                'additional_information' => [
                    'color' => 'black',
                    'material' => 'cotton',
                    'age' => '5 years'
                ],
                'category' => 'For Girls'
            ]);

        $response->assertCreated();

        print_r($response->content());
    }

    /**
     * Prueba de petición de un producto por el id.
     */
    public function test_show()
    {
        $user = User::factory()->create();

        $id = Product::query()->first(['id'])->id;

        $response = $this->actingAs($user)
            ->get(route('products.show', ['product' => $id]));

        $response->assertOk();

        print_r($response->content());
    }

    /**
     * Prueba de actualización de un producto.
     */
    public function test_update()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $id = Product::query()->first(['id']);

        $response = $this->actingAs($user)
            ->put(route('products.update', ['product' => $id]), [
                'name' => fake()->name(),
                'stock' => 30,
                'description' => fake()->paragraph()
            ]);

        $response->assertOk();
    }

    /**
     * Prueba de eliminación de un producto.
     */
    public function test_delete()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $id = Product::query()->first(['id']);

        $response = $this->actingAs($user)
            ->delete(route('products.destroy', ['product' => $id]));

        $response->assertOk();
    }
}
