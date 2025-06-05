<!-- Encabezado de la página -->
<div class="content-header">
    <h1>Materias</h1>
</div>

<!-- Contenido principal -->
<div class="content">
    <ul class="nav nav-tabs" id="materiaTabs">
        <li class="active"><a href="#activos" data-toggle="tab">Activos</a></li>
        <li><a href="#inactivos" data-toggle="tab">Inactivos</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active p-3" id="activos">
            <button class="btn btn-primary my-3" id="btnAgregar"><i class="fa fa-plus"></i> Nueva Materia</button>
            <table class="table table-bordered" id="tablaActivos" style="width: 100%">
                <thead>
                <tr>
                    <th>CLV</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="tab-pane p-3" id="inactivos">
            <table class="table table-bordered" id="tablaInactivos" style="width: 100%">
                <thead>
                <tr>
                    <th>CLV</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal para formulario de Materia -->
<div class="modal fade" id="modalMateria" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form id="formMateria">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Materia</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idmateria">
                    <div class="form-group">
                        <label for="clvmateria">Clave</label>
                        <input type="text" id="clvmateria" name="clvmateria" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tipomateria">Tipo</label>
                        <select id="tipomateria" name="tipomateria" class="form-control" required>
                            <option value="">Selecciona un tipo</option>
                            <option value="Civil">Civil</option>
                            <option value="Mercantil">Mercantil</option>
                            <option value="Penal">Penal</option>
                            <option value="Laboral">Laboral</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <input type="text" id="descripcion" name="descripcion" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Estilos adicionales -->
<style>
    .tab-pane {
        padding: 20px;
    }
    .btn {
        margin: 0 5px 5px 0;
    }
    .modal-body .form-group {
        margin-bottom: 15px;
    }
</style>

<!-- Script -->
<script>
    $(function () {
        const configIdiomaEsp = {
            lengthMenu: "Mostrar _MENU_ registros",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "Buscar:",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            },
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            emptyTable: "No hay datos disponibles en la tabla"
        };

        const tablaActivos = $('#tablaActivos').DataTable({
            language: configIdiomaEsp,
            ajax: {
                url: '{{ route("materias.data") }}',
                data: { activo: 'S' }
            },
            columns: [
                { data: 'clvmateria' },
                { data: 'tipomateria' },
                { data: 'descripcion' },
                {
                    data: 'idmateria',
                    render: function (data) {
                        return `
                            <button class="btn btn-sm btn-warning btn-editar" data-id="${data}"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger btn-toggle" data-id="${data}"><i class="fa fa-times"></i></button>
                        `;
                    }
                }
            ]
        });

        const tablaInactivos = $('#tablaInactivos').DataTable({
            language: configIdiomaEsp,
            ajax: '{{ route("materias.data") }}?activo=N',
            columns: [
                { data: 'clvmateria' },
                { data: 'tipomateria' },
                { data: 'descripcion' },
                {
                    data: 'idmateria',
                    render: function (data) {
                        return `
                            <button class="btn btn-sm btn-success btn-toggle" data-id="${data}"><i class="fa fa-check"></i></button>
                        `;
                    }
                }
            ]
        });

        $('#btnAgregar').click(() => {
            $('#formMateria')[0].reset();
            $('#idmateria').val('');
            $('#modalMateria').modal('show');
        });

        $('#formMateria').submit(function (e) {
            e.preventDefault();
            const id = $('#idmateria').val();
            const isEdit = !!id;
            const url = isEdit ? `/materias/update/${id}` : '{{ route("materias.store") }}';

            const formData = $(this).serializeArray();
            formData.push({ name: '_token', value: '{{ csrf_token() }}' });
            if (isEdit) {
                formData.push({ name: '_method', value: 'PUT' });
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                success: function (res) {
                    if (res.status === 'success') {
                        $('#modalMateria').modal('hide');
                        tablaActivos.ajax.reload();
                        tablaInactivos.ajax.reload();
                        Swal.fire('Listo', res.msg, 'success');
                    } else {
                        Swal.fire('Error', res.msg, 'error');
                    }
                }
            });
        });

        $(document).on('click', '.btn-editar', function () {
            const id = $(this).data('id');
            $.get(`/materias/${id}/edit`, function (res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#idmateria').val(d.idmateria);
                    $('#clvmateria').val(d.clvmateria);
                    $('#tipomateria').val(d.tipomateria);
                    $('#descripcion').val(d.descripcion);
                    $('#modalMateria').modal('show');
                } else {
                    Swal.fire('Error', res.msg, 'error');
                }
            });
        });

        $(document).on('click', '.btn-toggle', function () {
            const id = $(this).data('id');
            $.post(`/materias/toggle/${id}`, {_token: '{{ csrf_token() }}'}, function (res) {
                if (res.status === 'success') {
                    tablaActivos.ajax.reload();
                    tablaInactivos.ajax.reload();
                    Swal.fire('Listo', res.msg, 'success');
                } else {
                    Swal.fire('Error', res.msg, 'error');
                }
            });
        });
    });
</script>

