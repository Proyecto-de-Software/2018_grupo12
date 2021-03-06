<div class="tab-pane fade show active" id="contenidoConsultas" role="tabpanel" aria-labelledby="tabConsultas">
  <div>
    <div class="form-row align-items-center mt-2">
      <div class="contentsclass">
        <div class="col-md-auto mb-2 pr-md-0">
          <label for="tipoBusqueda">Buscar por:</label>
        </div>
        <div class="col-md-auto mb-2">
          <select class="custom-select inputBuscadorPaciente" id="tipoBusqueda" >
            <option id="opcionPorDefecto" value="no_aplica" selected>Ninguno</option>
            <option value="dni">Documento</option>
            <option value="historia_clinica">Historia clinica</option>
          </select>
        </div>
      </div>
      <div id="dni" class="noneclass">
        <div class="col-md-auto mb-2 ml-auto px-md-0">
          <label for="bus_tipoDoc">Tipo:</label>
        </div>
        <div class="col-md-auto">
          <select class="custom-select mb-2 inputBuscadorPaciente" id="bus_tipoDoc" >

          </select>
        </div>
        <div class="col-md-auto mb-2 ml-2 px-md-0">
          <label for="bus_nroDoc">Numero: </label>
        </div>
        <div class="col-md-auto">
          <input type="number" class="form-control mb-2 inputBuscadorPaciente" id="bus_nroDoc" min="0">
        </div>
      </div>
      <div id="historia_clinica" class="noneclass">
        <div class="col-md-auto mb-2 ml-auto px-md-0">
          <label for="bus_nroHistoriaClinica">Historia clinica:</label>
        </div>
        <div class="col-md-auto">
          <input type="number" class="form-control mb-2" id="bus_nroHistoriaClinica" min="0" max="999999">
        </div>
      </div>
      <div class="col-md-auto textcenter" >
        <button type="button" id="btnBuscar" class="btn btn-outline-primary mb-2">Buscar</button>
      </div>
    </div>
  </div>
  <div id="pagina" class="">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th class="textcenter" scope="col">Nombre</th>
            <th class="textcenter" scope="col">Apellido</th>
            <th class="textcenter" scope="col">Documento</th>
            <th class="textcenter" scope="col">Historia Clinica</th>
            <th class="textcenter" scope="col">Fecha</th>
            <th class="textcenter" scope="col">Motivo</th>
            <th class="textcenter" scope="col">Internacion</th>
            <th class="textcenter" scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody id="cuerpoTablaConsultas">

          <tr><td colspan="8" class="textcenter">Cargando...</td></tr>

        </tbody>
      </table>
    </div>
  </div>
  <nav aria-label="Navegacion entre paginas">
    <ul class="pagination justify-content-center" id="navPags">
      <li id="anterior" class="page-item disabled">
        <button class="page-link" id="btnAnterior" aria-label="Pagina anterior">
          <span aria-hidden="true">&laquo;</span>
          <span class="sr-only">Pagina anterior</span>
        </button>
      </li>
      <li id="inicio" class="page-item active"><button id="btnInicio" class="page-link">1</button></li>
      <li id="medio" class="page-item"><button id="btnMedio" class="page-link">2</button></li>
      <li id="final" class="page-item"><button id="btnFinal" class="page-link">3</button></li>
      <li id="siguiente" class="page-item">
        <button class="page-link" id="btnSiguiente" aria-label="Pagina siguiente">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Pagina siguiente</span>
        </button>
      </li>
    </ul>
  </nav>
  <br>
</div>
