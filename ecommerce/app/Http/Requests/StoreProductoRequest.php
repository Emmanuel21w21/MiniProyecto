<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:2', 'max:255'],
            'descripcion' => ['required', 'string', 'max:5000'],
            'precio' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'existencia' => ['required', 'integer', 'min:0', 'max:1000000'],
            'categorias' => ['nullable', 'array'],
            'categorias.*' => ['integer', Rule::exists('categorias', 'id')],
        ];
    }
}
