<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Mascota",
 *     type="object",
 *     title="Mascota",
 *     required={"nombre", "especie", "persona_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Firulais"),
 *     @OA\Property(property="especie", type="string", example="Perro"),
 *     @OA\Property(property="raza", type="string", example="Labrador"),
 *     @OA\Property(property="edad", type="integer", example=3),
 *     @OA\Property(property="imagen_url", type="string", example="https://example.com/firulais.jpg"),
 *     @OA\Property(property="temperamento", type="string", example="Amigable"),
 *     @OA\Property(property="descripcion", type="string", example="Muy juguetón y amigable"),
 *     @OA\Property(property="anios_vida", type="integer", example=12),
 *     @OA\Property(property="persona_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true)
 * )
 */
class Mascota extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'edad',
        'persona_id',
        'imagen_url',
        'temperamento',
        'descripcion',
        'anios_vida',
    ];
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
