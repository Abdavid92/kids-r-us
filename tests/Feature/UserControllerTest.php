<?php

namespace Tests\Feature;

use App\Models\User;
use App\Security\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class UserControllerTest
 * @package Tests\Feature
 */
class UserControllerTest extends TestCase
{
    /**
     * Prueba de petición del usuario autenticado.
     */
    public function test_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('user'));

        $response->assertStatus(200);

        print_r($response->content());
    }

    /**
     * Prueba de petición de todos los usuarios.
     */
    public function test_index()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $response = $this->actingAs($user)
            ->get(route('users'));

        $response->assertStatus(200);

        print_r($response->content());
    }

    /**
     * Prueba de petición de un usuario por el id.
     */
    public function test_show()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $user_id = User::query()->first(['id'])->id;

        $response = $this->actingAs($user)
            ->get(route('users.show', ['user' => $user_id]));

        $response->assertStatus(200);

        print_r($response->content());
    }

    /**
     * Prueba de asignación de roles a un usuario.
     */
    public function test_assign_role()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $user_id = User::query()->where('id', '!=', $user->id)
            ->first(['id'])->id;

        //$user_id = User::query()->first(['id'])->id;

        $response = $this->actingAs($user)
            ->patch(route('users.assign-role', ['user' => $user_id]), [
                'role' => 'edit'
            ]);

        $response->assertStatus(200);
    }

    /**
     * Prueba de eliminación de un rol a un usuario. Para esta prueba se necesitan
     * el menos dos usuarios en la base de datos ya que no se puede eliminar los
     * roles del usuario autenticado.
     */
    public function test_remove_role()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $target_user = User::query()->where('id', '!=', $user->id)
            ->first();

        $this->assertNotNull($target_user);

        $response = $this->actingAs($user)
            ->patch(route('users.remove-role', ['user' => $target_user->id]), [
                'role' => 'edit'
            ]);

        $response->assertStatus(200);
    }

    /**
     * Prueba de eliminación de un usuario. Para esta prueba se necesitan al menos dos
     * usuarios en la base de datos ya que no se puede eliminar el usuario autenticado
     * a través de este endpoint.
     */
    public function test_destroy()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $target_user = User::query()->where('id', '!=', $user->id)
            ->first();

        $this->assertNotNull($target_user);

        $response = $this->actingAs($user)
            ->delete(route('users.destroy', ['user' => $target_user->id]));

        $response->assertStatus(200);
    }
}
