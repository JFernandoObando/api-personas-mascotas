<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\PersonaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'verify.token.match'])->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::apiResource('personas', PersonaController::class);
    Route::apiResource('mascotas', MascotaController::class);
    Route::get('personas/{id}/mascotas', [PersonaController::class, 'mascotasDePersona']);
});