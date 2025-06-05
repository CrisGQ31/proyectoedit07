
    <div class="container-fluid">

        <h1 class="mb-4">Gestión de Permisos Usuarios</h1>

        {{-- Botón para abrir modal de nuevo registro --}}
        <button id="btnNuevo" class="btn btn-primary mb-3">Nuevo Permiso Usuario</button>

        {{-- Tabla para listar --}}
        <table id="tablaPermisosUsuarios" class="table table-striped table-bordered" style="width:100%">
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
            <tbody></tbody>
        </table>
    </div>

    {{-- Modal para crear/editar --}}
    <div class="modal fade" id="modalPermisosUsuarios" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formPermisosUsuarios">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Nuevo Permiso Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="idpermisosusuarios" name="idpermisosusuarios">

                        <div class="mb-3">
                            <label for="idusuarios" class="form-label">Usuario</label>
                            <select id="idusuarios" name="idusuarios" class="form-select" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->idusuarios }}">{{ $usuario->nombre }} {{ $usuario->apellidopaterno }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="idpermiso" class="form-label">Permiso</label>
                            <select id="idpermiso" name="idpermiso" class="form-select" required>
                                <option value="">Seleccione un permiso</option>
                                @foreach ($permisos as $permiso)
                                    <option value="{{ $permiso->idpermiso }}">{{ $permiso->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" id="descripcion" name="descripcion" class="form-control" maxlength="255">
                        </div>

                        <div class="mb-3">
                            <label for="activo" class="form-label">Activo</label>
                            <select id="activo" name="activo" class="form-select" required>
                                <option value="S">Sí</option>
                                <option value="N">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let tabla = $('#tablaPermisosUsuarios').DataTable({
                ajax: "{{ route('permisosusuarios.data') }}",
                columns: [
                    { data: 'id' },
                    { data: 'usuario' },
                    { data: 'permiso' },
                    { data: 'descripcion' },
                    {
                        data: 'activo',
                        render: function(data) {
                            return data === 'S' ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';
                        }
                    },
                    { data: 'fecharegistro' },
                    { data: 'fechaactualizacion' },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                        <button class="btn btn-sm btn-primary btn-edit" data-id="${data}">Editar</button>
                        <button class="btn btn-sm btn-warning btn-toggle" data-id="${data}">${row.activo === 'S' ? 'Desactivar' : 'Activar'}</button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${data}">Eliminar</button>
                    `;
                        }
                    }
                ]
            });

            // Abrir modal nuevo
            $('#btnNuevo').click(function() {
                $('#formPermisosUsuarios')[0].reset();
                $('#idpermisosusuarios').val('');
                $('#modalLabel').text('Nuevo Permiso Usuario');
                $('#activo').val('S');
                $('#modalPermisosUsuarios').modal('show');
            });

            // Guardar registro (crear o actualizar)
            $('#formPermisosUsuarios').submit(function(e) {
                e.preventDefault();

                let id = $('#idpermisosusuarios').val();
                let url = id ? `/permisosusuarios/update/${id}` : '/permisosusuarios/store';
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modalPermisosUsuarios').modal('hide');
                        tabla.ajax.reload();
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message || 'Ocurrió un error');
                    }
                });
            });

            // Cargar datos para editar
            $('#tablaPermisosUsuarios').on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get(`/permisosusuarios/edit/${id}`, function(data) {
                    $('#modalLabel').text('Editar Permiso Usuario');
                    $('#idpermisosusuarios').val(data.idpermisosusuarios);
                    $('#idusuarios').val(data.idusuarios);
                    $('#idpermiso').val(data.idpermiso);
                    $('#descripcion').val(data.descripcion);
                    $('#activo').val(data.activo);
                    $('#modalPermisosUsuarios').modal('show');
                });
            });

            // Cambiar estado activo/inactivo
            $('#tablaPermisosUsuarios').on('click', '.btn-toggle', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: `/permisosusuarios/toggle/${id}`,
                    method: 'PATCH',
                    success: function(response) {
                        tabla.ajax.reload();
                        alert(response.message);
                    },
                    error: function() {
                        alert('Error al cambiar estado');
                    }
                });
            });

            // Eliminar registro
            $('#tablaPermisosUsuarios').on('click', '.btn-delete', function() {
                if (!confirm('¿Seguro que deseas eliminar este registro?')) return;

                let id = $(this).data('id');
                $.ajax({
                    url: `/permisosusuarios/delete/${id}`,
                    method: 'DELETE',
                    success: function(response) {
                        tabla.ajax.reload();
                        alert(response.message);
                    },
                    error: function() {
                        alert('Error al eliminar registro');
                    }
                });
            });
        });
    </script>

