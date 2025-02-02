<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     required={"id", "name", "price", "stock"},
 *     @OA\Property(property="id", type="integer", description="ID товара"),
 *     @OA\Property(property="name", type="string", description="Название товара"),
 *     @OA\Property(property="price", type="number", format="float", description="Цена товара"),
 *     @OA\Property(property="stock", type="integer", description="Количество товара на складе"),
 *     @OA\Property(
 *         property="orders",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Order")
 *     )
 * )
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'stock'];


    /**
     * Получить заказы, содержащие этот товар.
     *
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price')->withTimestamps();
    }
}
