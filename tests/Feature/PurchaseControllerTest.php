<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\SoldProduct;
use App\Models\User;
use App\Security\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseControllerTest extends TestCase
{
    /**
     * Prueba del endpoint para vender un producto.
     */
    public function test_buy()
    {
        $user = User::factory()->create();

        $product = Product::query()->first();

        $stock = $product->stock;

        $response = $this->actingAs($user)
            ->post(route('buy'), [
               'product_id' => $product->id
            ]);

        $response->assertOk();

        $product->refresh();

        $this->assertEquals($stock - 1, $product->stock);

        $soldProduct = SoldProduct::query()
            ->where('product_id', $product->id)
            ->where('sale_price', $product->price)
            ->first();

        $this->assertNotNull($soldProduct);
    }

    /**
     * Prueba de petición de las ventas.
     */
    public function test_sales()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $response = $this->actingAs($user)
            ->get(route('sales'));

        $response->assertOk();

        print_r($response->content());
    }
}
