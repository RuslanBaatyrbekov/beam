<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Profile",
 *     type="object",
 *     title="Profile",
 *     required={"id", "user_id", "address", "phone"},
 *     @OA\Property(property="id", type="integer", description="ID профиля"),
 *     @OA\Property(property="user_id", type="integer", description="ID пользователя, с которым связан профиль"),
 *     @OA\Property(property="address", type="string", description="Адрес пользователя"),
 *     @OA\Property(property="phone", type="string", description="Телефон пользователя"),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User"
 *     )
 * )
 */

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'address', 'phone'];

    /**
     * Связь с пользователем.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
