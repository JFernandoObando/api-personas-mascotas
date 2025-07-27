<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MascotaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'especie' => $this->especie,
            'raza' => $this->raza,
            'edad' => $this->edad,
            'persona_id' => $this->persona_id,
            'imagen_url' => $this->imagen_url,
            'temperamento' => $this->temperamento,
            'anios_vida' => $this->anios_vida,
            'descripcion' => $this->descripcion,
        ];
    }
    
}
