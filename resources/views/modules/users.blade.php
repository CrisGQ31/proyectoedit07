<style>
    /* Títulos más grandes y separados */
    h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 2rem;
    }

    h5 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    /* Botones más grandes y con margen */
    .btn-lg {
        padding: 0.6rem 1.2rem;
        font-size: 1.1rem;
    }

    /* Margen inferior grande para separar secciones */
    .section-usuarios {
        margin-bottom: 4rem;
    }

    /* Margen entre botones */
    .btn + .btn {
        margin-left: 0.8rem;
    }

    /* Espaciado en filas de tabla para evitar que se vea amontonado */
    table.dataTable tbody tr {
        height: 50px;
    }
</style>

<div class="container-fluid">
    <h3>Gestión de Usuarios</h3>

    <!-- Usuarios Activos -->
    <div class="section-usuarios">
        <h5>Usuarios Activos</h5>
        <button class="btn btn-primary btn-lg mb-3" onclick="openUserModal()">Agregar Usuario</button>
        <table class="table table-bordered table-striped" id="tablaUsuariosActivos" style="width: 100%;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Correo</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
            </thead>
        </table>
    </div>

    <!-- Usuarios Inactivos -->
    <div class="section-usuarios">
        <h5>Usuarios Inactivos</h5>
        <table class="table table-bordered table-striped" id="tablaUsuariosInactivos" style="width: 100%;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Correo</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formUsuario">
            @csrf
            <input type="hidden" name="idusuarios" id="idusuarios">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUsuarioLabel">Formulario de Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidopaterno">Apellido Paterno:</label>
                        <input type="text" name="apellidopaterno" id="apellidopaterno" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidomaterno">Apellido Materno:</label>
                        <input type="text" name="apellidomaterno" id="apellidomaterno" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo:</label>
                        <input type="email" name="correo" id="correo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Contraseña:</label>
                        <input type="password" id="confirm_password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-lg">Guardar</button>
                    <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        cargarUsuarios();

        $('#formUsuario').on('submit', function(e){
            e.preventDefault();
            if ($('#password').val() !== $('#confirm_password').val()) {
                Swal.fire('Error', 'Las contraseñas no coinciden', 'error');
                return;
            }

            const formData = $(this).serialize();
            $.post("{{ route('usuarios.store') }}", formData, function(res) {
                $('#modalUsuario').modal('hide');
                Swal.fire('Éxito', res.message, 'success');
                $('#tablaUsuariosActivos').DataTable().ajax.reload();
                $('#tablaUsuariosInactivos').DataTable().ajax.reload();
            }).fail(function() {
                Swal.fire('Error', 'No se pudo guardar el usuario', 'error');
            });
        });
    });

    function cargarUsuarios() {
        $('#tablaUsuariosActivos').DataTable({
            processing: true,
            serverSide: false,
            ajax: '/usuarios/data/1', // Activos
            columns: [
                { data: 'idusuarios' },
                { data: 'nombre' },
                { data: 'apellidopaterno' },
                { data: 'apellidomaterno' },
                { data: 'correo' },
                { data: 'fecharegistro' },
                {
                    data: 'idusuarios',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `
                            <button class="btn btn-warning btn-md mr-2" onclick="editarUsuario(${data})">Editar</button>
                            <button class="btn btn-danger btn-md" onclick="cambiarEstadoUsuario(${data})">Desactivar</button>
                        `;
                    }
                }
            ],
            destroy: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });

        $('#tablaUsuariosInactivos').DataTable({
            processing: true,
            serverSide: false,
            ajax: '/usuarios/data/0', // Inactivos
            columns: [
                { data: 'idusuarios' },
                { data: 'nombre' },
                { data: 'apellidopaterno' },
                { data: 'apellidomaterno' },
                { data: 'correo' },
                { data: 'fecharegistro' },
                {
                    data: 'idusuarios',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `
                            <button class="btn btn-success btn-md mr-2" onclick="editarUsuario(${data})">Editar</button>
                            <button class="btn btn-primary btn-md" onclick="cambiarEstadoUsuario(${data})">Activar</button>
                        `;
                    }
                }
            ],
            destroy: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    }

    function openUserModal(usuario = null) {
        $('#formUsuario')[0].reset();
        $('#idusuarios').val('');
        if (usuario) {
            $('#idusuarios').val(usuario.idusuarios);
            $('#nombre').val(usuario.nombre);
            $('#apellidopaterno').val(usuario.apellidopaterno);
            $('#apellidomaterno').val(usuario.apellidomaterno);
            $('#correo').val(usuario.correo);
            $('#password, #confirm_password').val('');
        }
        $('#modalUsuario').modal('show');
    }

    function editarUsuario(id) {
        $.get("{{ url('usuarios/edit') }}/" + id, function(data) {
            openUserModal(data);
        });
    }

    function cambiarEstadoUsuario(id) {
        Swal.fire({
            title: '¿Está seguro?',
            text: '¿Desea cambiar el estado del usuario?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ url('usuarios/toggle') }}/" + id, {_token: '{{ csrf_token() }}'}, function(res) {
                    Swal.fire('Listo', res.message, 'success');
                    $('#tablaUsuariosActivos').DataTable().ajax.reload();
                    $('#tablaUsuariosInactivos').DataTable().ajax.reload();
                });
            }
        });
    }
</script>
