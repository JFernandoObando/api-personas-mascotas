<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DogApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.dogapi.base_url');
    }

    public function buscarRaza(string $nombreRaza): ?array
    {
        $response = Http::get("{$this->baseUrl}/breeds/search", [
            'q' => $nombreRaza
        ]);

        return $response->json()[0] ?? null;
    }

    public function obtenerImagenPorBreedId($breedId): ?string
    {
        $response = Http::get("{$this->baseUrl}/images/search", [
            'breed_id' => $breedId
        ]);

        return $response->json()[0]['url'] ?? null;
    }
}

