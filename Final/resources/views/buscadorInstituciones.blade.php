@extends($pagina)


@push('scripts')
    <script src="js/vue.js"></script>
    <script src="js/axios.min.js"></script>
@endpush

@section('titulo')
    Buscar Institucion
@endsection

@section('cuerpo')
    <div class="container my-3" id="App">
        <ul class="nav nav-tabs" id="menuTabs" role="tablist">
            @foreach ($permisos as $permiso)
                @includeIf('moduloInstitucion.tab_' . $permiso)
            @endforeach
        </ul>
        <div class="tab-content" id="myTabContent">
            @foreach ($permisos as $permiso)
                @includeIf('moduloInstitucion.cuerpo_' . $permiso)
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ------------------ Alertas ------------------
        //Funcion para armar la alerta
        function mostrarAlerta(texto, tipo){
        new Message(texto, {
            duration: 4000,
            type: tipo,
            class : 'alerta'
        }).show();
        }
    </script>
    <script>
        var app = new Vue(
        {
            delimiters: ['${', '}'],
            el: '#App',
            data: {
                //Variables del index
                partidos: [],
                nombreRegionSanitaria: '',
                instituciones: [],
                limite: {{ $limite }},
                pagActual: 1,
                //Variables del formulario modificacion
                tiposInstituciones: [],
                localidades: [],
                institucionSeleccionada: {},
                //Variables del formulario de alta
                institucionNueva: {},
                localidades_n: [],
                urlBase: 'https://grupo12.proyecto2018.linti.unlp.edu.ar/Final/public/api/'
                //urlBase: 'http://localhost/grupo12/Final/public/api/' //Es para local
            },
            created() {
                //Cargo lista de tipos de intitucion
                axios.get(this.urlBase + 'tipo-instituciones')
                .then(response => {
                    this.tiposInstituciones = response.data
                })
                .catch(e => {
                    this.errors.push(e)
                })
                //Cargo Partidos
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
                        axios.get(this.urlBase + 'instituciones/region-sanitaria/' + this.nombreRegionSanitaria.split(" ")[1])
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
                cargarLocalidades: function () {
                    var id = $("#partido").val();
                    axios.get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad/partido/' + id)
                    .then(response => {
                        this.localidades = response.data
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
                },
                cargarLocalidadesParaFormAgregar: function () {
                    var id = $("#partido_n").val();
                    axios.get('https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad/partido/' + id)
                    .then(response => {
                        this.localidades_n = response.data
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
                },
                mostrarFormularioModificacion: function(event) {
                    var id = event.target.parentElement.id;

                    var insti = this.instituciones.find(function(insti) {
                        return insti.id == id;
                    })

                    this.institucionSeleccionada = {};
                    Object.assign(this.institucionSeleccionada, insti);

                    $("#partido").val(insti.partido_id);
                    this.cargarLocalidades();

                    $("#tabModificarInstitucion").css({"display": "block"});
                    $("#tabModificarInstitucion").children().click();
                },
                modificarInstitucion: function(){
                    var id = this.institucionSeleccionada.id;
                    axios.put(
                        this.urlBase + 'instituciones/' + this.institucionSeleccionada.id,
                        {
                            nombre: this.institucionSeleccionada.nombre,
                            director: this.institucionSeleccionada.director,
                            direccion: this.institucionSeleccionada.direccion,
                            telefono: this.institucionSeleccionada.telefono,
                            localidad_id: this.institucionSeleccionada.localidad_id,
                            tipo_institucion_id: this.institucionSeleccionada.tipo_institucion_id
                        },
                        {
                            headers: {
                                Authorization: 'Bearer ' + sessionStorage.apiToken
                            }
                        }
                    )
                    .then(response => {
                        this.cargarRegionSanitaria();

                        mostrarAlerta("Institucion actualizada correctamente", "success");
                        $("#tabInstituciones").children().click();
                        this.ocultarPestana();
                    })
                    .catch(e => {
                        if (e.response.data.message) {
                            mostrarAlerta(e.response.data.message, "error")
                        }else if (e.response.data.error) {
                            mostrarAlerta(e.response.data.error, "error")
                        } else {
                            mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde", "error")
                        };
                    })
                },
                agregarInstitucion: function(){
                    axios.post(
                        this.urlBase + 'instituciones',
                        {
                            nombre: this.institucionNueva.nombre,
                            director: this.institucionNueva.director,
                            direccion: this.institucionNueva.direccion,
                            telefono: this.institucionNueva.telefono,
                            localidad_id: this.institucionNueva.localidad_id,
                            tipo_institucion_id: this.institucionNueva.tipo_institucion_id
                        },
                        {
                            headers: {
                                Authorization: 'Bearer ' + sessionStorage.apiToken
                            }
                        }
                    )
                    .then(response => {
                        if ($("#partidos").val()) {
                            this.cargarRegionSanitaria();
                        }

                        mostrarAlerta("Institucion actualizada correctamente", "success");
                        $("#tabInstituciones").children().click();
                        this.institucionNueva = {};
                    })
                    .catch(e => {
                        if (e.response.data.message) {
                            mostrarAlerta(e.response.data.message, "error")
                        }else if (e.response.data.error) {
                            mostrarAlerta(e.response.data.error, "error")
                        } else {
                            mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde", "error")
                        };
                    })

                },
                ocultarPestana: function () {
                    $("#tabModificarInstitucion").css({"display": "none"});
                },
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
