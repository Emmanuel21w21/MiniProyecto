<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodigoVerificacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => ['required', 'digits:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'Ingresa el código de verificación.',
            'codigo.digits' => 'El código debe contener 6 dígitos.',
        ];
    }

    public function attributes(): array
    {
        return [
            'codigo' => 'código de verificación',
        ];
    }
}
