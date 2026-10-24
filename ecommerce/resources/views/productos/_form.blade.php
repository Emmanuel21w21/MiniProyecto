<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
           value="{{ old('nombre', $producto->nombre ?? '') }}" required>
    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea id="descripcion" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" required>{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="precio" class="form-label">Precio</label>
        <input id="precio" name="precio" type="number" step="0.01" min="0.01" class="form-control @error('precio') is-invalid @enderror"
               value="{{ old('precio', $producto->precio ?? '') }}" required>
        @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="existencia" class="form-label">Existencia</label>
        <input id="existencia" name="existencia" type="number" min="0" class="form-control @error('existencia') is-invalid @enderror"
               value="{{ old('existencia', $producto->existencia ?? 0) }}" required>
        @error('existencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<fieldset class="mb-3">
    <legend class="form-label">Categorías</legend>
    @foreach($categorias as $categoria)
        <label class="form-check">
            <input class="form-check-input" type="checkbox" name="categorias[]" value="{{ $categoria->id }}"
                   @checked(in_array($categoria->id, old('categorias', isset($producto) ? $producto->categorias->modelKeys() : [])))>
            {{ $categoria->nombre }}
        </label>
    @endforeach
</fieldset>
