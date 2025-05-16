{{--@extends('layouts.dashboard')--}}

{{--@section('content')--}}
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
                <table id="tblAccionesActivas" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Activo</th>
                        <th>Fecha de Registro</th>
                        <th>Fecha de Actualización</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-lg-12">
                <h3>Acciones Inactivas</h3>
                <table id="tblAccionesInactivas" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Activo</th>
                        <th>Fecha de Registro</th>
                        <th>Fecha de Actualización</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <!-- MODAL ACCIÓN -->
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
                            <label for="descripcion">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion">
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
{{--@endsection--}}

{{--@push('scripts')--}}
    <script>
        let tblAccionesActivas, tblAccionesInactivas;

        function abrirModalAccion() {
            $('#formEditAccion')[0].reset();
            $('#hddIdAccion').val('');
            $('#btnRegisterAccion').show();
            $('#btnUpdateAccion').hide();
            $('#modalEditAccion').modal('show');
        }

        function actualizarDatatablesAcciones() {
            tblAccionesActivas.ajax.reload(null, false);
            tblAccionesInactivas.ajax.reload(null, false);
        }

        function toggleAccion(id, status) {
            $.ajax({
                url: '/acciones/toggle',
                method: 'POST',
                data: {
                    id: id,
                    activo: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire('Éxito', response.msg, 'success');
                    actualizarDatatablesAcciones();
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al cambiar el estado de la acción', 'error');
                }
            });
        }

        function getAccionData(id) {
            $.ajax({
                url: '/acciones/edit/' + id,
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#hddIdAccion').val(response.data.clvacciones);
                        $('#descripcion').val(response.data.descripcion);
                        $('#btnRegisterAccion').hide();
                        $('#btnUpdateAccion').show();
                        $('#modalEditAccion').modal('show');
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al obtener los datos', 'error');
                }
            });
        }

        $(document).ready(function () {
            tblAccionesActivas = $('#tblAccionesActivas').DataTable({
                processing: true,
                serverSide: true,
                language: configDatatableSpanish,
                ajax: {
                    url: '{{ route("acciones.data") }}',
                    data: { activo: 'S' }
                },
                columns: [
                    { data: 'clvacciones' },
                    { data: 'descripcion' },
                    { data: 'activo' },
                    { data: 'fecharegistro' },
                    { data: 'fechaactualizacion' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-sm btn-warning" onclick="getAccionData(${row.clvacciones})">Consultar</button>
                            <button class="btn btn-sm btn-danger" onclick="toggleAccion(${row.clvacciones}, 'N')">Desactivar</button>
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
                    { data: 'clvacciones' },
                    { data: 'descripcion' },
                    { data: 'activo' },
                    { data: 'fecharegistro' },
                    { data: 'fechaactualizacion' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-sm btn-success" onclick="toggleAccion(${row.clvacciones}, 'S')">Activar</button>
                        `;
                        }
                    }
                ]
            });

            // Registrar
            $('#btnRegisterAccion').on('click', function () {
                const descripcion = $('#descripcion').val();

                if (!descripcion) {
                    Swal.fire('Error', 'La descripción es obligatoria', 'error');
                    return;
                }

                $.ajax({
                    url: '/acciones/create',
                    method: 'POST',
                    data: {
                        descripcion: descripcion,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire('Éxito', response.msg, 'success');
                        $('#modalEditAccion').modal('hide');
                        $('#formEditAccion')[0].reset();
                        actualizarDatatablesAcciones();
                    },
                    error: function () {
                        Swal.fire('Error', 'Error al guardar la acción', 'error');
                    }
                });
            });

            // Actualizar
            $('#btnUpdateAccion').on('click', function () {
                const id = $('#hddIdAccion').val();
                const descripcion = $('#descripcion').val();

                if (!descripcion) {
                    Swal.fire('Error', 'La descripción es obligatoria', 'error');
                    return;
                }

                $.ajax({
                    url: '/acciones/update',
                    method: 'POST',
                    data: {
                        id: id,
                        descripcion: descripcion,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire('Éxito', response.msg, 'success');
                        $('#modalEditAccion').modal('hide');
                        $('#formEditAccion')[0].reset();
                        actualizarDatatablesAcciones();
                    },
                    error: function () {
                        Swal.fire('Error', 'Error al actualizar la acción', 'error');
                    }
                });
            });
        });
    </script>
{{--@endpush--}}
