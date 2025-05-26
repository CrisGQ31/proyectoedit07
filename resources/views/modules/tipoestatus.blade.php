<section class="content-header">
    <h1>Tipo de Status <small>Gestión de tipos de status</small></h1>
    <ol class="breadcrumb"><li class="active">Tipo de Status</li></ol>
</section>

<section class="content">
    <button class="btn btn-primary" onclick="abrirModalEstatus()">Agregar Tipo de Status</button>

    <div class="row mt-3">
        <div class="col-lg-12">
            <h3>Activos</h3>
            <table id="tblTipoEstatus" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Observaciones</th>
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
            <h3>Inactivos</h3>
            <table id="tblTipoEstatusInactivos" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Observaciones</th>
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

<!-- MODAL -->
<div class="modal fade" id="modalTipoEstatus" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formTipoEstatus">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Tipo de Status</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hddIdTipoEstatus" name="idtipoestatus">
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion">
                    </div>
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button id="btnGuardarEstatus" type="button" class="btn btn-primary">Guardar</button>
                    <button id="btnActualizarEstatus" type="button" class="btn btn-primary" style="display:none;">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let tblTipoEstatus, tblTipoEstatusInactivos;

    function abrirModalEstatus() {
        $('#formTipoEstatus')[0].reset();
        $('#btnGuardarEstatus').show();
        $('#btnActualizarEstatus').hide();
        $('#modalTipoEstatus').modal('show');
    }

    function actualizarTabla() {
        tblTipoEstatus.ajax.reload(null, false);
        tblTipoEstatusInactivos.ajax.reload(null, false);
    }

    function getEstatus(id) {
        $.get('/tipoestatus/' + id + '/edit', function(res) {
            if (res.status === 'success') {
                const d = res.data;
                $('#hddIdTipoEstatus').val(d.idtipoestatus);
                $('#descripcion').val(d.descripcion);
                $('#observaciones').val(d.observaciones);
                $('#btnGuardarEstatus').hide();
                $('#btnActualizarEstatus').show();
                $('#modalTipoEstatus').modal('show');
            } else {
                Swal.fire('Error', res.msg, 'error');
            }
        });
    }

    function toggleEstatus(id, estado) {
        $.post('/tipoestatus/toggle/' + id, {
            activo: estado,
            _token: '{{ csrf_token() }}'
        }, function(res) {
            if (res.status === 'success') {
                Swal.fire('Éxito', res.msg, 'success');
                actualizarTabla();
            } else {
                Swal.fire('Error', res.msg, 'error');
            }
        });
    }

    $(document).ready(function () {
        tblTipoEstatus = $('#tblTipoEstatus').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("tipoestatus.data") }}',
                data: {activo: 'S'}
            },
            columns: [
                {data: 'idtipoestatus'},
                {data: 'descripcion'},
                {data: 'observaciones'},
                {data: 'activo'},
                {data: 'fecharegistro'},
                {data: 'fechaactualizacion'},
                {
                    data: null,
                    render: function (data) {
                        return `
                            <button class="btn btn-warning btn-sm" onclick="getEstatus(${data.idtipoestatus})">Consultar</button>
                            <button class="btn btn-danger btn-sm" onclick="toggleEstatus(${data.idtipoestatus}, 'N')">Desactivar</button>
                        `;
                    }
                }
            ]
        });

        tblTipoEstatusInactivos = $('#tblTipoEstatusInactivos').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("tipoestatus.data") }}',
                data: {activo: 'N'}
            },
            columns: [
                {data: 'idtipoestatus'},
                {data: 'descripcion'},
                {data: 'observaciones'},
                {data: 'activo'},
                {data: 'fecharegistro'},
                {data: 'fechaactualizacion'},
                {
                    data: null,
                    render: function (data) {
                        return `<button class="btn btn-success btn-sm" onclick="toggleEstatus(${data.idtipoestatus}, 'S')">Activar</button>`;
                    }
                }
            ]
        });

        $('#btnGuardarEstatus').on('click', function () {
            const formData = {
                descripcion: $('#descripcion').val(),
                observaciones: $('#observaciones').val(),
                _token: '{{ csrf_token() }}'
            };

            $.post('/tipoestatus/store', formData, function (res) {
                if (res.status === 'success') {
                    Swal.fire('Éxito', res.msg, 'success');
                    $('#modalTipoEstatus').modal('hide');
                    actualizarTabla();
                } else {
                    Swal.fire('Error', res.msg, 'error');
                }
            });
        });

        $('#btnActualizarEstatus').on('click', function () {
            const id = $('#hddIdTipoEstatus').val();
            const formData = {
                descripcion: $('#descripcion').val(),
                observaciones: $('#observaciones').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '/tipoestatus/update/' + id,
                method: 'PUT',
                data: formData,
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire('Éxito', res.msg, 'success');
                        $('#modalTipoEstatus').modal('hide');
                        actualizarTabla();
                    } else {
                        Swal.fire('Error', res.msg, 'error');
                    }
                }
            });
        });
    });
</script>
