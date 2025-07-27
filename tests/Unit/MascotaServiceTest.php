<?php
namespace Tests\Unit;

use App\Models\Mascota;
use App\Models\Persona;
use App\Repositories\MascotaRepositoryInterface;
use App\Services\DogApiService;
use App\Services\MascotaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class MascotaServiceTest extends TestCase
{
    use RefreshDatabase;
/** @var \Mockery\MockInterface|\App\Repositories\MascotaRepositoryInterface */
    protected $mockRepository;
    //protected MascotaRepositoryInterface $mockRepository;
/** @var \Mockery\MockInterface|\App\Services\DogApiService */
    protected $mockDogApi;
    protected MascotaService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockRepository = Mockery::mock(MascotaRepositoryInterface::class);
        $this->mockDogApi     = Mockery::mock(DogApiService::class);
        $this->service        = new MascotaService($this->mockRepository, $this->mockDogApi);
    }

    public function test_list_mascotas_paginadas()
    {
        $paginator = new LengthAwarePaginator([], 0, 5);

        $this->mockRepository->shouldReceive('paginate')
            ->once()
            ->with(5)
            ->andReturn($paginator);

        $result = $this->service->list();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_find_mascota_por_id()
    {
        $mascota = new Mascota(['nombre' => 'Toby']);

        $this->mockRepository->shouldReceive('find')
            ->once()
            ->with(10)
            ->andReturn($mascota);

        $result = $this->service->find(10);

        $this->assertInstanceOf(Mascota::class, $result);
        $this->assertEquals('Toby', $result->nombre);
    }

    public function test_create_mascota_con_dog_api()
    {
        $persona = Persona::factory()->create();

        $data = [
            'nombre'     => 'Max',
            'especie'    => 'Perro',
            'raza'       => 'Labrador',
            'edad'       => 3,
            'persona_id' => $persona->id,
        ];

        $infoRaza = [
            'id'          => 9,
            'bred_for'    => 'Hunting',
            'life_span'   => '10 - 12 years',
            'temperament' => 'Gentle, Intelligent',
        ];
        $this->mockDogApi->shouldReceive('buscarRaza')
            ->once()
            ->with('Labrador')
            ->andReturn($infoRaza);

        $this->mockDogApi->shouldReceive('obtenerImagenPorBreedId')
            ->once()
            ->with(9)
            ->andReturn('http://imagen.com/labrador.jpg');

        $this->mockRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($persona) {
                return $arg['descripcion'] === 'Hunting' &&
                $arg['anios_vida'] === '10 - 12 years' &&
                $arg['temperamento'] === 'Gentle, Intelligent' &&
                $arg['imagen_url'] === 'http://imagen.com/labrador.jpg' &&
                $arg['persona_id'] === $persona->id;
            }))
            ->andReturn(new Mascota($data));

        $mascota = $this->service->create($data);

        $this->assertInstanceOf(Mascota::class, $mascota);
        $this->assertEquals('Max', $mascota->nombre);
    }

    public function test_update_mascota()
    {
        $mascota = new Mascota(['nombre' => 'Old']);
        $data    = ['nombre' => 'New'];

        $this->mockRepository->shouldReceive('update')
            ->once()
            ->with($mascota, $data)
            ->andReturn(new Mascota($data));

        $result = $this->service->update($mascota, $data);

        $this->assertEquals('New', $result->nombre);
    }

    public function test_delete_mascota()
    {
        $mascota = new Mascota();

        $this->mockRepository->shouldReceive('delete')
            ->once()
            ->with($mascota)
            ->andReturn(true);

        $this->service->delete($mascota);

        // Si no lanza error, se considera exitosa
        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
