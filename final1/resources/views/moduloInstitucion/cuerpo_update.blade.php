<div class="tab-pane fade" id="contenidoModificarInstitucion" role="tabpanel" aria-labelledby="tabModificarInstitucion">
    <div class="row justify-content-center mt-2">
        <div class="col-10">
            <form id="formularioModificarInstitucion" onsubmit="return false">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" v-model="institucionSeleccionada.nombre" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="director">Director</label>
                        <input type="text" class="form-control" id="director" name="director" placeholder="Director" v-model="institucionSeleccionada.director" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Direccion</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccion" v-model="institucionSeleccionada.direccion">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefono">Telefono</label>
                        <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Telefono" minlength="8" v-model="institucionSeleccionada.telefono">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Partido</label>
                        <select class="custom-select form-control" id="partido" @change="cargarLocalidades" v-model="institucionSeleccionada.partido_id">
                            <option v-for="partido in partidos" v-bind:value="partido.id">${ partido.nombre }</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Localidad</label>
                        <select class="custom-select form-control" id="localidad" v-model="institucionSeleccionada.localidad_id">
                            <option v-for="localidad in localidades" v-bind:value="localidad.id">${ localidad.nombre }</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tipo Institucion</label>
                        <select class="custom-select form-control" id="institucion" v-model="institucionSeleccionada.tipo_institucion_id">
                            <option v-for="tipoInsti in tiposInstituciones" v-bind:value="tipoInsti.id">${ tipoInsti.nombre }</option>
                        </select>
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <button type="submit" id="btnModificarInstitucion" class="btn btn-primary" v-on:click="modificarInstitucion">Modificar Institución</button>
                </div>
            </form>
        </div>
    </div>
</div>
