<?php
namespace App\Repositories;

use App\Models\Persona;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PersonaRepository implements PersonaRepositoryInterface
{
    public function paginate(int $perPage = 5): LengthAwarePaginator
    {
        return Persona::latest()->paginate($perPage);
    }

    public function create(array $data): Persona
    {
        return Persona::create($data);
    }

    public function update(Persona $persona, array $data): Persona
    {
        $persona->update($data);
        return $persona;
    }

    public function delete(Persona $persona): void
    {
        $persona->email = $persona->email . '_deleted';
        $persona->save();
        $persona->delete();
    }

    public function find(int $id): Persona
    {
        return Persona::findOrFail($id);
    }

    public function findWithMascotas(int $id): Persona
    {
        return Persona::with('mascotas')->findOrFail($id);
    }
}
