<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuario administrador para login
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('admin1234'),
        ]);

        // Hacer login y capturar el token
        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'admin1234',
        ]);
        
        // Obtener el token del cuerpo JSON
        $this->token = $response->json('token');
    }


    public function test_puede_crear_una_persona()
    {
        $payload = [
            'nombre' => 'María',
            'email' => 'maria@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
                         ->postJson('/api/personas', $payload);

                         $response->assertStatus(201);
                         $this->assertEquals('María', $response->json('persona')['nombre']);
    }

    public function test_puede_ver_detalle_de_persona()
    {
        $user = User::first();
        $persona = Persona::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
                         ->getJson("/api/personas/{$persona->id}");


                         $response->assertStatus(200);
                         $this->assertEquals($persona->nombre, $response->json('persona.nombre'));
    }


    public function test_puede_eliminar_una_persona()
    {
        $user = User::first();
        $persona = Persona::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer {$user->jwt_token}")
                         ->deleteJson("/api/personas/{$persona->id}");
                         $response->assertStatus(200);
                         $this->assertEquals($persona->email, $persona->email);

        $this->assertSoftDeleted('personas', ['id' => $persona->id]);
    }

    public function test_puede_ver_persona_con_mascotas()
    {
        $user = User::first();
        $persona = Persona::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
                         ->getJson("/api/personas/{$persona->id}/mascotas");

        $response->assertStatus(200)
                 ->assertJsonStructure(['persona' => ['mascotas']]);
    }
}
