<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Http\Requests\StoreMascotaRequest;
use App\Http\Requests\UpdateMascotaRequest;
use App\Http\Resources\MascotaResource;
use App\Services\MascotaService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Mascotas",
 *     description="Operaciones relacionadas con mascotas"
 * )
 */
class MascotaController extends Controller
{
    public function __construct(
        protected MascotaService $mascotaService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/mascotas",
     *     summary="Listar mascotas paginadas",
     *     tags={"Mascotas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de mascotas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Listado de mascotas"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Mascota")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="total", type="integer", example=10),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=2)
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $mascotas = $this->mascotaService->list(5);

        return response()->json([
            'message' => 'Listado de mascotas',
            'data' => MascotaResource::collection($mascotas),
            'meta' => [
                'total' => $mascotas->total(),
                'current_page' => $mascotas->currentPage(),
                'last_page' => $mascotas->lastPage()
            ]
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/mascotas",
     *     summary="Crear una nueva mascota",
     *     tags={"Mascotas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "especie", "persona_id"},
     *             @OA\Property(property="nombre", type="string", example="Firulais", maxLength=100),
     *             @OA\Property(property="especie", type="string", example="Perro", maxLength=50),
     *             @OA\Property(property="raza", type="string", example="Labrador", nullable=true, maxLength=100),
     *             @OA\Property(property="edad", type="integer", example=3, nullable=true, minimum=0),
     *             @OA\Property(property="imagen_url", type="string", example="https://example.com/firulais.jpg"),
     *             @OA\Property(property="temperamento", type="string", example="Tranquilo"),
     *             @OA\Property(property="anios_vida", type="integer", example=12),
     *             @OA\Property(property="descripcion", type="string", example="Mascota activa y amigable."),
     *             @OA\Property(property="persona_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Mascota creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Mascota creada correctamente"),
     *             @OA\Property(property="mascota", ref="#/components/schemas/Mascota")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(StoreMascotaRequest $request): JsonResponse
    {
        $mascota = $this->mascotaService->create($request->validated());

        return response()->json([
            'message' => 'Mascota creada correctamente',
            'mascota' => new MascotaResource($mascota)
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/mascotas/{id}",
     *     summary="Mostrar detalle de una mascota",
     *     tags={"Mascotas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la mascota",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle de la mascota",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Detalle de la mascota"),
     *             @OA\Property(property="mascota", ref="#/components/schemas/Mascota")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mascota no encontrada"
     *     )
     * )
     */
    public function show(Mascota $mascota): JsonResponse
    {
        return response()->json([
            'message' => 'Detalle de la mascota',
            'mascota' => new MascotaResource($mascota)
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/mascotas/{id}",
     *     summary="Actualizar una mascota",
     *     tags={"Mascotas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la mascota",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Max"),
     *             @OA\Property(property="especie", type="string", example="Gato"),
     *             @OA\Property(property="raza", type="string", example="Siames"),
     *             @OA\Property(property="edad", type="integer", example=2),
     *             @OA\Property(property="imagen_url", type="string", example="https://example.com/max.jpg"),
     *             @OA\Property(property="temperamento", type="string", example="Tímido"),
     *             @OA\Property(property="anios_vida", type="integer", example=15),
     *             @OA\Property(property="descripcion", type="string", example="Muy tranquilo."),
     *             @OA\Property(property="persona_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mascota actualizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Mascota actualizada correctamente"),
     *             @OA\Property(property="mascota", ref="#/components/schemas/Mascota")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mascota no encontrada"
     *     )
     * )
     */
    public function update(UpdateMascotaRequest $request, Mascota $mascota): JsonResponse
    {
        $mascota = $this->mascotaService->update($mascota, $request->validated());

        return response()->json([
            'message' => 'Mascota actualizada correctamente',
            'mascota' => new MascotaResource($mascota)
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/mascotas/{id}",
     *     summary="Eliminar (soft delete) una mascota",
     *     tags={"Mascotas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la mascota",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mascota eliminada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Mascota eliminada correctamente (soft delete)"),
     *             @OA\Property(property="mascota", type="object",
     *                 @OA\Property(property="nombre", type="string", example="Firulais"),
     *                 @OA\Property(property="especie", type="string", example="Perro")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mascota no encontrada"
     *     )
     * )
     */
    public function destroy(Mascota $mascota): JsonResponse
    {
        $this->mascotaService->delete($mascota);

        return response()->json([
            'message' => 'Mascota eliminada correctamente (soft delete)',
            'mascota' => [
                'nombre' => $mascota->nombre,
                'especie' => $mascota->especie,
            ]
        ], 200);
    }
}
