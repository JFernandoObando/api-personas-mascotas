<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMascotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:100',
            'especie'     => 'required|string|max:50',
            'raza'        => 'nullable|string|max:100',
            'edad'        => 'nullable|integer|min:0',
            'persona_id'  => 'required|exists:personas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'     => 'El nombre de la mascota es obligatorio.',
            'nombre.string'       => 'El nombre debe ser una cadena de texto.',
            'nombre.max'          => 'El nombre no debe superar los 100 caracteres.',

            'especie.required'    => 'La especie es obligatoria.',
            'especie.string'      => 'La especie debe ser una cadena de texto.',
            'especie.max'         => 'La especie no debe superar los 50 caracteres.',

            'raza.string'         => 'La raza debe ser una cadena de texto.',
            'raza.max'            => 'La raza no debe superar los 100 caracteres.',

            'edad.integer'        => 'La edad debe ser un número entero.',
            'edad.min'            => 'La edad no puede ser negativa.',

            'persona_id.required' => 'El ID de la persona es obligatorio.',
            'persona_id.exists'   => 'La persona especificada no existe en la base de datos.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
