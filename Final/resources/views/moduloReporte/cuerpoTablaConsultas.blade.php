@php
    $estado = [
        "0" => "No",
        "1" => "Si"
    ]
@endphp

@forelse ($consultas as $consulta)
    <tr>
        <td class="textcenter" >{{ ucfirst(strtolower($consulta["tipo_documento"] . " " . $consulta["documento"])) }}</td>
        <td class="textcenter" >{{ $consulta["genero"] }}</td>
        <td class="textcenter" >{{ $consulta["localidad"] }}</td>
        <td class="textcenter" >{{ $consulta["historia_clinica"] }}</td>
        <td class="textcenter" >{{ $consulta["fecha"] }}</td>
        <td class="textcenter" >{{ $consulta["motivo"] }}</td>
        <td class="textcenter" >{{ $estado[$consulta["internacion"]] }}</td>
    </tr>
@empty
    <tr><td colspan="7" class="textcenter" >No hay consultas para mostrar</td></tr>
@endforelse
