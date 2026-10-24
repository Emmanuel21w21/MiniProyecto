{{-- Formulario compartido por "crear" y "editar". Variables: $roles, $usuario (opcional) --}}
@php $esEdicion = isset($usuario); @endphp

<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre ?? '') }}"
           class="form-control @error('nombre') is-invalid @enderror" required>
    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="apellidos" class="form-label">Apellidos</label>
    <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $usuario->apellidos ?? '') }}"
           class="form-control @error('apellidos') is-invalid @enderror" required>
    @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="correo" class="form-label">Correo electrónico</label>
    <input type="email" id="correo" name="correo" value="{{ old('correo', $usuario->correo ?? '') }}"
           class="form-control @error('correo') is-invalid @enderror" required>
    @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
    <label for="clave" class="form-label">Contraseña @if($esEdicion)<small class="text-muted">(déjala vacía para no cambiarla)</small>@endif</label>
    <input type="password" id="clave" name="clave"
           class="form-control @error('clave') is-invalid @enderror" @unless($esEdicion) required @endunless>
    <div class="form-text">Mínimo 8 caracteres, con al menos una letra y un número.</div>
    @error('clave') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label for="clave_confirmation" class="form-label">Confirmar contraseña</label>
    <input type="password" id="clave_confirmation" name="clave_confirmation" class="form-control"
           @unless($esEdicion) required @endunless>
</div>
