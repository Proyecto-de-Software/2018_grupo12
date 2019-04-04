@extends($pagina)


@push('scripts')
    <script src="js/vue.js"></script>
    <script src="js/axios.min.js"></script>
@endpush

@section('titulo')
    Buscar Institucion
@endsection

@section('cuerpo')
    <div class="container my-3">
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
