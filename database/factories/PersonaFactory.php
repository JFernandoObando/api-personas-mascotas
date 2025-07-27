<?php

namespace Database\Factories;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
class PersonaFactory extends Factory
{
    protected $model = Persona::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'nombre'            => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'fecha_nacimiento'  => $this->faker->date(),
            'user_id'           => User::inRandomOrder()->value('id') ?? User::factory(),
        ];
    }
}
