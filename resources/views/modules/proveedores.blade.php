{{-- resources/views/modules/proveedores.blade.php --}}

@extends('layouts.dashboard')

@section('content')
    <section class="content-header">
        <h1>Proveedores</h1>
        <small>Gestión de proveedores</small>
    </section>

    <section class="content">
        <div>
            <button class="btn btn-primary" id="btnAddProveedor" onclick="abrirModalProveedor()">Agregar Proveedor</button>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <h3>Proveedores Activos</h3>
                <table id="tblProveedoresActivos" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-lg-12">
                <h3>Proveedores Inactivos</h3>
                <table id="tblProveedoresInactivos" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal Proveedor -->
    <div class="modal fade" id="modalEditProveedor" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formEditProveedor">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        <h4 class="modal-title">Proveedor</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="hddIdProveedor" name="hddIdProveedor">
                        <div class="form-group">
                            <label for="nombreProveedor">Nombre</label>
                            <input type="text" class="form-control" id="nombreProveedor" name="nombreProveedor">
                        </div>
                        <div class="form-group">
                            <label for="contactoProveedor">Contacto</label>
                            <input type="text" class="form-control" id="contactoProveedor" name="contactoProveedor">
                        </div>
                        <div class="form-group">
                            <label for="telefonoProveedor">Teléfono</label>
                            <input type="text" class="form-control" id="telefonoProveedor" name="telefonoProveedor">
                        </div>
                        <div class="form-group">
                            <label for="emailProveedor">Email</label>
                            <input type="email" class="form-control" id="emailProveedor" name="emailProveedor">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button id="btnRegisterProveedor" type="button" class="btn btn-primary">Guardar</button>
                        <button id="btnUpdateProveedor" type="button" class="btn btn-primary" style="display: none">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function abrirModalProveedor() {
            $('#formEditProveedor')[0].reset(); // Limpia los campos
            $('#btnRegisterProveedor').show(); // Muestra botón guardar
            $('#btnUpdateProveedor').hide();   // Oculta botón actualizar
            $('#modalEditProveedor').modal('show'); // Abre el modal
        }

        function actualizarDatatablesProveedores() {
            tblProveedoresActivos.ajax.reload(null, false);
            tblProveedoresInactivos.ajax.reload(null, false);
        }

        function toggleProveedorActive(id, status) {
            $.ajax({
                url: '/proveedores/toggle',
                method: 'POST',
                data: {
                    id: id,
                    activo: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.msg, 'success');
                        actualizarDatatablesProveedores();
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al cambiar el estado del proveedor', 'error');
                }
            });
        }

        function getProveedorData(id) {
            $.ajax({
                url: '/proveedores/edit/' + id,
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#hddIdProveedor').val(response.data.id);
                        $('#nombreProveedor').val(response.data.nombre);
                        $('#contactoProveedor').val(response.data.contacto);
                        $('#telefonoProveedor').val(response.data.telefono);
                        $('#emailProveedor').val(response.data.email);

                        $('#btnRegisterProveedor').hide(); // Oculta el botón "Guardar"
                        $('#btnUpdateProveedor').show();   // Muestra el botón "Actualizar"
                        $('#modalEditProveedor').modal('show');
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al obtener los datos del proveedor', 'error');
                }
            });
        }

        $(document).ready(function() {
            tblProveedoresActivos = $('#tblProveedoresActivos').DataTable({
                processing: true,
                serverSide: true,
                language: configDatatableSpanish,
                ajax: {
                    url: '{{ route("proveedores.data") }}',
                    data: { activo: 'S' }
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre' },
                    { data: 'contacto' },
                    { data: 'telefono' },
                    { data: 'email' },
                    { data: 'activo' },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-sm btn-warning" onclick="getProveedorData(${row.id})">Consultar</button>
                            <button class="btn btn-sm btn-danger" onclick="toggleProveedorActive(${row.id}, 'N')">Desactivar</button>
                        `;
                        }
                    }
                ]
            });

            tblProveedoresInactivos = $('#tblProveedoresInactivos').DataTable({
                processing: true,
                serverSide: true,
                language: configDatatableSpanish,
                ajax: {
                    url: '{{ route("proveedores.data") }}',
                    data: { activo: 'N' }
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre' },
                    { data: 'contacto' },
                    { data: 'telefono' },
                    { data: 'email' },
                    { data: 'activo' },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-sm btn-success" onclick="toggleProveedorActive(${row.id}, 'S')">Activar</button>
                        `;
                        }
                    }
                ]
            });
        });

        // Registrar nuevo proveedor
        $('#btnRegisterProveedor').on('click', function () {
            const nombre = $('#nombreProveedor').val();
            const contacto = $('#contactoProveedor').val();
            const telefono = $('#telefonoProveedor').val();
            const email = $('#emailProveedor').val();

            if (!nombre || !contacto || !telefono || !email) {
                Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
                return;
            }

            $.ajax({
                url: '/proveedores/create',
                method: 'POST',
                data: {
                    nombre: nombre,
                    contacto: contacto,
                    telefono: telefono,
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.msg, 'success');
                        $('#modalEditProveedor').modal('hide');
                        $('#formEditProveedor')[0].reset();
                        actualizarDatatablesProveedores();
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Ocurrió un error al guardar el proveedor', 'error');
                }
            });
        });

        // Actualizar proveedor
        $('#btnUpdateProveedor').on('click', function () {
            const id = $('#hddIdProveedor').val();
            const nombre = $('#nombreProveedor').val();
            const contacto = $('#contactoProveedor').val();
            const telefono = $('#telefonoProveedor').val();
            const email = $('#emailProveedor').val();

            if (!nombre || !contacto || !telefono || !email) {
                Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
                return;
            }

            $.ajax({
                url: '/proveedores/update',
                method: 'POST',
                data: {
                    id: id,
                    nombre: nombre,
                    contacto: contacto,
                    telefono: telefono,
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.msg, 'success');
                        $('#modalEditProveedor').modal('hide');
                        $('#formEditProveedor')[0].reset();
                        actualizarDatatablesProveedores();
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al actualizar el proveedor', 'error');
                }
            });
        });
    </script>
@endpush
