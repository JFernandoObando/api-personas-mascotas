<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\User;
use Faker\Factory as Faker;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Asegurarse de que el usuario admin exista
        $user = User::where('email', 'admin@example.com')->first();

        if (!$user) {
            $this->command->error('⚠️ Usuario admin@example.com no encontrado. Ejecuta primero UserSeeder.');
            return;
        }

        foreach (range(1, 10) as $i) {
            Persona::create([
                'nombre' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'fecha_nacimiento' => $faker->date(),
                'user_id' => $user->id, // ← Asociar al usuario
            ]);
        }

        $this->command->info('✅ 10 personas creadas para admin@example.com');
    }
}
