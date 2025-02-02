<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Order API",
 *     version="1.0.0"
 * )
 *
 * @OA\Server(
 *     url="http://localhost/api",
 *     description="Local API Server"
 * )
 */
class OrderController extends Controller
{
    /**
     * Получение списка заказов.
     *
     * @OA\Get(
     *     path="/orders",
     *     summary="Список всех заказов",
     *     tags={"Orders"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Order"))
     *     )
     * )
     */
    public function index()
    {
        return Order::all();
    }

    /**
     * Создание нового заказа.
     *
     * @OA\Post(
     *     path="/orders",
     *     summary="Создать заказ",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Заказ создан",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $order = Order::create($request->all());
        return response()->json($order, 201);
    }

    /**
     * Получение конкретного заказа.
     *
     * @OA\Get(
     *     path="/orders/{id}",
     *     summary="Получить заказ по ID",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID заказа",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация о заказе",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Заказ не найден"
     *     )
     * )
     */
    public function show(string $id)
    {
        return Order::findOrFail($id);
    }

    /**
     * Обновление заказа.
     *
     * @OA\Put(
     *     path="/orders/{id}",
     *     summary="Обновить заказ",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID заказа",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Заказ обновлен",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->all());
        return response()->json($order, 200);
    }

    /**
     * Удаление заказа.
     *
     * @OA\Delete(
     *     path="/orders/{id}",
     *     summary="Удалить заказ",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID заказа",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Заказ удален"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(null, 204);
    }
}
