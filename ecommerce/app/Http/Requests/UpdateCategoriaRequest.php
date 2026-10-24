<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateCategoriaRequest extends StoreCategoriaRequest
{
    public function rules(): array
    {
        $categoria = $this->route('categoria');

        return [
            'nombre' => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('categorias', 'nombre')->ignore($categoria),
            ],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
