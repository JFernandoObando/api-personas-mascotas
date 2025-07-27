<?php

namespace App\Repositories;

use App\Models\Mascota;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MascotaRepositoryInterface
{
    public function paginate(int $perPage = 5): LengthAwarePaginator;
    public function find(int $id): ?Mascota;
    public function create(array $data): Mascota;
    public function update(Mascota $mascota, array $data): Mascota;
    public function delete(Mascota $mascota): void;
}
