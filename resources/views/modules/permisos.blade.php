<section class="content-header">
    <h1>
        Permisos
        <small>Gestión de permisos</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active">Permisos</li>
    </ol>
</section>

<section class="content">
    <div>
        <button class="btn btn-primary" onclick="abrirModalPermiso()">Agregar Permiso</button>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <h3>Permisos Activos</h3>
            <table id="tablaPermisosActivos" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Registro</th>
                    <th>Actualización</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-lg-12">
            <h3>Permisos Inactivos</h3>
            <table id="tablaPermisosInactivos" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Registro</th>
                    <th>Actualización</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<!-- MODAL -->
<div class="modal fade" id="modalPermiso" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formPermiso">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Permiso</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idpermiso" name="idpermiso">
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button id="btnGuardarPermiso" type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let tablaPermisosActivos, tablaPermisosInactivos;

    function abrirModalPermiso() {
        $('#formPermiso')[0].reset();
        $('#idpermiso').val('');
        $('#modalPermiso').modal('show');
    }

    function cargarPermiso(id) {
        $.get('/permisos/edit/' + id, function(data) {
            $('#idpermiso').val(data.idpermiso);
            $('#descripcion').val(data.descripcion);
            $('#modalPermiso').modal('show');
        });
    }

    function cambiarEstado(id) {
        $.post('/permisos/toggle', { id, _token: '{{ csrf_token() }}' }, function(resp) {
            Swal.fire('Éxito', resp.message, 'success');
            tablaPermisosActivos.ajax.reload();
            tablaPermisosInactivos.ajax.reload();
        });
    }

    function eliminarPermiso(id) {
        Swal.fire({
            title: '¿Eliminar?',
            text: "Esto eliminará el permiso permanentemente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/permisos/delete/' + id,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(resp) {
                        Swal.fire('Eliminado', resp.message, 'success');
                        tablaPermisosActivos.ajax.reload();
                        tablaPermisosInactivos.ajax.reload();
                    }
                });
            }
        });
    }

    $('#formPermiso').on('submit', function(e) {
        e.preventDefault();
        const datos = $(this).serialize();
        $.post('/permisos/store', datos, function(resp) {
            $('#modalPermiso').modal('hide');
            Swal.fire('Éxito', resp.message, 'success');
            tablaPermisosActivos.ajax.reload();
            tablaPermisosInactivos.ajax.reload();
        }).fail(function(xhr, status, error) {
            let mensaje = 'No se pudo guardar el permiso';


            if (xhr.status === 422 && xhr.responseJSON.errors) {
                let errores = Object.values(xhr.responseJSON.errors).flat().join('\n');
                mensaje = 'Errores de validación:\n' + errores;
            }

            Swal.fire('Error', mensaje, 'error');
            console.error("Error:", error);
            console.error("Status:", status);
            console.error("Respuesta:", xhr.responseText);
        });
    });

    $(document).ready(function() {
        tablaPermisosActivos = $('#tablaPermisosActivos').DataTable({
            ajax: '/permisos/data',
            columns: [
                { data: 'idpermiso' },
                { data: 'descripcion' },
                { data: 'fecharegistro' },
                { data: 'fechaactualizacion' },
                { data: 'acciones', orderable: false, searchable: false }
            ],
            language: configDatatableSpanish,
            rowCallback: function(row, data) {
                if (data.activo === 'Inactivo') {
                    $(row).hide(); // Oculta si es inactivo
                }
            }
        });

        tablaPermisosInactivos = $('#tablaPermisosInactivos').DataTable({
            ajax: '/permisos/data',
            columns: [
                { data: 'idpermiso' },
                { data: 'descripcion' },
                { data: 'fecharegistro' },
                { data: 'fechaactualizacion' },
                { data: 'acciones', orderable: false, searchable: false }
            ],
            language: configDatatableSpanish,
            rowCallback: function(row, data) {
                if (data.activo === 'Activo') {
                    $(row).hide(); // Oculta si es activo
                }
            }
        });
    });
</script>

