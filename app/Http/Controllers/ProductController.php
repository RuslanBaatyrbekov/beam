<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    /**
     * Показать список всех товаров.
     *
     * @OA\Get(
     *     path="/products",
     *     summary="Список товаров",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Список товаров",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Создать новый товар.
     *
     * @OA\Post(
     *     path="/products",
     *     summary="Создать новый товар",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price", "stock"},
     *             @OA\Property(property="name", type="string", description="Название товара"),
     *             @OA\Property(property="price", type="number", format="float", description="Цена товара"),
     *             @OA\Property(property="stock", type="integer", description="Количество товара на складе")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Товар создан",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer'
        ]);
        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    /**
     * Показать конкретный товар.
     *
     * @OA\Get(
     *     path="/products/{id}",
     *     summary="Получить конкретный товар",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID товара",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Детали товара",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Товар не найден"
     *     )
     * )
     */
    public function show(string $id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Обновить информацию о товаре.
     *
     * @OA\Put(
     *     path="/products/{id}",
     *     summary="Обновить товар",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID товара",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Товар обновлен",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Товар не найден"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json($product, 200);
    }

    /**
     * Удалить товар.
     *
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Удалить товар",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID товара",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Товар удален"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Товар не найден"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }
}
