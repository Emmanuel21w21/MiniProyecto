{{-- Formulario compartido por "crear" y "editar". Variables: $roles, $usuario (opcional) --}}
@php $esEdicion = isset($usuario); @endphp

<div class="mb-3">
    <label for="name" class="form-label">Nombre completo</label>
    <input type="text" id="name" name="name" value="{{ old('name', $usuario->name ?? '') }}"
           class="form-control @error('name') is-invalid @enderror" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Correo electrónico</label>
    <input type="email" id="email" name="email" value="{{ old('email', $usuario->email ?? '') }}"
           class="form-control @error('email') is-invalid @enderror" required>
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="rol" class="form-label">Rol</label>
    <select id="rol" name="rol" class="form-select @error('rol') is-invalid @enderror" required>
        <option value="">Selecciona un rol…</option>
        @foreach($roles as $rol)
            <option value="{{ $rol }}" @selected(old('rol', $usuario->rol ?? '') === $rol)>{{ ucfirst($rol) }}</option>
        @endforeach
    </select>
    @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label">Contraseña @if($esEdicion)<small class="text-muted">(déjala vacía para no cambiarla)</small>@endif</label>
    <input type="password" id="password" name="password"
           class="form-control @error('password') is-invalid @enderror" @unless($esEdicion) required @endunless>
    <div class="form-text">Mínimo 8 caracteres, con al menos una letra y un número.</div>
    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
           @unless($esEdicion) required @endunless>
</div>
