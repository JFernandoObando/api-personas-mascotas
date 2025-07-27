<?php

namespace Database\Factories;

use App\Models\Mascota;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

class MascotaFactory extends Factory
{

    protected $model = Mascota::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'nombre'         => $this->faker->firstName(),
            'especie'        => 'Perro',
            'raza'           => $this->faker->randomElement(['Labrador', 'Beagle', 'Poodle', 'Bulldog']),
            'edad'           => $this->faker->numberBetween(1, 15),
            'persona_id'     => Persona::query()->inRandomOrder()->value('id') ?? Persona::factory(),
            'imagen_url'     => $this->faker->imageUrl(300, 300, 'animals'),
            'temperamento'   => $this->faker->randomElement(['Alegre', 'Tranquilo', 'Activo']),
            'anios_vida'     => $this->faker->numberBetween(8, 15) . ' años',
            'descripcion'    => $this->faker->sentence(),
        ];
    }
}
