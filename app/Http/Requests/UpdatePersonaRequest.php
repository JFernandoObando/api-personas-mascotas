<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
class UpdatePersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('personas', 'email')->ignore($this->route('persona')->id),
            ],
            'fecha_nacimiento' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'           => 'El nombre es obligatorio.',
            'nombre.string'             => 'El nombre debe ser una cadena de texto.',
            'nombre.max'                => 'El nombre no debe superar los 255 caracteres.',

            'email.required'            => 'El correo electrónico es obligatorio.',
            'email.email'               => 'Debes ingresar un correo electrónico válido.',
            'email.unique'              => 'Ya existe una persona registrada con este correo electrónico.',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date'     => 'La fecha de nacimiento debe ser una fecha válida.',
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
