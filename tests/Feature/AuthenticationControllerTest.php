<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    /**
     * Prueba de inicio de sesión.
     */
    public function test_login()
    {
        $response = $this->post(route('login'), [
            'email' => static::$email,
            'password' => static::$password
        ]);

        $response->assertStatus(200);

        $content = $response->content();

        $this->assertNotNull($content);
        $this->assertNotEmpty($content);

        print_r($content);
    }
}
