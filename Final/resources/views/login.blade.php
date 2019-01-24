@extends('layout')

@section('titulo')
Ingresar
@endsection

@section('buscador')
  <div class="alturabuscador">

  </div>
@endsection

@section('cuerpo')
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-12 col-sm-6 col-lg-4">
        <form onsubmit="return false">
          <div class="form-group">
            <label for="usuario" id="labelUsuario">Usuario</label>
            <input name="usuario" type="text" class="form-control" id="usuario" aria-describedby="labelUsuario" placeholder="Usuario..." required>
          </div>
          <div class="form-group">
            <label for="contrasena" id="labelContrasena">Contraseña</label>
            <input aria-describedby="labelContrasena" name="contrasena" type="password" class="form-control" id="contrasena" placeholder="Contraseña..." required>
          </div>
          <div class="form-row justify-content-center">
            <button type="submit" class="btn btn-primary align-self-center" id="btnAceptar">Aceptar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="js/login.js"></script>
@endpush
