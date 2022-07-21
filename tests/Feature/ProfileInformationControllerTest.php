<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Class ProfileInformationControllerTest
 * @package Tests\Feature
 */
class ProfileInformationControllerTest extends TestCase
{
    /**
     * Prueba de actualización de los datos del usuario.
     * Para ejecutar esta prueba debe tener la extension 'gd' instalada en el intérprete de php.
     * Puede ir al archivo php.ini, buscarla y descomentarla.
     */
    public function test_update_profile_information()
    {
        $user = User::factory()->create();

        Storage::fake('public');

        $photo = UploadedFile::fake()->image('avatar.jpeg');

        $response = $this->actingAs($user)
            ->put(route('user.profile-information'), [
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'photo' => $photo
            ]);

        $response->assertStatus(200);
    }
}
