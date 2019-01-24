<div class="tab-pane fade" id="contenidoModificarPaciente" role="tabpanel" aria-labelledby="tabModificarPaciente">
  <div class="row justify-content-center mt-2">
    <div class="col-10">
      <form id="formularioModificarPaciente" onsubmit="return false">
        <div class="form-row">
          <div class="form-group col-md-6">
            <div>
              <label for="m_nombre">Nombre</label>
              <div class="rojo contentsclass">*</div>
            </div>
            <input type="text" class="form-control" id="m_nombre" name="nombre" placeholder="Nombre...">
          </div>
          <div class="form-group col-md-6">
            <div>
              <label for="m_apellido">Apellido</label>
              <div class="rojo contentsclass">*</div>
            </div>
            <input type="text" class="form-control" id="m_apellido" name="apellido" placeholder="Apellido...">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="m_lNacimiento">Lugar de nacimiento</label>
            <input type="text" class="form-control" id="m_lNacimiento" name="lNacimiento">
          </div>
          <div class="form-group col-md-6">
            <div>
              <label for="m_fNacimiento">Fecha de nacimiento</label>
              <div class="rojo contentsclass">*</div>
            </div>
            <input type="date" class="form-control" id="m_fNacimiento" name="fNacimiento">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="m_partido">Partido</label>
            <select id="m_partido" name="partido" class="custom-select">

            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="m_localidad">Localidad</label>
            <select id="m_localidad" name="localidad" class="custom-select">

            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="m_regionSanitaria">Region sanitaria</label>
            <input type="text" class="form-control" id="m_regionSanitaria" disabled>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-12">
            <div>
              <label for="m_domicilio">Domicilio</label>
              <div class="rojo contentsclass">*</div>
            </div>
            <input type="text" class="form-control" id="m_domicilio" name="domicilio">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-12">
            <div>
              <label for="m_genero">Genero</label>
              <div class="rojo contentsclass">*</div>
            </div>
            <select id="m_genero" name="genero" class="custom-select">
              <option value="3">Otro</option>
              <option value="1">Masculino</option>
              <option value="2">Femenino</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-12 col-lg-4">
            <div>
              <label for="m_tieneDoc">Tiene en su poder su documento</label>
              <div class="rojo contentsclass">*</div>
            </div>
            <select id="m_tieneDoc" name="tieneDoc" class="custom-select">
              <option value="0">No</option>
              <option value="1">Si</option>
            </select>
          </div>
          <div class="form-group col-md-6 col-lg-4">
            <div>
              <label for="m_tipoDoc">Tipo de documento</label>
              <div class="rojo contentsclass">*</div>
            </div>
            <select id="m_tipoDoc" name="tipoDoc" class="custom-select">

            </select>
          </div>
          <div class="form-group col-md-6 col-lg-4">
            <div>
              <label for="m_nroDoc">Numero de documento</label>
              <div class="rojo contentsclass">*</div>
            </div>
            <input type="number" class="form-control" id="m_nroDoc" name="nroDoc" min="10000000" max="9999999999">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="m_nroHC">Numero de historia clinica</label>
            <input type="number" class="form-control" id="m_nroHC" name="nroHistoriaClinica" min="0" max="999999">
          </div>
          <div class="form-group col-md-6">
            <label for="m_nroCarpeta">Numero de carpeta</label>
            <input type="number" class="form-control" id="m_nroCarpeta" name="nroCarpeta" min="0" max="99999">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-12">
            <label for="m_nroTel_cel">Numero de telefono o celular</label>
            <input type="text" class="form-control" id="m_nroTel_cel" name="nroTel_cel">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-12">
            <label for="m_obraSocial">Obra social</label>
            <select id="m_obraSocial" name="obraSocial" class="custom-select">

            </select>
          </div>
        </div>
        <div class="form-row justify-content-center">
          <button type="submit" id="btnModificarPaciente" class="btn btn-primary">Modificar paciente</button>
        </div>
      </form>
    </div>
  </div>
</div>
