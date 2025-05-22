<section class="content-header">
    <h1>
        Acciones
        <small>Gestión de acciones</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active">Acciones</li>
    </ol>
</section>

<section class="content">
    <div>
        <button class="btn btn-primary" id="btnAddAccion" onclick="abrirModalAccion()">Agregar Acción</button>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <h3>Acciones Activas</h3>
            <table id="tblAcciones" class="display" style="width:100%">
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
            <h3>Acciones Inactivas</h3>
            <table id="tblAccionesInactivas" class="display" style="width:100%">
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

<!-- MODAL ACCION -->
<div class="modal fade" id="modalEditAccion" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEditAccion">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Acción</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hddIdAccion" name="id">
                    <div class="form-group">
                        <label for="descripcionAccion">Descripción</label>
                        <input type="text" class="form-control" id="descripcionAccion" name="descripcionAccion">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button id="btnRegisterAccion" type="button" class="btn btn-primary">Guardar</button>
                    <button id="btnUpdateAccion" type="button" class="btn btn-primary" style="display: none">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function abrirModalAccion() {
        $('#formEditAccion')[0].reset();
        $('#btnRegisterAccion').show();
        $('#btnUpdateAccion').hide();
        $('#modalEditAccion').modal('show');
    }

    function actualizarDatatableAcciones() {
        tblAcciones.ajax.reload(null, false);
        tblAccionesInactivas.ajax.reload(null, false);
    }

    function toggleAccion(id, status) {
        $.post('/acciones/toggle', {
            id: id,
            activo: status,
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.status === 'success') {
                Swal.fire('Éxito', response.msg, 'success');
                actualizarDatatableAcciones();
            } else {
                Swal.fire('Error', response.msg, 'error');
            }
        });
    }

    function getAccionData(id) {
        $.get('/acciones/edit/' + id, function(response) {
            if (response.status === 'success') {
                $('#hddIdAccion').val(response.data.clvacciones);
                $('#descripcionAccion').val(response.data.descripcion);
                $('#btnRegisterAccion').hide();
                $('#btnUpdateAccion').show();
                $('#modalEditAccion').modal('show');
            } else {
                Swal.fire('Error', response.msg, 'error');
            }
        });
    }

    function eliminarAccion(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/acciones/delete/' + id,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Eliminado', response.msg, 'success');
                            actualizarDatatableAcciones();
                        } else {
                            Swal.fire('Error', response.msg || 'No se pudo eliminar.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'Ocurrió un error al eliminar.', 'error');
                    }
                });
            }
        });
    }


    $(document).ready(function() {
        tblAcciones = $('#tblAcciones').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("acciones.data") }}',
                data: { activo: 'S' }
            },
            columns: [
                // {
                //     data: null,
                //     render: function (data, type, row, meta) {
                //         return meta.row + 1;
                //     }
                // },
                { data: 'clvacciones' },
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
                            <button class="btn btn-sm btn-warning" onclick="getAccionData(${data.clvacciones})">Consultar</button>
                            <button class="btn btn-sm btn-danger" onclick="toggleAccion(${data.clvacciones}, 'N')">Desactivar</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarAccion(${data.clvacciones})">Eliminar</button>
                        `;
                    }
                }
            ]
        });

        tblAccionesInactivas = $('#tblAccionesInactivas').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("acciones.data") }}',
                data: { activo: 'N' }
            },
            columns: [
                // {
                //     data: null,
                //     render: function (data, type, row, meta) {
                //         return meta.row + 1;
                //     }
                // },
                { data: 'clvacciones' },
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
                            <button class="btn btn-sm btn-success" onclick="toggleAccion(${data.clvacciones}, 'S')">Activar</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarAccion(${data.clvacciones})">Eliminar</button>
                        `;
                    }
                }
            ]
        });


        $('#btnRegisterPermiso').on('click', function () {
            const id = $('#hddIdPermiso').val().trim();
            const descripcion = $('#descripcionPermiso').val().trim();

            if (!descripcion) {
                Swal.fire('Error', 'La descripción es obligatoria.', 'error');
                return;
            }

            const formData = {
                descripcion: descripcion,
                _token: '{{ csrf_token() }}'
            };

            if (id !== '') {
                // Se trata de una edición, llama al update
                $.ajax({
                    url: '/permisos/update/' + id,
                    method: 'PUT',
                    data: formData,
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire('Éxito', response.msg, 'success');
                            $('#modalEditPermiso').modal('hide');
                            $('#formEditPermiso')[0].reset();
                            actualizarDatatablePermisos();
                        } else {
                            Swal.fire('Error', response.msg || 'Ocurrió un error.', 'error');
                        }
                    }
                });
            } else {
                // Registro nuevo
                $.post('/permisos/store', formData, function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.msg, 'success');
                        $('#modalEditPermiso').modal('hide');
                        $('#formEditPermiso')[0].reset();
                        actualizarDatatablePermisos();
                    } else {
                        Swal.fire('Error', response.msg || 'Ocurrió un error.', 'error');
                    }
                });
            }
        });


    });
</script>
