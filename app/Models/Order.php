<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     title="Order",
 *     required={"id", "user_id", "total_price", "status"},
 *     @OA\Property(property="id", type="integer", description="ID заказа"),
 *     @OA\Property(property="user_id", type="integer", description="ID пользователя"),
 *     @OA\Property(property="total_price", type="number", format="float", description="Общая стоимость заказа"),
 *     @OA\Property(property="status", type="string", description="Статус заказа", example="pending"),
 *     @OA\Property(
 *         property="products",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Product")
 *     ),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User"
 *     )
 * )
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_price', 'status'];

    /**
     * Получить пользователя, оформившего заказ.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить список продуктов в заказе.
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
