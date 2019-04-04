<div class="tab-pane fade show active" id="contenidoInstituciones" role="tabpanel" aria-labelledby="tabInstituciones">
    <div class="container" id="main">
        <div class="row my-3">
            <div class="col">
                <div class="form-row align-items-center mt-2">
                    <div class="col-md-auto mb-2">
                        <label class="" for="partidos">Partidos: </label>
                    </div>
                    <div class="col-md-auto mb-2">
                        <select class="custom-select mr-sm-2" v-on:change="cargarRegionSanitaria" id="partidos">
                        <option disabled="disabled" value="" selected>Seleccionar...</option>
                        <option v-for="partido in partidos" v-bind:value="partido.id">${ partido.nombre }</option>
                        </select>
                    </div>
                    <div class="col-md-auto mb-2">
                        <label class="mr-sm-2" for="regionSanitaria">Region Sanitaria:</label>
                    </div>
                    <div class="col-md-auto">
                        <input id="regionSanitaria" type="text" class="form-control mb-2" v-bind:value="nombreRegionSanitaria" class="form-control" readonly>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col" class="textcenter">#</th>
                                <th scope="col" class="textcenter">Institución</th>
                                <th scope="col" class="textcenter">Director/a</th>
                                <th scope="col" class="textcenter">Direccion</th>
                                <th scope="col" class="textcenter">Telefono</th>
                                @if ($logueado)
                                    <th scope="col" class="textcenter">Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaRoles">
                            <tr v-for="insti in paginatedData">
                                <td class="textcenter">${insti.id}</td>
                                <td class="textcenter">${insti.nombre}</td>
                                <td class="textcenter">${insti.director}</td>
                                <td class="textcenter">${insti.direccion}</td>
                                <td class="textcenter">${insti.telefono}</td>
                                @if ($logueado)
                                    <td class="textcenter" v-bind:id="insti.id">
                                        @foreach ($permisos as $permiso)
                                            @includeIf('moduloInstitucion.boton_' . $permiso)
                                        @endforeach
                                    </td>
                                @endif
                            </tr>
                            <tr v-if="paginatedData.length == 0">
                                <td colspan="6" class="textcenter">No hay instituciones para mostrar</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <nav aria-label="Navegacion entre paginas">
                        <ul class="pagination justify-content-center">
                        <li id="anterior" class="page-item disabled">
                            <button class="page-link" aria-label="Previous" @click="prevPage" :disabled="pagActual === 1">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                            </button>
                        </li>
                        <li class="page-item"><button class="page-link">${ pagActual }</button></li>
                        <li id="siguiente" class="page-item disabled">
                            <button class="page-link" aria-label="Next" @click="nextPage" :disabled="pagActual >= pageCount">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                            </button>
                        </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var partidos = new Vue(
        {
            delimiters: ['${', '}'],
            el: '#main',
            data: {
                partidos: [],
                nombreRegionSanitaria: '',
                instituciones: [],
                limite: {{ $limite }},
                pagActual: 1
            },
            created() {
                axios.get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido')
                .then(response => {
                    this.partidos = response.data
                })
                .catch(e => {
                    this.errors.push(e)
                })
            },
            methods: {
                cargarRegionSanitaria: function () {
                    var idPartido = $("#partidos").val();
                    var regId = this.partidos.find(partido => partido.id === idPartido).region_sanitaria_id
                    axios.get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria/' + regId)
                    .then(response => {
                        this.nombreRegionSanitaria = response.data.nombre;
                        axios.get('http://localhost/grupo12/final/public/api/instituciones/region-sanitaria/' + this.nombreRegionSanitaria.split(" ")[1])
                            .then(response => {
                                $("#anterior").attr('class', 'page-item disabled');
                                $("#siguiente").attr('class', 'page-item disabled');
                                this.pagActual = 1;
                                this.instituciones = response.data;
                            })
                            .catch(e => {
                                this.errors.push(e)
                            });
                    })
                    .catch(e => {
                        this.errors.push(e)
                    });
                },
                nextPage(){
                    this.pagActual++;
                },
                prevPage(){
                    this.pagActual--;
                },
                comprobarAnterior: function () {
                    if (this.pagActual === 1) {
                        $("#anterior").attr('class', 'page-item disabled');
                    }else {
                        $("#anterior").attr('class', 'page-item');
                    }
                },
                comprobarSiguiente: function(result){
                    if (this.pagActual >= result) {
                        $("#siguiente").attr('class', 'page-item disabled');
                    }else {
                        $("#siguiente").attr('class', 'page-item');
                    }
                },
                mostrarFormularioModificacion: function(event) {
                    var id = event.target.parentElement.id;
                    console.log(id);

                    var insti = this.instituciones.find(function(insti) {
                        return insti.id == id;
                    })

                    console.log(insti);


                    $("#nombre").val(insti.nombre);
                    $("#director").val(insti.director);
                    $("#direccion").val(insti.direccion);
                    $("#telefono").val(insti.telefono);
                    $("#partido").val(insti.partido_id);

                    axios.get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad/partido/' + insti.partido_id)
                    .then(response => {
                        var localidades = response.data;

                        var select = $("#localidad");
                        select.html("");

                        select.append($('<option/>').attr({ 'value': "" }).text('Seleccionar...'));

                        for (var i = 0; i < localidades.length; i++) {
                            var option = $('<option/>')[0];
                            option.value = localidades[i].id;
                            option.innerHTML = localidades[i].nombre
                            select.append(option);
                        }
                        select.val(insti.localidad_id);
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })

                    $("#institucion").val(insti.tipo_institucion_id);

                    $("#tabModificarInstitucion").css({"display": "block"});
                    $("#tabModificarInstitucion").children().click();
                }
            },
            computed:{
                pageCount(){
                    let l = this.instituciones.length,
                        s = this.limite;
                    let result = Math.ceil(l/s);
                    this.comprobarAnterior();
                    this.comprobarSiguiente(result);
                    return result;
                },
                paginatedData(){
                    const start = (this.pagActual - 1) * this.limite,
                            end = start + this.limite;
                    return this.instituciones.slice(start, end);
                }
            },
        })
    </script>
@endpush
