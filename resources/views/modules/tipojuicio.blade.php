<section class="content-header">
    <h1>
        Tipo de Juicio
        <small>Gestión de tipos de juicio</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active">Tipo de Juicio</li>
    </ol>
</section>

<section class="content">
    <div>
        <button class="btn btn-primary" onclick="abrirModalTipoJuicio()">Agregar Tipo de Juicio</button>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <h3>Tipos de Juicio Activos</h3>
            <table id="tblTipoJuicio" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Clave</th>
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
            <h3>Tipos de Juicio Inactivos</h3>
            <table id="tblTipoJuicioInactivos" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Clave</th>
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

<!-- MODAL TIPO JUICIO -->
<div class="modal fade" id="modalEditTipoJuicio" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEditTipoJuicio">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Tipo de Juicio</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hddIdJuicio" name="id">
                    <div class="form-group">
                        <label for="clvjuicio">Clave</label>
                        <input type="text" class="form-control" id="clvjuicio" name="clvjuicio">
                    </div>
                    <div class="form-group">
                        <label for="descripcionJuicio">Descripción</label>
                        <input type="text" class="form-control" id="descripcionJuicio" name="descripcion">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button id="btnRegisterTipoJuicio" type="button" class="btn btn-primary">Guardar</button>
                    <button id="btnUpdateTipoJuicio" type="button" class="btn btn-primary" style="display: none">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function abrirModalTipoJuicio() {
        $('#formEditTipoJuicio')[0].reset();
        $('#btnRegisterTipoJuicio').show();
        $('#btnUpdateTipoJuicio').hide();
        $('#modalEditTipoJuicio').modal('show');
    }

    function actualizarDatatableTipoJuicio() {
        tblTipoJuicio.ajax.reload(null, false);
        tblTipoJuicioInactivos.ajax.reload(null, false);
    }

    function toggleTipoJuicio(id, status) {
        $.post(`/tipojuicio/toggle/${id}`, {
            activo: status,
            _token: '{{ csrf_token() }}'
        }, function (response) {
            if (response.status === 'success') {
                Swal.fire('Éxito', response.msg, 'success');
                actualizarDatatableTipoJuicio();
            } else {
                Swal.fire('Error', response.msg, 'error');
            }
        });
    }

    function getTipoJuicio(id) {
        $.get(`/tipojuicio/${id}/edit`, function (response) {
            if (response.status === 'success') {
                $('#hddIdJuicio').val(response.data.idjuicio);
                $('#clvjuicio').val(response.data.clvjuicio);
                $('#descripcionJuicio').val(response.data.descripcion);
                $('#btnRegisterTipoJuicio').hide();
                $('#btnUpdateTipoJuicio').show();
                $('#modalEditTipoJuicio').modal('show');
            } else {
                Swal.fire('Error', response.msg, 'error');
            }
        });
    }

    $(document).ready(function () {
        tblTipoJuicio = $('#tblTipoJuicio').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("tipojuicio.data", "S") }}'
            },
            columns: [
                { data: 'idjuicio' },
                { data: 'clvjuicio' },
                { data: 'descripcion' },
                { data: 'activo' },
                { data: 'fecharegistro' },
                { data: 'fechaactualizacion' },
                {
                    data: null,
                    orderable: false,
                    render: function (data) {
                        return `
                            <button class="btn btn-sm btn-warning" onclick="getTipoJuicio(${data.idjuicio})">Editar</button>
                            <button class="btn btn-sm btn-danger" onclick="toggleTipoJuicio(${data.idjuicio}, 'N')">Desactivar</button>
                        `;
                    }
                }
            ]
        });

        tblTipoJuicioInactivos = $('#tblTipoJuicioInactivos').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("tipojuicio.data", "N") }}'
            },
            columns: [
                { data: 'idjuicio' },
                { data: 'clvjuicio' },
                { data: 'descripcion' },
                { data: 'activo' },
                { data: 'fecharegistro' },
                { data: 'fechaactualizacion' },
                {
                    data: null,
                    orderable: false,
                    render: function (data) {
                        return `<button class="btn btn-sm btn-success" onclick="toggleTipoJuicio(${data.idjuicio}, 'S')">Activar</button>`;
                    }
                }
            ]
        });

        $('#btnRegisterTipoJuicio').on('click', function () {
            const formData = {
                clvjuicio: $('#clvjuicio').val().trim(),
                descripcion: $('#descripcionJuicio').val().trim(),
                _token: '{{ csrf_token() }}'
            };

            $.post('{{ route("tipojuicio.store") }}', formData, function (response) {
                if (response.status === 'success') {
                    Swal.fire('Éxito', response.msg, 'success');
                    $('#modalEditTipoJuicio').modal('hide');
                    $('#formEditTipoJuicio')[0].reset();
                    actualizarDatatableTipoJuicio();
                } else {
                    Swal.fire('Error', response.msg || 'Ocurrió un error.', 'error');
                }
            });
        });

        $('#btnUpdateTipoJuicio').on('click', function () {
            const id = $('#hddIdJuicio').val();
            const formData = {
                clvjuicio: $('#clvjuicio').val().trim(),
                descripcion: $('#descripcionJuicio').val().trim(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: `/tipojuicio/update/${id}`,
                method: 'PUT',
                data: formData,
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.msg, 'success');
                        $('#modalEditTipoJuicio').modal('hide');
                        $('#formEditTipoJuicio')[0].reset();
                        actualizarDatatableTipoJuicio();
                    } else {
                        Swal.fire('Error', response.msg || 'Ocurrió un error.', 'error');
                    }
                }
            });
        });
    });
</script>
