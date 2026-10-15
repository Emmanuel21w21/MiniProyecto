<?php

namespace App\Http\Requests;

use App\Models\User;
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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required', 'string', 'email:rfc', 'max:255',
                Rule::unique('users', 'email')->ignore($usuario?->id),
            ],
            'rol' => ['required', Rule::in(User::ROLES)],
            // En edición la contraseña es opcional: si va vacía, no se cambia.
            'password' => [
                $esEdicion ? 'nullable' : 'required',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'rol' => 'rol',
            'password' => 'contraseña',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'max' => 'El campo :attribute no puede superar :max caracteres.',
            'email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Ese correo electrónico ya está registrado.',
            'rol.in' => 'El rol seleccionado no es válido.',
            'confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.letters' => 'La contraseña debe incluir al menos una letra.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
        ];
    }
}
