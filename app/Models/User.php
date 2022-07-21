<?php

namespace App\Models;

use App\Traits\HasProfilePhoto;
use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;
use OpenApi\Annotations as OA;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @package App\Models
 *
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="int", description="Identificador único"),
 *     @OA\Property(property="name", type="string", description="Nombre de usuario"),
 *     @OA\Property(property="email", type="string", description="Dirección de correo"),
 *     @OA\Property(property="profile_photo_url", type="string", description="Url de la foto de perfil"),
 *     @OA\Property(property="role_names", type="array", description="Los roles del usuario", items={
 *         @OA\Items(title="admin"),
 *         @OA\Items(title="edit")
 *     }),
 *     example={
 *         "id": 1,
 *         "name": "Juan",
 *         "email": "juan@mail.com",
 *         "profile_photo_url": "https://ui-avatars.com/api/?name=J+U&color=7F9CF5&background=EBF4FF",
 *         "role_names": {"edit"}
 *     }
 * )
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $profile_photo_path
 * @property string $profile_photo_url
 * @property DateTime $email_verified_at
 * @property Collection $reviews
 * @method static create(array $array)
 */
class User extends Authenticatable implements HasApiTokensContract
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasProfilePhoto;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'profile_photo_path',
        'roles'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'role_names'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [

    ];

    /**
     * @return Collection
     */
    public function getRoleNamesAttribute(): Collection
    {
        return $this->getRoleNames();
    }

    /**
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
