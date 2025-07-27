<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @OA\Schema(
 *     schema="Persona",
 *     type="object",
 *     title="Persona",
 *     required={"nombre", "email", "fecha_nacimiento", "user_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *     @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
 *     @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1990-05-15"),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true)
 * )
 */

class Persona extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'email',
        'fecha_nacimiento',
        'user_id'
    ];

    public function mascotas()
    {
        return $this->hasMany(Mascota::class);
    }
    public function user()
{
    return $this->belongsTo(User::class);
}
}

