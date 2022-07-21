<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordControllerTest extends TestCase
{
    /**
     * Prueba de cambio de contraseña.
     */
    public function test_password_update()
    {

        $user = User::factory()->create([
            'password' => Hash::make(static::$password)
        ]);

        $response = $this->actingAs($user)->post(route('password-update'), [
            'current_password' => static::$password,
            'password' => '1234abcde',
            'password_confirmation' => '1234abcde'
        ], $this->authenticationHeader);

        $response->assertStatus(200);

        //Rollback de la contraseña para que las demás pruebas puedan funcionar
        $response = $this->post(route('password-update'), [
            'current_password' => '1234abcde',
            'password' => static::$password,
            'password_confirmation' => static::$password
        ], $this->authenticationHeader);

        $response->assertStatus(200);
    }
}
