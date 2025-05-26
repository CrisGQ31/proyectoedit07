<!-- resources/views/modules/solicitantes.blade.php -->

<section class="content-header">
    <h1>
        Solicitantes
        <small>Gestión de solicitantes</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active">Solicitantes</li>
    </ol>
</section>

<section class="content">
    <div>
        <button class="btn btn-primary" id="btnAddSolicitante" onclick="abrirModalSolicitante()">Agregar Solicitante</button>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <h3>Solicitantes Activos</h3>
            <table id="tblSolicitantes" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Teléfono</th>
                    <th>RFC</th>
                    <th>CURP</th>
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
            <h3>Solicitantes Inactivos</h3>
            <table id="tblSolicitantesInactivos" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Teléfono</th>
                    <th>RFC</th>
                    <th>CURP</th>
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

<!-- MODAL SOLICITANTE -->
<div class="modal fade" id="modalEditSolicitante" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEditSolicitante">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Solicitante</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hddIdSolicitante" name="idsolicitante">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="nombreSolicitante" name="nombre">
                    </div>
                    <div class="form-group">
                        <label>Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellidoPaternoSolicitante" name="apellidopaterno">
                    </div>
                    <div class="form-group">
                        <label>Apellido Materno</label>
                        <input type="text" class="form-control" id="apellidoMaternoSolicitante" name="apellidomaterno">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" class="form-control" id="telefonoSolicitante" name="telefono">
                    </div>
                    <div class="form-group">
                        <label>RFC</label>
                        <input type="text" class="form-control" id="rfcSolicitante" name="rfc">
                    </div>
                    <div class="form-group">
                        <label>CURP</label>
                        <input type="text" class="form-control" id="curpSolicitante" name="curp">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button id="btnRegisterSolicitante" type="button" class="btn btn-primary">Guardar</button>
                    <button id="btnUpdateSolicitante" type="button" class="btn btn-primary" style="display: none">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function abrirModalSolicitante() {
        $('#formEditSolicitante')[0].reset();
        $('#btnRegisterSolicitante').show();
        $('#btnUpdateSolicitante').hide();
        $('#modalEditSolicitante').modal('show');
    }

    function actualizarDatatableSolicitantes() {
        tblSolicitantes.ajax.reload(null, false);
        tblSolicitantesInactivos.ajax.reload(null, false);
    }

    function toggleSolicitante(id, status) {
        $.post('/solicitantes/toggle', {
            id: id,
            activo: status,
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.status === 'success') {
                Swal.fire('Éxito', response.msg, 'success');
                actualizarDatatableSolicitantes();
            } else {
                Swal.fire('Error', response.msg, 'error');
            }
        });
    }

    function getSolicitanteData(id) {
        $.get('/solicitantes/' + id + '/edit', function(response) {
            if (response.status === 'success') {
                const d = response.data;
                $('#hddIdSolicitante').val(d.idsolicitante);
                $('#nombreSolicitante').val(d.nombre);
                $('#apellidoPaternoSolicitante').val(d.apellidopaterno);
                $('#apellidoMaternoSolicitante').val(d.apellidomaterno);
                $('#telefonoSolicitante').val(d.telefono);
                $('#rfcSolicitante').val(d.rfc);
                $('#curpSolicitante').val(d.curp);
                $('#btnRegisterSolicitante').hide();
                $('#btnUpdateSolicitante').show();
                $('#modalEditSolicitante').modal('show');
            } else {
                Swal.fire('Error', response.msg, 'error');
            }
        });
    }

    $(document).ready(function() {
        tblSolicitantes = $('#tblSolicitantes').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("solicitantes.data") }}',
                data: { activo: 'S' }
            },
            columns: [
                { data: 'idsolicitante' },
                { data: 'nombre' },
                { data: 'apellidopaterno' },
                { data: 'apellidomaterno' },
                { data: 'telefono' },
                { data: 'rfc' },
                { data: 'curp' },
                { data: 'activo' },
                { data: 'fecharegistro' },
                { data: 'fechaactualizacion' },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <button class="btn btn-warning btn-sm" onclick="getSolicitanteData(${data.idsolicitante})">Consultar</button>
                            <button class="btn btn-danger btn-sm" onclick="toggleSolicitante(${data.idsolicitante}, 'N')">Desactivar</button>
                        `;
                    }
                }
            ]
        });

        tblSolicitantesInactivos = $('#tblSolicitantesInactivos').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("solicitantes.data") }}',
                data: { activo: 'N' }
            },
            columns: [
                { data: 'idsolicitante' },
                { data: 'nombre' },
                { data: 'apellidopaterno' },
                { data: 'apellidomaterno' },
                { data: 'telefono' },
                { data: 'rfc' },
                { data: 'curp' },
                { data: 'activo' },
                { data: 'fecharegistro' },
                { data: 'fechaactualizacion' },
                {
                    data: null,
                    render: function(data) {
                        return `<button class="btn btn-success btn-sm" onclick="toggleSolicitante(${data.idsolicitante}, 'S')">Activar</button>`;
                    }
                }
            ]
        });

        $('#btnRegisterSolicitante').on('click', function () {
            const nombre = $('#nombreSolicitante').val().trim();
            const apellidopaterno = $('#apellidoPaternoSolicitante').val().trim();
            const apellidomaterno = $('#apellidoMaternoSolicitante').val().trim();
            const telefono = $('#telefonoSolicitante').val().trim();
            const rfc = $('#rfcSolicitante').val().trim();
            const curp = $('#curpSolicitante').val().trim();

            // ✅ Validaciones antes del envío
            if (!nombre || !apellidopaterno || !apellidomaterno || !telefono || !rfc || !curp) {
                Swal.fire('Error', 'Todos los campos son obligatorios.', 'error');
                return;
            }

            if (curp.length !== 18) {
                Swal.fire('Error', 'La CURP debe tener exactamente 18 caracteres.', 'error');
                return;
            }

            const formData = {
                nombre: nombre,
                apellidopaterno: apellidopaterno,
                apellidomaterno: apellidomaterno,
                telefono: telefono,
                rfc: rfc,
                curp: curp,
                _token: '{{ csrf_token() }}'
            };

            $.post('/solicitantes/store', formData, function (response) {
                if (response.status === 'success') {
                    Swal.fire('Éxito', response.msg, 'success');
                    $('#modalEditSolicitante').modal('hide');
                    $('#formEditSolicitante')[0].reset();
                    actualizarDatatableSolicitantes();
                } else {
                    Swal.fire('Error', response.msg || 'Ocurrió un error.', 'error');
                }
            });
        });

        $('#btnUpdateSolicitante').on('click', function () {
            const id = $('#hddIdSolicitante').val();
            const nombre = $('#nombreSolicitante').val().trim();
            const apellidopaterno = $('#apellidoPaternoSolicitante').val().trim();
            const apellidomaterno = $('#apellidoMaternoSolicitante').val().trim();
            const telefono = $('#telefonoSolicitante').val().trim();
            const rfc = $('#rfcSolicitante').val().trim();
            const curp = $('#curpSolicitante').val().trim();

            // ✅ Validaciones antes del envío
            if (!nombre || !apellidopaterno || !apellidomaterno || !telefono || !rfc || !curp) {
                Swal.fire('Error', 'Todos los campos son obligatorios.', 'error');
                return;
            }

            if (curp.length !== 18) {
                Swal.fire('Error', 'La CURP debe tener exactamente 18 caracteres.', 'error');
                return;
            }

            const formData = {
                nombre: nombre,
                apellidopaterno: apellidopaterno,
                apellidomaterno: apellidomaterno,
                telefono: telefono,
                rfc: rfc,
                curp: curp,
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '/solicitantes/update/' + id,
                method: 'PUT',
                data: formData,
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.msg, 'success');
                        $('#modalEditSolicitante').modal('hide');
                        $('#formEditSolicitante')[0].reset();
                        actualizarDatatableSolicitantes();
                    } else {
                        Swal.fire('Error', response.msg || 'Ocurrió un error.', 'error');
                    }
                }
            });
        });

    });
</script>

