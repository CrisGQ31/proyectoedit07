
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
                        <th>#</th>
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
                        <th>#</th>
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
    <div class="modal fade" id="modalEditAccion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formEditAccion">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span>&times;</span></button>
                        <h4 class="modal-title">Acción</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="hddIdAccion" name="id">
                        <div class="form-group">
                            <label for="descripcionAccion">Descripción</label>
                            <input type="text" class="form-control" id="descripcionAccion" name="descripcion">
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

    <!-- CSRF Token meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Configura token CSRF para AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function abrirModalAccion() {
            $('#formEditAccion')[0].reset();
            $('#hddIdAccion').val('');
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
                activo: status
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
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'clvacciones' },
                    { data: 'descripcion' },
                    {
                        data: 'activo',
                        render: function(data) {
                            return data === 'S' ? 'Sí' : 'No';
                        }
                    },
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
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'clvacciones' },
                    { data: 'descripcion' },
                    {
                        data: 'activo',
                        render: function(data) {
                            return data === 'S' ? 'Sí' : 'No';
                        }
                    },
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


            // Evento para registrar nueva acción
            $('#btnRegisterAccion').on('click', function () {
                const descripcion = $('#descripcionAccion').val().trim();

                if (!descripcion) {
                    Swal.fire('Error', 'La descripción es obligatoria.', 'error');
                    return;
                }

                $.post('/acciones/store', { descripcion: descripcion })
                    .done(function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Éxito', response.msg, 'success');
                            $('#modalEditAccion').modal('hide');
                            $('#formEditAccion')[0].reset();
                            actualizarDatatableAcciones();
                        } else {
                            Swal.fire('Error', response.msg, 'error');
                        }
                    }).fail(function() {
                    Swal.fire('Error', 'Error en la conexión.', 'error');
                });
            });

            // Evento para actualizar acción
            $('#btnUpdateAccion').on('click', function () {
                const id = $('#hddIdAccion').val();
                const descripcion = $('#descripcionAccion').val().trim();

                if (!descripcion) {
                    Swal.fire('Error', 'La descripción es obligatoria.', 'error');
                    return;
                }

                $.post('/acciones/update/' + id, { descripcion: descripcion })
                    .done(function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Éxito', response.msg, 'success');
                            $('#modalEditAccion').modal('hide');
                            $('#formEditAccion')[0].reset();
                            actualizarDatatableAcciones();
                        } else {
                            Swal.fire('Error', response.msg, 'error');
                        }
                    }).fail(function() {
                    Swal.fire('Error', 'Error en la conexión.', 'error');
                });
            });
        });
    </script>
