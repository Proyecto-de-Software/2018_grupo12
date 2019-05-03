@php
    $estado =
        [
            "0" => "Bloqueado",
            "1" => "Activo"
        ]
@endphp

@forelse ($usuarios as $usuario)
    <tr>
        <td>{{ ucwords($usuario->getFirst_name()) }}</td>
        <td>{{ ucwords($usuario->getLast_name()) }}</td>
        <td>{{ $usuario->getEmail() }}</td>
        <td>{{ $usuario->getUsername() }}</td>
        <td>
            {{ $estado[$usuario->getActivo()] }}
        </td>
        <td>
            @forelse ($usuario->getRoles() as $rol)
                {{ ucfirst(strtolower($rol->getNombre())) }} <br>
            @empty
                No tiene
            @endforelse
        </td>
        <td id="{{ $usuario->getId() }}">
            @foreach ($permisos as $permiso)
                @includeIf('moduloUsuario.boton_' . $permiso)
            @endforeach
        </td>
    </tr>
@empty
    <tr><td colspan="7" class="textcenter" >No hay usuarios para mostrar</td></tr>
@endforelse
