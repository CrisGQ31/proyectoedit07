
    <div class="container">
        <h1 class="mb-4">Listado de Permisos Usuarios</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('permisosusuarios.create') }}" class="btn btn-primary mb-3">Asignar Nuevo Permiso</a>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Permiso</th>
                <th>Descripción</th>
                <th>Activo</th>
                <th>Fecha Registro</th>
                <th>Fecha Actualización</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($permisosUsuarios as $permisoUsuario)
                <tr>
                    <td>{{ $permisoUsuario->idpermisosusuarios }}</td>
                    <td>{{ $permisoUsuario->usuario->nombre ?? 'N/A' }}</td>
                    <td>{{ $permisoUsuario->permiso->descripcion ?? 'N/A' }}</td>
                    <td>{{ $permisoUsuario->descripcion }}</td>
                    <td>{{ $permisoUsuario->activo ? 'Sí' : 'No' }}</td>
                    <td>{{ \Carbon\Carbon::parse($permisoUsuario->fecharegistro)->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($permisoUsuario->fechaactualizacion)->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('permisosusuarios.edit', $permisoUsuario->idpermisosusuarios) }}" class="btn btn-sm btn-warning">Editar</a>

                        <form action="{{ route('permisosusuarios.destroy', $permisoUsuario->idpermisosusuarios) }}" method="POST" style="display:inline-block" onsubmit="return confirm('¿Estás seguro de cambiar el estado?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                {{ $permisoUsuario->activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">No hay registros.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

