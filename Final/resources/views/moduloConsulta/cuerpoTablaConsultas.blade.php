@php
    $estado = [
        "0" => "No",
        "1" => "Si"
    ]
@endphp

@forelse ($consultas as $consulta)
    <tr>
        <td class="textcenter" >{{ $consulta["nombre"] }}</td>
        <td class="textcenter" >{{ $consulta["apellido"] }}</td>
        <td class="textcenter" >{{ ucfirst(strtolower($consulta["tipo_documento"] . " " . $consulta["documento"])) }}</td>
        <td class="textcenter" >{{ $consulta["historia_clinica"] }}</td>
        <td class="textcenter" >{{ date('d-m-Y', strtotime($consulta["fecha"] )) }}</td>
        <td class="textcenter" >{{ $consulta["motivo"] }}</td>
        <td class="textcenter" >{{ $estado[$consulta["internacion"]] }}</td>
        <td class="textcenter" id="{{ $consulta["id"] }}">
            @foreach ($permisos as $permiso)
                @includeIf('moduloConsulta.boton_' . $permiso)
            @endforeach
        </td>
    </tr>
@empty
    <tr><td colspan="8" class="textcenter" >No hay consultas para mostrar</td></tr>
@endforelse
