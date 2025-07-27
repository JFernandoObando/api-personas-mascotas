<?php

namespace App\Providers;

use App\Repositories\MascotaRepository;
use App\Repositories\MascotaRepositoryInterface;
use App\Repositories\PersonaRepository;
use App\Repositories\PersonaRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PersonaRepositoryInterface::class, PersonaRepository::class);
        $this->app->bind(MascotaRepositoryInterface::class, MascotaRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
