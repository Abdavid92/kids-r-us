<?php

namespace Tests\Feature;

use App\Models\User;
use App\Security\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfitsControllerTest extends TestCase
{
    /**
     * Prueba para obtener las ganancias totales.
     */
    public function test_profits()
    {
        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $response = $this->actingAs($user)
            ->get(route('profits'));

        $response->assertOk();

        print_r($response->content());
    }
}
