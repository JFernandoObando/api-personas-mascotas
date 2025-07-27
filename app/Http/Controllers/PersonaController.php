<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Http\Resources\PersonaResource;
use App\Http\Resources\PersonaWithMascotasResource;
use App\Models\Persona;
use App\Services\PersonaService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Personas",
 *     description="Operaciones relacionadas con personas"
 * )
 */
class PersonaController extends Controller
{
    public function __construct(
        protected PersonaService $personaService
    ) {
        $this->authorizeResource(Persona::class, 'persona');
    }

    /**
     * @OA\Get(
     *     path="/api/personas",
     *     summary="Listar personas paginadas",
     *     tags={"Personas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de personas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Listado de personas"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Persona")),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $personas = $this->personaService->list(5);

        return response()->json([
            'message' => 'Listado de personas',
            'data'    => PersonaResource::collection($personas),
            'meta'    => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'last_page'    => $personas->lastPage(),
            ],
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/personas",
     *     summary="Crear una nueva persona",
     *     tags={"Personas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "email", "fecha_nacimiento", "user_id"},
     *             @OA\Property(property="nombre", type="string", example="Ana López"),
     *             @OA\Property(property="email", type="string", format="email", example="ana@example.com"),
     *             @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1990-06-21"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Persona creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="persona", ref="#/components/schemas/Persona")
     *         )
     *     )
     * )
     */
    public function store(StorePersonaRequest $request): JsonResponse
    {
        $persona = $this->personaService->create($request->validated());

        return response()->json([
            'message' => 'Persona creada correctamente',
            'persona' => new PersonaResource($persona),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/personas/{id}",
     *     summary="Mostrar una persona por ID",
     *     tags={"Personas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle de la persona",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="persona", ref="#/components/schemas/Persona")
     *         )
     *     )
     * )
     */
    public function show(Persona $persona): JsonResponse
    {
        return response()->json([
            'message' => 'Detalle de la persona',
            'persona' => new PersonaResource($persona),
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/personas/{id}",
     *     summary="Actualizar una persona",
     *     tags={"Personas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Ana López"),
     *             @OA\Property(property="email", type="string", example="ana@example.com"),
     *             @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1990-06-21")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona actualizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="persona", ref="#/components/schemas/Persona")
     *         )
     *     )
     * )
     */
    public function update(UpdatePersonaRequest $request, Persona $persona): JsonResponse
    {
        $persona = $this->personaService->update($persona, $request->validated());

        return response()->json([
            'message' => 'Persona actualizada correctamente',
            'persona' => new PersonaResource($persona),
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/personas/{id}",
     *     summary="Eliminar una persona",
     *     tags={"Personas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona eliminada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Persona eliminada (soft delete)"),
     *             @OA\Property(property="persona", type="object",
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="email", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function destroy(Persona $persona): JsonResponse
    {
        $this->authorize('delete', $persona);

        $this->personaService->delete($persona);

        return response()->json([
            'message' => 'Persona eliminada (soft delete)',
            'persona' => [
                'nombre' => $persona->nombre,
                'email'  => $persona->email,
            ],
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/personas/{id}/mascotas",
     *     summary="Obtener persona junto con sus mascotas",
     *     tags={"Personas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona con sus mascotas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Persona con sus mascotas"),
     *             @OA\Property(property="persona", ref="#/components/schemas/Persona")
     *         )
     *     )
     * )
     */
    public function mascotasDePersona(int $id): JsonResponse
    {
        $persona = $this->personaService->findWithMascotas($id);

        return response()->json([
            'message' => 'Persona con sus mascotas',
            'persona' => new PersonaWithMascotasResource($persona),
        ]);
    }
}
