<div class="tab-pane fade" id="contenidoModificarInstitucion" role="tabpanel" aria-labelledby="tabModificarInstitucion">
    <div class="row justify-content-center mt-2">
        <div class="col-10">
            <form id="formularioModificarInstitucion" onsubmit="return false">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="director">Director</label>
                        <input type="text" class="form-control" id="director" name="director" placeholder="Director" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Direccion</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccion">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefono">Telefono</label>
                        <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Telefono" minlength="8">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Partido</label>
                        <select class="custom-select form-control" id="partido" @change="cargarLocalidades">
                            <option disabled="disabled" value="" selected>Seleccionar...</option>
                            <option v-for="partido in partidos" v-bind:value="partido.id">${ partido.nombre }</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Localidad</label>
                        <select class="custom-select form-control" id="localidad" >
                            <option disabled="disabled" value="" selected>Seleccionar...</option>
                            <option v-for="localidad in localidades" v-bind:value="localidad.id">${ localidad.nombre }</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tipo Institucion</label>
                        <select class="custom-select form-control" id="institucion">
                            <option disabled="disabled" value="" selected>Seleccionar...</option>
                            <option v-for="tipoInsti in tiposInstituciones" v-bind:value="tipoInsti.id">${ tipoInsti.nombre }</option>
                        </select>
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <button type="submit" id="btnModificarInstitucion" class="btn btn-primary">Modificar Institución</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var formularioModificacion = new Vue(
        {
            delimiters: ['${', '}'],
            el: '#formularioModificarInstitucion',
            data: {
                partidos: [],
                tiposInstituciones: [],
                localidades: []
            },
            created() {
                //Cargo lista de tipos de intitucion
                axios.get('http://localhost/grupo12/final/public/api/tipo-instituciones')
                .then(response => {
                    this.tiposInstituciones = response.data
                })
                .catch(e => {
                    this.errors.push(e)
                })
                //Cargo partidos
                axios.get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido')
                .then(response => {
                    this.partidos = response.data
                })
                .catch(e => {
                    this.errors.push(e)
                })
            },
            methods: {
                cargarLocalidades: function () {
                    var id = $("#partido").val();
                    axios.get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad/partido/' + id)
                    .then(response => {
                        this.localidades = response.data
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
                }
            }
        })
    </script>
@endpush
