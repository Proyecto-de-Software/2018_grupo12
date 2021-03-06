<div class="tab-pane fade" id="contenidoAgregarConsulta" role="tabpanel" aria-labelledby="tabAgregarConsulta">
    <div class="container">
        <div class="row">
            <div class="col textcenter mt-2">
                <h3>Buscador paciente</h3>
            </div>
        </div>
    </div>
    <div>
        <div class="form-row align-items-center mt-2">
            <div class="contentsclass">
                <div class="col-md-auto mb-2 pr-md-0">
                    <label for="tipoBusqueda">Buscar por:</label>
                </div>
                <div class="col-md-auto mb-2">
                    <select class="custom-select" id="a_tipoBusqueda" >
                        <option id="a_opcionPorDefecto" value="no_aplica" selected>Ninguno</option>
                        <option value="dni">Documento</option>
                        <option value="historia_clinica">Historia clinica</option>
                    </select>
                </div>
            </div>
            <div id="a_dni" class="noneclass">
                <div class="col-md-auto mb-2 ml-auto px-md-0">
                    <label for="a_bus_tipoDoc">Tipo:</label>
                </div>
                <div class="col-md-auto">
                    <select class="custom-select mb-2 inputBuscadorPaciente" id="a_bus_tipoDoc" >

                    </select>
                </div>
                <div class="col-md-auto mb-2 ml-2 px-md-0">
                    <label for="a_bus_nroDoc">Numero: </label>
                </div>
                <div class="col-md-auto">
                    <input type="number" class="form-control mb-2 inputBuscadorPaciente" id="a_bus_nroDoc" min="0">
                </div>
            </div>
            <div id="a_historia_clinica" class="noneclass">
                <div class="col-md-auto mb-2 ml-auto px-md-0">
                    <label for="a_bus_nroHistoriaClinica">Historia clinica:</label>
                </div>
                <div class="col-md-auto">
                    <input type="number" class="form-control mb-2" id="a_bus_nroHistoriaClinica" min="0" max="999999">
                </div>
            </div>
            <div class="col-md-auto textcenter" >
                <button type="button" id="a_btnBuscar" class="btn btn-outline-primary mb-2">Buscar</button>
            </div>
        </div>
    </div>
    <div id="a_pagina" class="">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="textcenter" scope="col">Nombre</th>
                        <th class="textcenter" scope="col">Apellido</th>
                        <th class="textcenter" scope="col">Documento</th>
                        <th class="textcenter" scope="col">Numero de historia clinica</th>
                        <th class="textcenter" scope="col">Obra social</th>
                        <th class="textcenter" scope="col">Acciones</th>
                     </tr>
                </thead>
                <tbody id="a_cuerpoTablaPacientes">
                    <tr>
                        <td class="textcenter" colspan="6"> Cargando...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <nav aria-label="Navegacion entre paginas">
        <ul class="pagination justify-content-center" id="a_navPags">
            <li id="a_anterior" class="page-item disabled">
                <button class="page-link" id="a_btnAnterior" aria-label="Pagina anterior">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Pagina anterior</span>
                </button>
            </li>
            <li id="a_inicio" class="page-item active"><button id="a_btnInicio" class="page-link">1</button></li>
            <li id="a_medio" class="page-item"><button id="a_btnMedio" class="page-link">2</button></li>
            <li id="a_final" class="page-item"><button id="a_btnFinal" class="page-link">3</button></li>
            <li id="a_siguiente" class="page-item">
                <button class="page-link" id="a_btnSiguiente" aria-label="Pagina siguiente">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Pagina siguiente</span>
                </button>
            </li>
        </ul>
    </nav>
    <form id="formularioAgregarConsulta" onsubmit="return false">
        <div class="form-row">
            <div class="form-group col-md-6">
                <div>
                    <label for="a_nombre">Nombre</label>
                </div>
                <input type="text" class="form-control" id="a_nombre" disabled>
            </div>
            <div class="form-group col-md-6">
                <div>
                    <label for="a_apellido">Apellido</label>
                </div>
                <input type="text" class="form-control" id="a_apellido" disabled>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <div>
                    <label for="a_documento">Documento</label>
                </div>
                <input type="text" class="form-control" id="a_documento" disabled>
            </div>
            <div class="form-group col-md-4">
                <div>
                    <label for="a_hc">Numero de historia clinica</label>
                </div>
                <input type="text" class="form-control" id="a_hc" disabled>
            </div>
            <div class="form-group col-md-4">
                <div>
                    <label for="a_obrasocial">Obra social</label>
                </div>
                <input type="text" class="form-control" id="a_obrasocial" disabled>
            </div>
        </div>
        <div class="form-row justify-content-center">
            <div class="col-12 textcenter">
                <h3>Derivaciones anteriores</h3>
            </div>
            <!-- el atributo style es necesario aca por que sino el mapa no se instancia correctamente -->
            <div class="col-12 col-md-6" style="width: 400px; height:400px" id="mapdiv">

            </div>
        </div>
        <div class="form-row mt-2">
            <div class="form-group col-md-6">
                <div>
                    <label for="a_fecha">Fecha</label>
                    <div class="rojo contentsclass">*</div>
                </div>
                <input type="date" class="form-control" id="a_fecha" required>
            </div>
            <div class="form-group col-md-6">
                <div>
                    <label for="a_motivo">Motivo</label>
                    <div class="rojo contentsclass">*</div>
                </div>
                <select class="custom-select form-control" id="a_motivo" required>
                    <option value="" selected>Elegir...</option>

                    @foreach ($motivos as $motivo)
                        <option value="{{ $motivo->getId() }}"> {{ $motivo->getNombre() }} </option>
                    @endforeach

                </select>
            </div>
        </div>
        <div class="form-row mt-2">
            <div class="form-group col-md-6">
                <div>
                    <label for="a_derivacion">Derivacion</label>
                </div>
                <select class="custom-select form-control" id="a_derivacion">

                </select>
            </div>
            <div class="form-group col-md-6">
                <div>
                    <label for="a_internacion">Internacion</label>
                    <div class="rojo contentsclass">*</div>
                </div>
                <select class="custom-select form-control" id="a_internacion" required>
                    <option value="0" selected>No</option>
                    <option value="1">Si</option>
                </select>
            </div>
        </div>
        <div class="form-row mt-2">
            <div class="form-group col-md-6">
                <div>
                    <label for="a_tratamiento">Tratamiento farmacologico</label>
                </div>
                <select class="custom-select form-control" id="a_tratamiento">
                    <option value="" selected>Elegir...</option>

                    @foreach ($tratamientos as $tratamiento)
                        <option value="{{ $tratamiento->getId() }}"> {{ $tratamiento->getNombre() }} </option>
                    @endforeach

                </select>
            </div>
            <div class="form-group col-md-6">
                <div>
                    <label for="a_acompanamiento">Acompañamiento</label>
                </div>
                <select class="custom-select form-control" id="a_acompanamiento">
                    <option value="" selected>Elegir...</option>

                    @foreach ($acompanamientos as $acompanamiento)
                        <option value="{{ $acompanamiento->getId() }}"> {{ $acompanamiento->getNombre() }} </option>
                    @endforeach

                </select>
            </div>
        </div>
        <div class="form-row mt-2">
            <div class="form-group col">
                <div>
                    <label for="a_articulacion">Articulacion con otras instituciones</label>
                </div>
                <textarea id="a_articulacion" class="form-control" ></textarea>
            </div>
        </div>
        <div class="form-row mt-2">
            <div class="form-group col">
                <div>
                    <label for="a_diagnostico">Diagnostico</label>
                    <div class="rojo contentsclass">*</div>
                </div>
                <textarea id="a_diagnostico" class="form-control" ></textarea>
            </div>
        </div>
        <div class="form-row mt-2">
            <div class="form-group col">
                <div>
                    <label for="a_observaciones">Observaciones</label>
                </div>
                <textarea id="a_observaciones" class="form-control" ></textarea>
            </div>
        </div>

        <br>
        <div class="form-row justify-content-center">
            <button type="submit" id="btnAgregarConsulta" class="btn btn-primary">Agregar consulta</button>
        </div>
        <br>
    </form>
</div>
