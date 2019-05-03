<div class="row justify-content-center mt-2">
  <div class="col-10">
    <form id="formularioModificarUsuario" onsubmit="return false">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="nombreModificacion">Nombre</label>
          <input type="text" class="form-control" id="nombreModificacion" name="nombre" placeholder="Nombre" value="{{ ucfirst(strtolower($usuario->getFirst_name())) }}" required>
        </div>
        <div class="form-group col-md-6">
          <label for="apellidoModificacion">Apellido</label>
          <input type="text" class="form-control" id="apellidoModificacion" name="apellido" placeholder="Apellido" value="{{ ucfirst(strtolower($usuario->getLast_name())) }}" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Nombre de Usuario</label>
          <input type="text" class="form-control" placeholder="Nombre de usuario" value="{{ $usuario->getUsername() }}" readonly>
        </div>
        <div class="form-group col-md-6">
          <label for="contrasenaModificacion">Contraseña</label>
          <input type="password" class="form-control" id="contrasenaModificacion" name="contrasena" placeholder="Contraseña" minlength="8">
        </div>
      </div>
      <div class="form-group">
        <label for="emailModificacion">Email</label>
        <input type="email" class="form-control" id="emailModificacion" name="email" placeholder="Email" value="{{ $usuario->getEmail() }}" required>
      </div>
      <div class="form-row justify-content-center">
        <button type="submit" id="btnModificarUsuario" class="btn btn-primary">Modificar usuario</button>
      </div>
    </form>
  </div>
</div>
