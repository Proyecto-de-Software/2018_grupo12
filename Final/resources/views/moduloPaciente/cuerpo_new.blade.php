<div class="tab-pane fade" id="contenidoAgregarPaciente" role="tabpanel" aria-labelledby="tabAgregarPaciente">
  <div class="form-row justify-content-center mt-2">
    <div class="form-group col-6">
      <label for="tipoDeAlta">Formulario de carga</label>
      <select id="tipoDeAlta" class="custom-select">
        <option value="simple" selected>Simple</option>
        <option value="completa">Completa</option>
      </select>
    </div>
  </div>
  <form id="formularioAgregarPaciente" onsubmit="return false">
    <div id="form_simple">
      <div class="form-row justify-content-center">
        <div class="form-group col-8">
          <div>
            <label for="as_nroHC">Numero de historia clínica</label>
            <div class="rojo contentsclass">*</div>
          </div>
          <input type="number" class="form-control" id="as_nroHC" name="nroHistoriaClinica" placeholder="Historia clinica">
        </div>
      </div>
    </div>
    <div id="form_completo" class="noneclass">
      <div class="form-row">
        <div class="form-group col-md-6">
          <div>
            <label for="ac_nombre">Nombre</label>
            <div class="rojo contentsclass">*</div>
          </div>
          <input type="text" class="form-control" id="ac_nombre" name="nombre" placeholder="Nombre...">
        </div>
        <div class="form-group col-md-6">
          <div>
            <label for="ac_apellido">Apellido</label>
            <div class="rojo contentsclass">*</div>
          </div>
          <input type="text" class="form-control" id="ac_apellido" name="apellido" placeholder="Apellido...">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="ac_lNacimiento">Lugar de nacimiento</label>
          <input type="text" class="form-control" id="ac_lNacimiento" name="lNacimiento">
        </div>
        <div class="form-group col-md-6">
          <div>
            <label for="ac_fNacimiento">Fecha de nacimiento</label>
            <div class="rojo contentsclass">*</div>
          </div>
          <input type="date" class="form-control" id="ac_fNacimiento" name="fNacimiento">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="ac_partido">Partido</label>
          <select id="ac_partido" name="partido" class="custom-select">

          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="ac_localidad">Localidad</label>
          <select id="ac_localidad" name="localidad" class="custom-select">

          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="ac_regionSanitaria">Region sanitaria</label>
          <input type="text" class="form-control" id="ac_regionSanitaria" disabled>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-12">
          <div>
            <label for="ac_domicilio">Domicilio</label>
            <div class="rojo contentsclass">*</div>
          </div>
          <input type="text" class="form-control" id="ac_domicilio" name="domicilio">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-12">
          <div>
            <label for="ac_genero">Genero</label>
            <div class="rojo contentsclass">*</div>
          </div>
          <select id="ac_genero" name="genero" class="custom-select">
            <option value="3" selected>Otro</option>
            <option value="1">Masculino</option>
            <option value="2">Femenino</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12 col-lg-4">
          <div>
            <label for="ac_tieneDoc">Tiene en su poder su documento</label>
            <div class="rojo contentsclass">*</div>
          </div>
          <select id="ac_tieneDoc" name="tieneDoc" class="custom-select">
            <option value="0" selected>No</option>
            <option value="1">Si</option>
          </select>
        </div>
        <div class="form-group col-md-6 col-lg-4">
          <div>
            <label for="ac_tipoDoc">Tipo de documento</label>
            <div class="rojo contentsclass">*</div>
          </div>
          <select id="ac_tipoDoc" name="tipoDoc" class="custom-select">

          </select>
        </div>
        <div class="form-group col-md-6 col-lg-4">
          <div>
            <label for="ac_nroDoc">Numero de documento</label>
            <div class="rojo contentsclass">*</div>
          </div>
          <input type="number" class="form-control" id="ac_nroDoc" name="nroDoc" min="10000000" max="9999999999">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="ac_nroHC">Numero de historia clinica</label>
          <input type="number" class="form-control" id="ac_nroHC" name="nroHistoriaClinica" min="0" max="999999">
        </div>
        <div class="form-group col-md-6">
          <label for="ac_nroCarpeta">Numero de carpeta</label>
          <input type="number" class="form-control" id="ac_nroCarpeta" name="nroCarpeta" min="0" max="99999">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-12">
          <label for="ac_nroTel_cel">Numero de telefono o celular</label>
          <input type="text" class="form-control" id="ac_nroTel_cel" name="nroTel_cel">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-12">
          <label for="ac_obraSocial">Obra social</label>
          <select id="ac_obraSocial" name="obraSocial" class="custom-select">

          </select>
        </div>
      </div>
    </div>
    <div class="form-row justify-content-center">
      <button type="submit" id="btnAgregarPaciente" class="btn btn-primary">Agregar paciente</button>
    </div>
  </form>
</div>
