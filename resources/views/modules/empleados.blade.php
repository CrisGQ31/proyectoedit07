<section class="content-header">
    <h1>
        Empleados
        <small>Gestión de empleados</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active">Empleados</li>
    </ol>
</section>

<section class="content">
    <div>
        <button class="btn btn-primary" id="btnAddEmpleado" onclick="abrirModalEmpleado()">Agregar Empleado</button>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <h3>Empleados Activos</h3>
            <table id="tblEmpleadosActivos" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Activo</th>
                    <th>Fecha Registro</th>
                    <th>Fecha Actualización</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-lg-12">
            <h3>Empleados Inactivos</h3>
            <table id="tblEmpleadosInactivos" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
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

<!-- MODAL EMPLEADO -->
<div class="modal fade" id="modalEditEmpleado" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEditEmpleado">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Empleado</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hddIdEmpleado" name="hddIdEmpleado">
                    <div class="form-group">
                        <label for="nombreEmpleado">Nombre</label>
                        <input type="text" class="form-control" id="nombreEmpleado" name="nombreEmpleado">
                    </div>
                    <div class="form-group">
                        <label for="apellidoPaternoEmpleado">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellidoPaternoEmpleado" name="apellidoPaternoEmpleado">
                    </div>
                    <div class="form-group">
                        <label for="apellidoMaternoEmpleado">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellidoMaternoEmpleado" name="apellidoMaternoEmpleado">
                    </div>
                    <div class="form-group">
                        <label for="telefonoEmpleado">Teléfono</label>
                        <input type="text" class="form-control" id="telefonoEmpleado" name="telefonoEmpleado">
                    </div>
                    <div class="form-group">
                        <label for="correoEmpleado">Correo</label>
                        <input type="email" class="form-control" id="correoEmpleado" name="correoEmpleado">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button id="btnRegisterEmpleado" type="button" class="btn btn-primary">Guardar</button>
                    <button id="btnUpdateEmpleado" type="button" class="btn btn-primary" style="display: none">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--@push('scripts')--}}
