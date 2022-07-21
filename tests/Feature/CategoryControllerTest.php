<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Security\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class CategoryControllerTest
 * @package Tests\Feature
 */
class CategoryControllerTest extends TestCase
{
    /**
     * Prueba de petición de las categorías.
     */
    public function test_index()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $response = $this->actingAs($user)
            ->get(route('categories.index'));

        $response->assertStatus(200);

        $content = $response->content();

        $this->assertNotNull($content);
        $this->assertNotEmpty($content);

        print_r($content);
    }

    /**
     * Prueba de inserción de una categoría.
     */
    public function test_store()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $response = $this->actingAs($user)
            ->post(route('categories.store'), [
                'name' => fake()->name()
            ]);

        $response->assertCreated();

        print_r($response->content());
    }

    /**
     * Prueba de petición de una categoría por el id.
     */
    public function test_show()
    {
        $id = Category::query()->first()->id;

        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $response = $this->actingAs($user)
            ->get(route('categories.show', ['category' => $id]));

        $response->assertOk();

        print_r($response->content());
    }

    /**
     * Prueba de actualización de una categoría.
     */
    public function test_update()
    {
        $id = Category::query()->first()->id;

        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $response = $this->actingAs($user)
            ->put(route('categories.update', ['category' => $id]), [
                'name' => fake()->name()
            ]);

        $response->assertOk();
    }

    /**
     * Prueba de eliminación de una categoría.
     */
    public function test_destroy()
    {
        $id = Category::query()->first()->id;

        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $response = $this->actingAs($user)
            ->delete(route('categories.destroy', ['category' => $id]));

        $response->assertOk();
    }
}
