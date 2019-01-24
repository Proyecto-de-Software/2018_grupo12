<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="css/prophet.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>@yield('titulo') | {{ $tituloPag }}</title>
  </head>
  <body>
    <div class="navbar navbar-expand-lg navbar-light colorfondo" >
      <a href="./"><img class="redimensionarImg" src="img/logo.png" alt="Logo de la pagina"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <nav class="barraNavegacion">

          @section('panel_izquierdo')
          <p class="mb-0 ml-lg-4"> {{ $tituloPag }}</p>
          <ul class="navbar-nav ml-lg-3">
            <li class="nav-item">
              <a class="nav-link" href="buscadorInstituciones">Instituciones</a>
            </li>
          </ul>
          @show

          @yield('panel_central')

        </nav>

        @section('buscador')
        <div class="ml-auto">

        </div>
        @show

        @yield('panel_derecho')

      </div>
    </div>

    <main>

      @section('cuerpo')
      <div class="container">

      </div>
      @show

      <ul class="prophet bottomclass" ></ul>
    </main>
    <footer class="pieDePagina align-bottom">
      <div class="container-flex pl-3 pr-3 colorfondo" >
        <span>Proyecto de Software 2018 - {{ $tituloPag }}</span>
        <span class="d-block float-sm-right">v 0.0.1</span>
      </div>
    </footer>
    <script src="js/prophet-min.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>

    @stack('scripts')

  </body>
</html>
