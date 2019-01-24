@php
$botonEstado =
    [
        "0" => '<button type="button" name="activar" class="btn btn-sm btn-success btnActivar mb-1">Activar</button>',
        "1" => '<button type="button" name="bloquear" class="btn btn-sm btn-danger mb-1">Bloquear</button>'
    ]
@endphp

{!! $botonEstado[$usuario->getActivo()] !!}
