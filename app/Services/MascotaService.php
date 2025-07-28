<?php
namespace App\Services;

use App\Models\Mascota;
use App\Models\Persona;
use App\Repositories\MascotaRepositoryInterface;
use App\Services\DogApiService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class MascotaService
{
    public function __construct(
        protected MascotaRepositoryInterface $repository,
        protected DogApiService $dogApiService
    ) {}

    public function list(int $perPage = 5): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function find(int $id): ?Mascota
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Mascota|JsonResponse
    {
        $persona = Persona::withTrashed()->find($data['persona_id']);
        
        if (!$persona || $persona->trashed()) {
            return response()->json([
                'message' => 'No se puede asociar una mascota a una persona eliminada.',
                'status_code' => 422
            ], 422);
        }
    
        if (strtolower($data['especie']) === 'perro') {
            $info = $this->dogApiService->buscarRaza($data['raza']);
    
            if ($info) {
                $data['descripcion']  = $info['bred_for'] ?? null;
                $data['anios_vida']   = $info['life_span'] ?? null;
                $data['temperamento'] = $info['temperament'] ?? null;
                $data['imagen_url']   = $this->dogApiService->obtenerImagenPorBreedId($info['id']);
            }
        }
    
        return $this->repository->create($data);
    }
    

    public function update(Mascota $mascota, array $data): Mascota
    {
        return $this->repository->update($mascota, $data);
    }

    public function delete(Mascota $mascota): void
    {
        $this->repository->delete($mascota);
    }
}
