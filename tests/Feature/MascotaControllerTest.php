<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Mascota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Persona;

class MascotaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuario y generar token
        $this->user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('admin1234'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'admin1234',
        ]);

        $this->token = $response->json('token');
    }

    public function test_puede_listar_mascotas()
    {
        Mascota::factory()->count(6)->create();

        $response = $this->withToken($this->token)->getJson('/api/mascotas');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'data' => [['id', 'nombre', 'especie']],
                     'meta' => ['total', 'current_page', 'last_page']
                 ]);
    }

    public function test_puede_crear_mascota()
    {
        $persona = \App\Models\Persona::factory()->create([
            'user_id' => $this->user->id,
        ]);
    
        $data = [
            'nombre' => 'Firulais',
            'especie' => 'Perro',
            'raza' => 'Labrador',
            'edad' => 3,
            'imagen_url' => 'https://example.com/firulais.jpg',
            'temperamento' => 'Amigable',
            'anios_vida' => 12,
            'descripcion' => 'Es muy juguetón.',
            'persona_id' => $persona->id
        ];
    
        $response = $this->withToken($this->token)->postJson('/api/mascotas', $data);
    
        $response->assertStatus(201)
                 ->assertJsonFragment(['nombre' => 'Firulais']);
    }
    

    public function test_puede_mostrar_mascota()
    {
        $mascota = Mascota::factory()->create();

        $response = $this->withToken($this->token)->getJson("/api/mascotas/{$mascota->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => $mascota->nombre]);
    }

    public function test_puede_actualizar_mascota()
    {
        $mascota = Mascota::factory()->create();

        $updateData = ['nombre' => 'NuevoNombre'];

        $response = $this->withToken($this->token)->putJson("/api/mascotas/{$mascota->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'NuevoNombre']);
    }

    public function test_puede_eliminar_mascota()
    {
        $mascota = Mascota::factory()->create();

        $response = $this->withToken($this->token)->deleteJson("/api/mascotas/{$mascota->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => $mascota->nombre]);

        $this->assertSoftDeleted('mascotas', ['id' => $mascota->id]);
    }
}
