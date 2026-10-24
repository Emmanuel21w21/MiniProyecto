<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Validation\Rule;

class UpdateVentaRequest extends StoreVentaRequest
{
    public function rules(): array
    {
        return [
            'producto_id' => ['required', 'integer', Rule::exists('productos', 'id')],
            'cliente_id' => [
                'required', 'integer',
                Rule::exists('users', 'id')->where('rol', Usuario::ROL_CLIENTE),
            ],
            'fecha' => ['required', 'date'],
            'total' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
        ];
    }
}
