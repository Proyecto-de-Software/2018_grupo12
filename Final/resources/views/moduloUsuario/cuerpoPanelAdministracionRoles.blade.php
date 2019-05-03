<div class="container">
  <form class="form-inline justify-content-center" onsubmit="return false">
    <div class="form-group mx-sm-3 mb-2">
      <label for="rol" class="sr-only">Seleccionar rol</label>
      <select class="form-control" id="rol" name="rol">
        <option value="no seleccionado" selected>Selectionar Rol...</option>

        @foreach ($roles as $rol)
            <option value="{{ $rol->getId() }}">{{ ucfirst(strtolower($rol->getNombre())) }}</option>
        @endforeach

      </select>
    </div>
    <button type="submit" class="btn btn-primary mb-2" id="btnAgregarRol">Agregar rol</button>
  </form>
  <div class="mt-2">
    <h5 class="textcenter">Roles del usuario: {{ ucfirst(strtolower($usuario->getUsername())) }}</h5>
    <hr>
    <div class="px-5" >

        @forelse ($usuario->getRoles() as $rol)
            <div class="mb-2">
                {{ ucfirst(strtolower($rol->getNombre())) }}
                <button type="button" class="close" name="btnQuitarRol" aria-label="Boton para quitar rol {{ ucfirst(strtolower($rol->getNombre())) }}" id="{{ $rol->getId() }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @empty
            <div class="textcenter">No tiene</div>
        @endforelse

    </div>
  </div>
</div>
