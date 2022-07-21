<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    /**
     * Prueba de petición de las reseñas de un producto.
     */
    public function test_index()
    {
        $user = User::factory()->create();

        $id = Product::query()->first(['id'])->id;

        $response = $this->actingAs($user)
            ->get(route('reviews.index', ['product' => $id]));

        $response->assertOk();

        print_r($response->content());
    }

    public function test_store()
    {
        $user = User::factory()->create();

        $id = Product::query()->first(['id'])->id;

        $response = $this->actingAs($user)
            ->post(route('reviews.store'), [
                'assessment' => 5,
                'comment' => fake()->paragraph(),
                'product_id' => $id
            ]);

        $response->assertCreated();
    }

    public function test_update()
    {
        $user = User::factory()->create();

        $id = Review::query()->first(['id'])->id;

        $response = $this->actingAs($user)
            ->put(route('reviews.update', ['review' => $id]), [
               'assessment' => 3
            ]);

        $response->assertOk();
    }
}
