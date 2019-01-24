<div class="tab-pane fade" id="contenidoAgregarUsuario" role="tabpanel" aria-labelledby="tabAgregarUsuario">
  <div class="row justify-content-center mt-2">
    <div class="col-10">
      <form id="formularioAgregarUsuario" onsubmit="return false">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
          </div>
          <div class="form-group col-md-6">
            <label for="apellido">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="nombre">Nombre de Usuario</label>
            <input type="text" class="form-control" id="nombreDeUsuario" name="nombreDeUsuario" placeholder="Nombre de usuario" required>
          </div>
          <div class="form-group col-md-6">
            <label for="contrasena">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" minlength="8" required>
          </div>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-row justify-content-center">
          <button type="submit" id="btnAgregarUsuario" class="btn btn-primary">Agregar usuario</button>
        </div>
      </form>
    </div>
  </div>
</div>
