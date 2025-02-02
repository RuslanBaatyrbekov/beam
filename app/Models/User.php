<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", description="ID пользователя"),
 *     @OA\Property(property="name", type="string", description="Имя пользователя"),
 *     @OA\Property(property="email", type="string", description="Email пользователя"),
 *     @OA\Property(property="orders", type="array", @OA\Items(ref="#/components/schemas/Order")),
 *     @OA\Property(property="profile", ref="#/components/schemas/Profile")
 * )
 */


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Связь с заказами пользователя.
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Связь с профилем пользователя.
     *
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
}
