@extends('layout')

@section('panel_izquierdo')
  <ul class="navbar-nav ml-lg-5">
    <li class="nav-item">
      <a class="nav-link" href="home">Inicio</a>
    </li>
    <li>
      <a class="nav-link" href="buscadorInstituciones">Instituciones</a>
    </li>

    @foreach ($modulos as $modulo)
      @includeIf("modulosBarraNavegacion." . $modulo)
    @endforeach

  </ul>
@endsection

@section('panel_central')
  @if (! empty($modulosAdministracion))
    @includeIf("modulosBarraNavegacion.dropdownAdministracion")
  @endif
@endsection

@section('panel_derecho')
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_username" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ ucfirst(strtolower($username)) }}
      </a>
      <div class="dropdown-menu dropdown-menu-right mt-2" aria-labelledby="navbarDropdown_username">
        <a class="dropdown-item" href="cerrarSesion">Cerrar sesión</a>
      </div>
    </li>
  </ul>
@endsection
