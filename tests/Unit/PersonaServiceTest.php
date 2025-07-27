<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\PersonaService;
use App\Repositories\PersonaRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class PersonaServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @var \Mockery\MockInterface|\App\Repositories\PersonaRepositoryInterface */
    
    protected $mockRepository;

    protected PersonaService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockRepository = Mockery::mock(PersonaRepositoryInterface::class);
        $this->service = new PersonaService($this->mockRepository);
    }

    public function test_list_personas_paginadas()
    {
        $paginator = new LengthAwarePaginator([], 0, 5);

        $this->mockRepository->shouldReceive('paginate')
            ->once()
            ->with(5)
            ->andReturn($paginator);

        $result = $this->service->list();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_create_persona_agrega_user_id()
    {
        $user = User::factory()->create();
        $this->actingAs($user); 

        $data = [
            'nombre' => 'Ana',
            'email' => 'ana@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ];

        $expected = array_merge($data, ['user_id' => $user->id]);

        $this->mockRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(fn ($arg) => $arg['user_id'] === $user->id))
            ->andReturn(Mockery::mock(Persona::class)->makePartial($expected));

        $persona = $this->service->create($data);

        $this->assertInstanceOf(Persona::class, $persona);
    }

    public function test_update_persona()
    {
        $persona = Mockery::mock(Persona::class)->makePartial(['nombre' => 'Old']);
        $data = ['nombre' => 'New'];

        $this->mockRepository->shouldReceive('update')
            ->once()
            ->with($persona, $data)
            ->andReturn((new Persona())->forceFill(['nombre' => 'New']));


        $result = $this->service->update($persona, $data);

        $this->assertEquals('New', $result->nombre);
    }

    public function test_delete_persona()
    {
        $persona = Mockery::mock(Persona::class)->makePartial();

        $this->mockRepository->shouldReceive('delete')
            ->once()
            ->with($persona)
            ->andReturnNull();

        $this->service->delete($persona);

        $this->assertTrue(true);
    }

    public function test_find_persona_por_id()
    {
        $persona = (new Persona())->forceFill(['nombre' => 'Carlos']);
    
        $this->mockRepository->shouldReceive('find')
            ->once()
            ->with(15)
            ->andReturn($persona);
    
        $result = $this->service->find(15);
    
        $this->assertInstanceOf(Persona::class, $result);
        $this->assertEquals('Carlos', $result->nombre);
    }

    public function test_find_with_mascotas()
    {
        $persona = Mockery::mock(Persona::class)->makePartial();

        $persona->shouldReceive('load')
            ->once()
            ->with('mascotas')
            ->andReturnSelf();

        $this->mockRepository->shouldReceive('find')
            ->once()
            ->with(20)
            ->andReturn($persona);

        $result = $this->service->findWithMascotas(20);

        $this->assertInstanceOf(Persona::class, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
