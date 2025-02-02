<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Profiles",
 *     description="Операции, связанные с профилями пользователей"
 * )
 */
class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/profiles",
     *     summary="Получить список профилей",
     *     tags={"Profiles"},
     *     @OA\Response(
     *         response=200,
     *         description="Список профилей",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Profile")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Profile::all();
    }

    /**
     * @OA\Post(
     *     path="/profiles",
     *     summary="Создать новый профиль",
     *     tags={"Profiles"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Profile")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Профиль успешно создан",
     *         @OA\JsonContent(ref="#/components/schemas/Profile")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $profile = Profile::create($request->all());
        return response()->json($profile, 201);
    }

    /**
     * @OA\Get(
     *     path="/profiles/{id}",
     *     summary="Получить конкретный профиль по ID",
     *     tags={"Profiles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID профиля для получения",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Детали профиля",
     *         @OA\JsonContent(ref="#/components/schemas/Profile")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Профиль не найден"
     *     )
     * )
     */
    public function show(string $id)
    {
        return Profile::findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/profiles/{id}",
     *     summary="Обновить конкретный профиль по ID",
     *     tags={"Profiles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID профиля для обновления",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Profile")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Профиль успешно обновлен",
     *         @OA\JsonContent(ref="#/components/schemas/Profile")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Профиль не найден"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $profile = Profile::findOrFail($id);
        $profile->update($request->all());
        return response()->json($profile, 200);
    }

    /**
     * @OA\Delete(
     *     path="/profiles/{id}",
     *     summary="Удалить конкретный профиль по ID",
     *     tags={"Profiles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID профиля для удаления",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Профиль успешно удален"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Профиль не найден"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();
        return response()->json(null, 204);
    }
}
