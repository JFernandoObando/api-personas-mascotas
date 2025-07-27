<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\Mascota;
use App\Services\DogApiService;
use Faker\Factory as Faker;

class MascotaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $razas = ['Labrador', 'Beagle', 'Poodle', 'Bulldog'];
        $dogApi = new DogApiService(); // sin inyección, instanciado manualmente

        foreach ($razas as $raza) {
            $persona = Persona::inRandomOrder()->first();

            $info = $dogApi->buscarRaza($raza);
            $imagen = $info && isset($info['id']) ? $dogApi->obtenerImagenPorBreedId($info['id']) : null;

            Mascota::create([
                'nombre'        => $faker->firstName(),
                'especie'       => 'Perro',
                'raza'          => $raza,
                'edad'          => rand(1, 10),
                'persona_id'    => $persona->id,
                'descripcion'   => $info['bred_for'] ?? null,
                'anios_vida'    => $info['life_span'] ?? null,
                'temperamento'  => $info['temperament'] ?? null,
                'imagen_url'    => $imagen,
            ]);
        }
    }
}
