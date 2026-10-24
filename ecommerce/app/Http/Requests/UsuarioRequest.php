<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Validación del CRUD de usuarios (alta y edición hecha por el Gerente).
 */
class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // El acceso ya está restringido por el middleware de rol.
    }

    public function rules(): array
    {
        // En edición la ruta trae el usuario; en alta no.
        $usuario = $this->route('usuario');
        $esEdicion = $usuario !== null;

        return [
            'nombre' => ['required', 'string', 'min:2', 'max:100'],
            'apellidos' => ['required', 'string', 'min:2', 'max:150'],
            'correo' => [
                'required', 'string', 'email:rfc', 'max:255',
                Rule::unique('users', 'correo')->ignore($usuario?->id),
            ],
            'rol' => ['required', Rule::in($this->user()->esAdministrador()
                ? Usuario::ROLES
                : [Usuario::ROL_CLIENTE])],
            'clave' => [
                $esEdicion ? 'nullable' : 'required',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'apellidos' => 'apellidos',
            'correo' => 'correo electrónico',
            'rol' => 'rol',
            'clave' => 'contraseña',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'max' => 'El campo :attribute no puede superar :max caracteres.',
            'correo.email' => 'Ingresa un correo electrónico válido.',
            'correo.unique' => 'Ese correo electrónico ya está registrado.',
            'rol.in' => 'El rol seleccionado no es válido.',
            'confirmed' => 'La confirmación de la contraseña no coincide.',
            'clave.min' => 'La contraseña debe tener al menos :min caracteres.',
            'clave.letters' => 'La contraseña debe incluir al menos una letra.',
            'clave.numbers' => 'La contraseña debe incluir al menos un número.',
        ];
    }
}
