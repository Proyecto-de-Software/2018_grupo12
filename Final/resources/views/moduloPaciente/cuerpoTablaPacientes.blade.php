@forelse ($pacientes as $paciente)
    <tr>
        <td class="textcenter" >{{ ucfirst(strtolower($paciente->getNombre())) }}</td>
        <td class="textcenter" >{{ ucfirst(strtolower($paciente->getApellido())) }}</td>
        <td class="textcenter" >{{ $paciente->getNombreTipoDocumento() }}</td>
        <td class="textcenter" >{{ $paciente->getNumero() }}</td>
        <td class="textcenter" >{{ $paciente->getNro_historia_clinica() }}</td>
        <td class="textcenter" >{{ $paciente->getNombreObraSocial() }}</td>
        <td id="{{ $paciente->getId() }}">
            @foreach ($permisos as $permiso)
                @includeIf('moduloPaciente.boton_' . $permiso)
            @endforeach
        </td>
    </tr>
@empty
    <tr><td colspan="7" class="textcenter" >No hay pacientes para mostrar</td></tr>
@endforelse
