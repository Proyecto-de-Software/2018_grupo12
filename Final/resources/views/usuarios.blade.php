@extends('layoutLogueado')

@section('titulo')
  Administración - Usuarios
@endsection

@section('cuerpo')
  <div class="container my-3">
    <ul class="nav nav-tabs" id="menuTabs" role="tablist">
      @foreach ($permisos as $permiso)
        @includeIf('moduloUsuario.tab_' . $permiso)
      @endforeach
    </ul>
    <div class="tab-content" id="myTabContent">
      @foreach ($permisos as $permiso)
        @includeIf('moduloUsuario.cuerpo_' . $permiso)
      @endforeach
    </div>
  </div>

  <!-- Modal Mensaje Confirmacion -->
  <div class="modal fade" id="mensajeConfirmacion" tabindex="-1" role="dialog" aria-labelledby="tituloMensaje" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title ml-auto" id="tituloMensaje">titulo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Boton para cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="cuerpoMensaje" class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
          <button id="botonMensaje" type="button" class="btn btn-outline-success" data-dismiss="modal">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Administracion Roles -->
  <div class="modal fade" id="panelAdministracionRoles" tabindex="-1" role="dialog" aria-labelledby="tituloPanelAdministracionRoles" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title ml-auto" id="tituloPanelAdministracionRoles">Administrar Roles</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Boton para cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="cuerpoPanelAdministracionRoles" class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="js/usuarios.js"></script>
@endpush
