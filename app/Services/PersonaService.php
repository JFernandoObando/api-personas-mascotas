<?php

namespace App\Services;

use App\Models\Persona;
use App\Repositories\PersonaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PersonaService
{
    public function __construct(
        protected PersonaRepositoryInterface $repository
    ) {}

    public function list(int $perPage = 5): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): Persona
    {
        $data['user_id'] = auth()->id();
        return $this->repository->create($data);
    }

    public function update(Persona $persona, array $data): Persona
    {
        return $this->repository->update($persona, $data);
    }

    public function delete(Persona $persona): void
    {
        $this->repository->delete($persona);
    }

    public function find(int $id): Persona
    {
        return $this->repository->find($id);
    }

    public function findWithMascotas(int $id): Persona
{
    return $this->repository->find($id)->load('mascotas');
}
}
