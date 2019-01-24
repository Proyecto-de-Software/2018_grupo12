@extends('layoutLogueado')

@section('titulo')
    Administración - Configuración
@endsection

@section('cuerpo')
    <div class="container">
        <div class="row justify-content-center my-5">
            <div class="col-8">
                <form method="post" onsubmit="return false">
                    <h2>Información del hospital</h2>
                    <hr>
                    <div class="form-group">
                        <label for="titulo">Titulo</label>
                        <input name="titulo" type="text" class="form-control" id="titulo" placeholder="Titulo del hospital" value="{{ $titulo }}" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripcion</label>
                        <textarea name="descripcion" class="form-control" id="descripcion" placeholder="Descripcion del hospital" required>{{ $descripcion }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Mail de contacto</label>
                        <input name="email" type="email" class="form-control" id="email" placeholder="Email del hospital" value="{{ $email }}" required>
                    </div>
                    <h2>Listado</h2>
                    <hr>
                    <div class="form-group">
                        <label for="limite">Cantidad de elementos por pagina</label>
                        <input name="limite" type="number" min="1" class="form-control" id="limite" value="{{ $limite }}" required>
                    </div>
                    <h2>Mantenimiento</h2>
                    <hr>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="habilitado">Sitio habilitado</label>
                            <select class="form-control" id="habilitado" name="habilitado">
                                <option value="1">Si</option>
                                <option value="0" @if ($habilitado == "0") selected @endif >No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                            @foreach ($permisos as $permiso)
                                @includeIf('moduloConfiguracion.boton_' . $permiso)
                            @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="js/configuracion.js"></script>
@endpush
