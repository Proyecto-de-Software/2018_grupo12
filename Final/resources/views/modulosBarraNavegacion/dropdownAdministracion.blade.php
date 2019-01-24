<ul class="navbar-nav ml-auto">
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_administracion" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Administración
    </a>
    <div class="dropdown-menu mb-2" aria-labelledby="navbarDropdown_administracion">
        @foreach ($modulosAdministracion as $modulo)
            @includeIf("modulosBarraNavegacion." . $modulo)
        @endforeach
    </div>
  </li>
</ul>
