{{--@extends('layouts.dashboard')--}}

{{--@section('content')--}}
    <section class="content-header">
        <h1>
            Productos
            <small>Gestión de productos</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">Productos</li>
        </ol>
    </section>

    <section class="content">
        <div>
            <button class="btn btn-primary" id="btnAddProduct" onclick="abrirModalProducto()">Agregar Producto</button>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <h3>Productos Activos</h3>
                <table id="tblProductosActivos" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
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
                <h3>Productos Inactivos</h3>
                <table id="tblProductosInactivos" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <!-- MODAL PRODUCTO -->
    <div class="modal fade" id="modalEditProduct" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formEditProduct">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        <h4 class="modal-title">Producto</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="hddIdProduct" name="hddIdProduct">
                        <div class="form-group">
                            <label for="nameProduct">Nombre</label>
                            <input type="text" class="form-control" id="nameProduct" name="nameProduct">
                        </div>
                        <div class="form-group">
                            <label for="priceProduct">Precio</label>
                            <input type="number" class="form-control" id="priceProduct" name="priceProduct" step="0.01">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button id="btnRegisterProduct" type="button" class="btn btn-primary">Guardar</button>
                        <button id="btnUpdateProduct" type="button" class="btn btn-primary" style="display: none">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

{{--@endsection--}}

{{--@push('scripts')--}}
    <script>
        // Funciones de validación, registrar, actualizar, activar/desactivar, abrir modal, datatables...

        function abrirModalProducto() {
            $('#formEditProduct')[0].reset(); // Limpia los campos
            $('#btnRegisterProduct').show(); // Muestra botón guardar
            $('#btnUpdateProduct').hide();   // Oculta botón actualizar
            $('#modalEditProduct').modal('show'); // Abre el modal
        }

        function actualizarDatatablesProductos() {
            tblProductosActivos.ajax.reload(null, false);
            tblProductosInactivos.ajax.reload(null, false);
        }

        function toggleProductActive(id, status) {
            $.ajax({
                url: '/products/toggle',
                method: 'POST',
                data: {
                    id: id,
                    activo: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.msg, 'success');
                        actualizarDatatablesProductos();
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al cambiar el estado del producto', 'error');
                }
            });
        }

        function getProductData(id) {
            $.ajax({
                url: '/products/edit/' + id,
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#hddIdProduct').val(response.data.id);
                        $('#nameProduct').val(response.data.nombre);
                        $('#priceProduct').val(response.data.precio);

                        $('#btnRegisterProduct').hide(); // Oculta el botón "Guardar"
                        $('#btnUpdateProduct').show();   // Muestra el botón "Actualizar"
                        $('#modalEditProduct').modal('show');
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al obtener los datos del producto', 'error');
                }
            });
        }

        $(document).ready(function() {
            tblProductosActivos = $('#tblProductosActivos').DataTable({
                processing: true,
                serverSide: true,
                language: configDatatableSpanish,
                ajax: {
                    url: '{{ route("products.data") }}',
                    data: { activo: 'S' }
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre' },
                    { data: 'precio' },
                    { data: 'activo' },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-sm btn-warning" onclick="getProductData(${row.id})">Consultar</button>
                            <button class="btn btn-sm btn-danger" onclick="toggleProductActive(${row.id}, 'N')">Desactivar</button>
                        `;
                        }
                    }
                ]
            });

            tblProductosInactivos = $('#tblProductosInactivos').DataTable({
                processing: true,
                serverSide: true,
                language: configDatatableSpanish,
                ajax: {
                    url: '{{ route("products.data") }}',
                    data: { activo: 'N' }
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre' },
                    { data: 'precio' },
                    { data: 'activo' },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-sm btn-success" onclick="toggleProductActive(${row.id}, 'S')">Activar</button>
                        `;
                        }
                    }
                ]
            });
        });

        // Registrar nuevo producto
        $('#btnRegisterProduct').on('click', function () {
            const nombre = $('#nameProduct').val();
            const precio = $('#priceProduct').val();

            if (!nombre || !precio) {
                Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
                return;
            }

            $.ajax({
                url: '/products/create',
                method: 'POST',
                data: {
                    nombre: nombre,
                    precio: precio,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.msg, 'success');
                        $('#modalEditProduct').modal('hide');
                        $('#formEditProduct')[0].reset();
                        actualizarDatatablesProductos();
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Ocurrió un error al guardar el producto', 'error');
                }
            });
        });

        // Actualizar producto
        $('#btnUpdateProduct').on('click', function () {
            const id = $('#hddIdProduct').val();
            const nombre = $('#nameProduct').val();
            const precio = $('#priceProduct').val();

            if (!nombre || !precio) {
                Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
                return;
            }

            $.ajax({
                url: '/products/update',
                method: 'POST',
                data: {
                    id: id,
                    nombre: nombre,
                    precio: precio,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('Éxito', response.msg, 'success');
                        $('#modalEditProduct').modal('hide');
                        $('#formEditProduct')[0].reset();
                        actualizarDatatablesProductos();
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al actualizar el producto', 'error');
                }
            });
        });
    </script>
{{--@endpush--}}
