@forelse ($pacientes as $paciente)
    <tr>
        <td class="textcenter" >{{ ucfirst(strtolower($paciente->getNombre())) }}</td>
        <td class="textcenter" >{{ ucfirst(strtolower($paciente->getApellido())) }}</td>
        <td class="textcenter" >{{ $paciente->getNombreTipoDocumento() . " " . $paciente->getNumero() }}</td>
        <td class="textcenter" >{{ $paciente->getNro_historia_clinica() }}</td>
        <td class="textcenter" >{{ $paciente->getNombreObraSocial() }}</td>
        <td id="{{ $paciente->getId() }}" class="textcenter">
            <button type="button" name="autocomplete" class="btn btn-sm btn-primary mb-1">Seleccionar</button>
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="textcenter" >No hay pacientes para mostrar</td></tr>
@endforelse
