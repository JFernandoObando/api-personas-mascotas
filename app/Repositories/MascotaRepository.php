<?php

namespace App\Repositories;

use App\Models\Mascota;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MascotaRepository implements MascotaRepositoryInterface
{
    public function paginate(int $perPage = 5): LengthAwarePaginator
    {
        return Mascota::latest()->paginate($perPage);
    }

    public function find(int $id): Mascota
    {
        return Mascota::findOrFail($id);
    }

    public function create(array $data): Mascota
    {
        return Mascota::create($data);
    }

    public function update(Mascota $mascota, array $data): Mascota
    {
        $mascota->update($data);
        return $mascota;
    }

    public function delete(Mascota $mascota): void
    {
        $mascota->delete();
    }
}
