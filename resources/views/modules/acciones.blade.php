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
                    <input type="hidden" id="hddIdAccion" name="hddIdAccion">
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
                        `;
                    }
                }
            ]
        });

        $('#btnRegisterAccion').on('click', function () {
            const descripcion = $('#descripcionAccion').val();
            if (!descripcion) {
                Swal.fire('Error', 'La descripción es obligatoria', 'error');
                return;
            }

            $.post('/acciones/store', {
                descripcion,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                if (response.status === 'success') {
                    Swal.fire('Éxito', response.msg, 'success');
                    $('#modalEditAccion').modal('hide');
                    actualizarDatatableAcciones();
                } else {
                    Swal.fire('Error', response.msg, 'error');
                }
            });
        });

        $('#btnUpdateAccion').on('click', function () {
            const id = $('#hddIdAccion').val();
            const descripcion = $('#descripcionAccion').val();
            if (!descripcion) {
                Swal.fire('Error', 'La descripción es obligatoria', 'error');
                return;
            }

            $.post('/acciones/update', {
                id,
                descripcion,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                if (response.status === 'success') {
                    Swal.fire('Éxito', response.msg, 'success');
                    $('#modalEditAccion').modal('hide');
                    actualizarDatatableAcciones();
                } else {
                    Swal.fire('Error', response.msg, 'error');
                }
            });
        });
    });
</script>
