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
