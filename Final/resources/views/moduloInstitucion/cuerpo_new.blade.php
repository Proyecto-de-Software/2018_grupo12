<div class="tab-pane fade" id="contenidoAgregarInstitucion" role="tabpanel" aria-labelledby="tabAgregarInstitucion">
    <div class="row justify-content-center mt-2">
        <div class="col-10">
            <form id="formularioAgregarInstitucion" onsubmit="return false">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre_n" name="nombre" placeholder="Nombre" v-model="institucionNueva.nombre" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="director">Director</label>
                        <input type="text" class="form-control" id="director_n" name="director" placeholder="Director" v-model="institucionNueva.director" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Direccion</label>
                        <input type="text" class="form-control" id="direccion_n" name="direccion" placeholder="Direccion" v-model="institucionNueva.direccion">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefono">Telefono</label>
                        <input type="number" class="form-control" id="telefono_n" name="telefono" placeholder="Telefono" minlength="8" v-model="institucionNueva.telefono">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Partido</label>
                        <select class="custom-select form-control" id="partido_n" @change="cargarLocalidadesParaFormAgregar" v-model="institucionNueva.partido_id">
                            <option disabled="disabled" value="" selected>Seleccionar...</option>
                            <option v-for="partido in partidos" v-bind:value="partido.id">${ partido.nombre }</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Localidad</label>
                        <select class="custom-select form-control" id="localidad_n" v-model="institucionNueva.localidad_id">
                            <option disabled="disabled" value="" selected>Seleccionar...</option>
                            <option v-for="localidad in localidades_n" v-bind:value="localidad.id">${ localidad.nombre }</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tipo Institucion</label>
                        <select class="custom-select form-control" id="institucion" v-model="institucionNueva.tipo_institucion_id">
                            <option v-for="tipoInsti in tiposInstituciones" v-bind:value="tipoInsti.id">${ tipoInsti.nombre }</option>
                        </select>
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <button type="submit" id="btnAgregarInstitucion" class="btn btn-primary" v-on:click="agregarInstitucion">Modificar Institución</button>
                </div>
            </form>
        </div>
    </div>
</div>
