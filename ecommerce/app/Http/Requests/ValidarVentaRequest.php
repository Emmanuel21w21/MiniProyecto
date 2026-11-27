<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidarVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('validar', $this->route('venta'));
    }

    public function rules(): array
    {
        return [];
    }
}