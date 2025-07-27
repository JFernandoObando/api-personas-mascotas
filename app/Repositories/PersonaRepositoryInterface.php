<?php

namespace App\Repositories;

use App\Models\Persona;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PersonaRepositoryInterface
{
    public function paginate(int $perPage = 5): LengthAwarePaginator;
    public function create(array $data): Persona;
    public function update(Persona $persona, array $data): Persona;
    public function delete(Persona $persona): void;
    public function find(int $id): Persona;
    public function findWithMascotas(int $id): Persona;
}
