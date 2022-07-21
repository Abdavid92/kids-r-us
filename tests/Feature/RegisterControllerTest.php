<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{

    /**
     * Prueba de registro de usuario.
     */
    public function test_register()
    {
        $response = $this->post(route('register'), [
            'name' => static::$name, //'Juan',
            'email' => static::$email, //'juan@gmail.com',
            'password' => static::$password,
            'password_confirmation' => static::$password
        ]);

        $response->assertStatus(201);

        $content = $response->content();

        $this->assertNotNull($content);
        $this->assertNotEmpty($content);

        print_r($content);
    }
}
