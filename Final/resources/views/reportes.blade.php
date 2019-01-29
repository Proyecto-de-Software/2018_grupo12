@extends('layoutLogueado')

@section('titulo')
    Reportes
@endsection

@section('cuerpo')
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col">
                <div id="grafico" class="container"></div>
            </div>
        </div>
        <div class="form-row justify-content-center mt-2">
            <div class="col-md-auto mt-1 pr-md-0">
                <label for="agrupar">Agrupar por:</label>
            </div>
            <div class="col-md-5 mb-2">
                <select class="custom-select" id="agrupar" >
                    <option id="opcionPorDefecto" value="motivo" selected>Motivo</option>
                    <option value="genero">Genero</option>
                    <option value="localidad">Localidad</option>
                </select>
            </div>
        </div>
        <div class="row justify-content-end">
            <a href="reportes/pdf" target="_blank" class="btn btn-outline-primary">Exportar reporte a PDF</a>
        </div>
        <div id="pagina" class="mt-3">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th class="textcenter" scope="col">Documento</th>
                        <th class="textcenter" scope="col">Genero</th>
                        <th class="textcenter" scope="col">Localidad</th>
                        <th class="textcenter" scope="col">Historia Clinica</th>
                        <th class="textcenter" scope="col">Fecha</th>
                        <th class="textcenter" scope="col">Motivo</th>
                        <th class="textcenter" scope="col">Internacion</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaConsultas">

                        <tr><td colspan="7" class="textcenter">Cargando...</td></tr>

                    </tbody>
                </table>
            </div>
        </div>
        <nav aria-label="Navegacion entre paginas">
            <ul class="pagination justify-content-center">
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
@endsection

@push('scripts')
    <script src="js/highcharts.src.js"></script>
    <script src="js/chartExporting.js"></script>
    <script src="js/reportes.js"></script>

    <script>
        var porMotivo = [
            @forelse ($porcentajesMotivo as $porcentajeMotivo)
                {
                    name: "{{ $porcentajeMotivo['nombre'] }}",
                    y: {{ $porcentajeMotivo['porcentaje'] }}
                },
            @empty
                {
                    name: "No hay consultas para mostrar",
                    y: 100,
                }
            @endforelse
        ]
        var porGenero = [
            @forelse ($porcentajesGenero as $porcentajeGenero)
                {
                    name: "{{ $porcentajeGenero['nombre'] }}",
                    y: {{ $porcentajeGenero['porcentaje'] }},
                },
            @empty
                {
                    name: "No hay consultas para mostrar",
                    y: 100,
                }
            @endforelse
        ]
        var porLocalidad = [
            @forelse ($porcentajesLocalidad as $porcentajeLocalidad)
                {
                    name: "{{ $porcentajeLocalidad['nombre'] }}",
                    y: {{ $porcentajeLocalidad['porcentaje'] }},
                },
                @empty
                {
                    name: "No hay consultas para mostrar",
                    y: 100,
                }
            @endforelse
        ]

        var agruparPor = {
            motivo: porMotivo,
            genero: porGenero,
            localidad: porLocalidad
        }

        var chart1 = Highcharts.chart('grafico', {

            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },

            title: {
                text: 'Reportes de consultas agrupadas por motivo'
            },

            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },

            plotOptions: {
                pie: {
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },

            series: [{
                name: 'porcentaje',
                size: 150,
                colorByPoint: true,
                data: agruparPor.motivo
            }],

            exporting:{
                filename: 'reporte'
            }
        });

        function actualizarGrafico() {
            var tipo = this.value;

            chart1.update({
                series: [{
                    name: 'procentaje',
                    size: 150,
                    colorByPoint: true,
                    data: agruparPor[tipo]
                }],
                title: {
                    text: ('Reportes de consultas agrupadas por ' + tipo)
                },
            });
        }

        $("#agrupar").bind('change',actualizarGrafico);
    </script>
@endpush
