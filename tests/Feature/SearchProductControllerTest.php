<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchProductControllerTest extends TestCase
{
    /**
     * Prueba de búsqueda de productos.
     */
    public function test_search()
    {
        $user = User::factory()->create();

        $url = route('products.search', [
            'tags' => 'Girls',
            'category' => 'For Boys',
            'price' => 15.00,
            'stock' => 0
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertOk();

        print_r($response->content());
    }

    /**
     * Prueba de cantidad de resultados de la búsqueda de productos.
     */
    public function test_search_count()
    {
        $user = User::factory()->create();

        $url = route('products.search-count', [
            'tags' => 'Girls',
            'category' => 'For Boys',
            'price' => 15.00,
            'stock' => 0
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertOk();

        print_r($response->content());
    }
}
