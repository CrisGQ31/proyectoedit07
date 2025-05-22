<section class="content-header">
    <h1>
        Permisos
        <small>Gestión de permisos</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active">Permisos</li>
    </ol>
</section>

<section class="content">
    <div>
        <button class="btn btn-primary" id="btnAddPermiso" onclick="abrirModalPermiso()">Agregar Permiso</button>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <h3>Permisos Activos</h3>
            <table id="tblPermisos" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Activo</th>
                    <th>Fecha Registro</th>
                    <th>Fecha Actualización</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-12">
            <h3>Permisos Inactivos</h3>
            <table id="tblPermisosInactivos" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Activo</th>
                    <th>Fecha Registro</th>
                    <th>Fecha Actualización</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<!-- MODAL PERMISO -->
<div class="modal fade" id="modalEditPermiso" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEditPermiso">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Permiso</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hddIdPermiso" name="id">
                    <div class="form-group">
                        <label for="descripcionPermiso">Descripción</label>
                        <input type="text" class="form-control" id="descripcionPermiso" name="descripcion">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button id="btnRegisterPermiso" type="button" class="btn btn-primary">Guardar</button>
                    <button id="btnUpdatePermiso" type="button" class="btn btn-primary" style="display: none">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function abrirModalPermiso() {
        $('#formEditPermiso')[0].reset();
        $('#btnRegisterPermiso').show();
        $('#btnUpdatePermiso').hide();
        $('#modalEditPermiso').modal('show');
    }

    function actualizarDatatablePermisos() {
        tblPermisos.ajax.reload(null, false);
        tblPermisosInactivos.ajax.reload(null, false);
    }

    function togglePermiso(id, status) {
        $.post('/permisos/toggle', {
            id: id,
            activo: status,
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.status === 'success') {
                Swal.fire('Éxito', response.msg, 'success');
                actualizarDatatablePermisos();
            } else {
                Swal.fire('Error', response.msg, 'error');
            }
        });
    }

    function getPermisoData(id) {
        $.get('/permisos/edit/' + id, function(response) {
            if (response.status === 'success') {
                $('#hddIdPermiso').val(response.data.idpermiso);
                $('#descripcionPermiso').val(response.data.descripcion);
                $('#btnRegisterPermiso').hide();
                $('#btnUpdatePermiso').show();
                $('#modalEditPermiso').modal('show');
            } else {
                Swal.fire('Error', response.msg, 'error');
            }
        });
    }

    $(document).ready(function() {
        tblPermisos = $('#tblPermisos').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("permisos.data") }}',
                data: { activo: 'S' }
            },
            columns: [
                { data: 'idpermiso' },
                { data: 'descripcion' },
                { data: 'activo' },
                { data: 'fecharegistro' },
                { data: 'fechaactualizacion' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `
                            <button class="btn btn-sm btn-warning" onclick="getPermisoData(${data.idpermiso})">Consultar</button>
                            <button class="btn btn-sm btn-danger" onclick="togglePermiso(${data.idpermiso}, 'N')">Desactivar</button>
                        `;
                    }
                }
            ]
        });

        tblPermisosInactivos = $('#tblPermisosInactivos').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("permisos.data") }}',
                data: { activo: 'N' }
            },
            columns: [
                { data: 'idpermiso' },
                { data: 'descripcion' },
                { data: 'activo' },
                { data: 'fecharegistro' },
                { data: 'fechaactualizacion' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `
                            <button class="btn btn-sm btn-success" onclick="togglePermiso(${data.idpermiso}, 'S')">Activar</button>
                        `;
                    }
                }
            ]
        });

        $('#btnRegisterPermiso').on('click', function () {
            const descripcion = $('#descripcionPermiso').val().trim();

            if (!descripcion) {
                Swal.fire('Error', 'La descripción es obligatoria.', 'error');
                return;
            }

            const formData = {
                descripcion: descripcion,
                _token: '{{ csrf_token() }}'
            };

            $.post('/permisos/store', formData, function (response) {
                if (response.status === 'success') {
                    Swal.fire('Éxito', response.msg, 'success');
                    $('#modalEditPermiso').modal('hide');
                    $('#formEditPermiso')[0].reset();
                    $('#hddIdPermiso').val('');
                    actualizarDatatablePermisos();
                } else {
                    Swal.fire('Error', response.msg || 'Ocurrió un error.', 'error');
                }
            });
        });


        $('#btnUpdatePermiso').on('click', function () {
            const id = $('#hddIdPermiso').val().trim();
            const descripcion = $('#descripcionPermiso').val().trim();

            if (!descripcion) {
                Swal.fire('Error', 'La descripción es obligatoria.', 'error');
                return;
            }

            $.ajax({
                url: '/permisos/update/' + id,
                method: 'PUT',
                data: {
                    descripcion: descripcion,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.msg, 'success');
                        $('#modalEditPermiso').modal('hide');
                        $('#formEditPermiso')[0].reset();
                        $('#hddIdPermiso').val('');
                        actualizarDatatablePermisos();
                    } else {
                        Swal.fire('Error', response.msg || 'Ocurrió un error.', 'error');
                    }
                }
            });
        });
    });
</script>