<script>
    function abrirModalEmpleado() {
        $('#formEditEmpleado')[0].reset();
        $('#btnRegisterEmpleado').show();
        $('#btnUpdateEmpleado').hide();
        $('#modalEditEmpleado').modal('show');
    }

    function actualizarDatatablesEmpleados() {
        tblEmpleadosActivos.ajax.reload(null, false);
        tblEmpleadosInactivos.ajax.reload(null, false);
    }

    function toggleEmpleadoActive(id, status) {
        $.ajax({
            url: '/empleados/toggle',
            method: 'POST',
            data: {
                id: id,
                activo: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire('Éxito', response.msg, 'success');
                    actualizarDatatablesEmpleados();
                } else {
                    Swal.fire('Error', response.msg, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Ocurrió un error al cambiar el estado del empleado', 'error');
            }
        });
    }

    function getEmpleadoData(id) {
        $.ajax({
            url: '/empleados/edit/' + id,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    $('#hddIdEmpleado').val(response.data.idempleado);
                    $('#nombreEmpleado').val(response.data.nombre);
                    $('#apellidoPaternoEmpleado').val(response.data.apellidopaterno);
                    $('#apellidoMaternoEmpleado').val(response.data.apellidomaterno);
                    $('#telefonoEmpleado').val(response.data.telefono);
                    $('#correoEmpleado').val(response.data.correo);

                    $('#btnRegisterEmpleado').hide();
                    $('#btnUpdateEmpleado').show();
                    $('#modalEditEmpleado').modal('show');
                } else {
                    Swal.fire('Error', response.msg, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Ocurrió un error al obtener los datos del empleado', 'error');
            }
        });
    }

    $(document).ready(function() {
        tblEmpleadosActivos = $('#tblEmpleadosActivos').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("empleados.data") }}',
                data: { activo: 'S' }
            },
            columns: [
                { data: 'idempleado' },
                { data: null, render: data => `${data.nombre} ${data.apellidopaterno} ${data.apellidomaterno}` },
                { data: 'telefono' },
                { data: 'correo' },
                { data: 'activo' },
                { data: 'fecharegistro' },
                { data: 'fechaactualizacion' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `
            <button class="btn btn-sm btn-warning" onclick="getEmpleadoData(${data.idempleado})">Consultar</button>
            <button class="btn btn-sm btn-danger" onclick="toggleEmpleadoActive(${data.idempleado}, 'N')">Desactivar</button>
        `;
                    }
                }
            ]

        });

        tblEmpleadosInactivos = $('#tblEmpleadosInactivos').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route("empleados.data") }}',
                data: { activo: 'N' }
            },
            columns: [
                { data: 'idempleado' },
                { data: null, render: data => `${data.nombre} ${data.apellidopaterno} ${data.apellidomaterno}` },
                { data: 'telefono' },
                { data: 'correo' },
                { data: 'activo' },
                { data: 'fecharegistro' },
                { data: 'fechaactualizacion' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `<button class="btn btn-sm btn-success" onclick="toggleEmpleadoActive(${data.idempleado}, 'S')">Activar</button>`;
                    }
                }
            ]

        });

        $(document).on('click', '#btnAddEmpleado', function () {
            abrirModalEmpleado();
        });
    });

    $('#btnRegisterEmpleado').on('click', function () {
        const nombre = $('#nombreEmpleado').val();
        const apellidopaterno = $('#apellidoPaternoEmpleado').val();
        const apellidomaterno = $('#apellidoMaternoEmpleado').val();
        const telefono = $('#telefonoEmpleado').val();
        const correo = $('#correoEmpleado').val();

        if (!nombre || !apellidopaterno || !apellidomaterno) {
            Swal.fire('Error', 'Nombre y apellidos son obligatorios', 'error');
            return;
        }

        $.ajax({
            url: '/empleados/store',
            method: 'POST',
            data: {
                nombre,
                apellidopaterno,
                apellidomaterno,
                telefono,
                correo,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire('Éxito', response.msg, 'success');
                    $('#modalEditEmpleado').modal('hide');
                    $('#formEditEmpleado')[0].reset();
                    actualizarDatatablesEmpleados();
                } else {
                    Swal.fire('Error', response.msg, 'error');
                }
            },

            error: function(xhr) {
                console.error(xhr.responseText);

                let msg = 'Ocurrió un error al guardar el empleado';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                Swal.fire('Error', msg, 'error');
            }
            // error: function(xhr) {
            //     console.error(xhr.responseText);
            //     Swal.fire('Error', 'Ocurrió un error al guardar el empleado', 'error');
            // }
        });
    });

    $('#btnUpdateEmpleado').on('click', function () {
        const id = $('#hddIdEmpleado').val();
        const nombre = $('#nombreEmpleado').val();
        const apellidopaterno = $('#apellidoPaternoEmpleado').val();
        const apellidomaterno = $('#apellidoMaternoEmpleado').val();
        const telefono = $('#telefonoEmpleado').val();
        const correo = $('#correoEmpleado').val();

        if (!nombre || !apellidopaterno || !apellidomaterno) {
            Swal.fire('Error', 'Nombre y apellidos son obligatorios', 'error');
            return;
        }

        $.ajax({
            url: '/empleados/update',
            method: 'POST',
            data: {
                id,
                nombre,
                apellidopaterno,
                apellidomaterno,
                telefono,
                correo,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire('Éxito', response.msg, 'success');
                    $('#modalEditEmpleado').modal('hide');
                    $('#formEditEmpleado')[0].reset();
                    actualizarDatatablesEmpleados();
                } else {
                    Swal.fire('Error', response.msg, 'error');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Ocurrió un error al actualizar el empleado', 'error');
            }
        });
    });
</script>

{{--@endpush--}}
