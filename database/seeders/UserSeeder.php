<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin1234'),
        ]);

        // Generar y guardar el token
        $token = JWTAuth::fromUser($user);
        $user->update(['jwt_token' => $token]);

        $this->command->info('✅ Usuario creado con token JWT:');
        $this->command->line("🔑 Token: $token");
    }
}
