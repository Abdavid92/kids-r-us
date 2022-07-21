<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Class ProfilePhotoControllerTest
 * @package Tests\Feature
 */
class ProfilePhotoControllerTest extends TestCase
{
    /**
     * Prueba de eliminación de la foto de perfil.
     */
    public function test_destroy_profile_photo()
    {
        $user = User::factory()->create();

        Storage::fake('public');

        $photo = UploadedFile::fake()->image('avatar.jpeg');

        $photo = UploadedFile::createFromBase($photo);

        $user->updateProfilePhoto($photo);

        $response = $this->actingAs($user)
            ->delete(route('current-profile-photo.destroy'));

        $response->assertStatus(200);

        $default_photo = $response->content();

        $this->assertNotNull($default_photo);
        $this->assertNotEmpty($default_photo);
    }
}
