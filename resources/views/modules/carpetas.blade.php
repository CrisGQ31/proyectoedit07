<!-- Encabezado -->
<div class="content-header">
    <h1>Carpetas</h1>
</div>

<!-- Contenido principal -->
<div class="content">
    <ul class="nav nav-tabs" id="carpetaTabs">
        <li class="active"><a href="#activos" data-toggle="tab">Activos</a></li>
        <li><a href="#inactivos" data-toggle="tab">Inactivos</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active p-3" id="activos">
            <button class="btn btn-primary my-3" id="btnAgregar"><i class="fa fa-plus"></i> Nueva Carpeta</button>
            <table class="table table-bordered" id="tablaActivos" style="width: 100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Solicitante</th>
                    <th>Materia</th>
                    <th>Tipo de Juicio</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="tab-pane p-3" id="inactivos">
            <table class="table table-bordered" id="tablaInactivos" style="width: 100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Solicitante</th>
                    <th>Materia</th>
                    <th>Tipo de Juicio</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCarpeta" tabindex="-1">
    <div class="modal-dialog">
        <form id="formCarpeta">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Carpeta</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idcarpeta">

                    <div class="form-group">
                        <label for="idsolicitante">Solicitante</label>
                        <select id="idsolicitante" name="idsolicitante" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach ($solicitantes as $s)
                                <option value="{{ $s->idsolicitante }}">{{ $s->nombre }} {{ $s->apellidopaterno }} {{ $s->apellidomaterno }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="idmateria">Materia</label>
                        <select id="idmateria" name="idmateria" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach ($materias as $m)
                                <option value="{{ $m->idmateria }}">{{ $m->tipomateria }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="idjuicio">Tipo de Juicio</label>
                        <select id="idjuicio" name="idjuicio" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach ($juicios as $j)
                                <option value="{{ $j->idjuicio }}">{{ $j->tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sintesis">Síntesis</label>
                        <textarea id="sintesis" name="sintesis" class="form-control" rows="3" placeholder="Escribe una síntesis (opcional)"></textarea>
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

<!-- Script -->
<script>
    $(function () {
        const idioma = {
            lengthMenu: "Mostrar _MENU_ registros",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "Buscar:",
            paginate: {
                first: "Primero", last: "Último",
                next: "Siguiente", previous: "Anterior"
            },
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            emptyTable: "No hay datos disponibles en la tabla"
        };

        const tablaActivos = $('#tablaActivos').DataTable({
            language: idioma,
            ajax: '{{ route("carpetas.data") }}?activo=S',
            columns: [
                { data: 'idcarpeta' },
                { data: 'nombre_solicitante' },
                { data: 'materia' },
                { data: 'tipo_juicio' },
                { data: 'fecharegistro' },
                {
                    data: 'idcarpeta',
                    render: function (id) {
                        return `
                            <button class="btn btn-warning btn-sm btn-editar" data-id="${id}"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm btn-toggle" data-id="${id}"><i class="fa fa-times"></i></button>
                        `;
                    }
                }
            ]
        });

        const tablaInactivos = $('#tablaInactivos').DataTable({
            language: idioma,
            ajax: '{{ route("carpetas.data") }}?activo=N',
            columns: [
                { data: 'idcarpeta' },
                { data: 'nombre_solicitante' },
                { data: 'materia' },
                { data: 'tipo_juicio' },
                { data: 'fecharegistro' },
                {
                    data: 'idcarpeta',
                    render: function (id) {
                        return `<button class="btn btn-success btn-sm btn-toggle" data-id="${id}"><i class="fa fa-check"></i></button>`;
                    }
                }
            ]
        });

        $('#btnAgregar').click(() => {
            $('#formCarpeta')[0].reset();
            $('#idcarpeta').val('');
            $('#modalCarpeta').modal('show');
        });

        $('#formCarpeta').submit(function (e) {
            e.preventDefault();
            const id = $('#idcarpeta').val();
            const isEdit = !!id;
            const url = isEdit ? `/carpetas/update/${id}` : '{{ route("carpetas.store") }}';

            const formData = $(this).serializeArray();
            formData.push({ name: '_token', value: '{{ csrf_token() }}' });

            $.post(url, formData, function (res) {
                if (res.status === 'success') {
                    $('#modalCarpeta').modal('hide');
                    tablaActivos.ajax.reload();
                    tablaInactivos.ajax.reload();
                    Swal.fire('Listo', res.msg, 'success');
                } else {
                    Swal.fire('Error', res.msg, 'error');
                }
            });
        });

        $(document).on('click', '.btn-editar', function () {
            const id = $(this).data('id');
            $.get(`/carpetas/${id}/edit`, function (res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#idcarpeta').val(d.idcarpeta);
                    $('#idsolicitante').val(d.idsolicitante);
                    $('#idmateria').val(d.idmateria);
                    $('#idjuicio').val(d.idjuicio);
                    $('#sintesis').val(d.sintesis);
                    $('#modalCarpeta').modal('show');
                } else {
                    Swal.fire('Error', res.msg, 'error');
                }
            });
        });

        $(document).on('click', '.btn-toggle', function () {
            const $btn = $(this);
            const id = $btn.data('id');

            $btn.prop('disabled', true);

            $.post(`/carpetas/toggle/${id}`, {
                _token: '{{ csrf_token() }}'
            })
                .done(function (res) {
                    if (res.status === 'success') {
                        tablaActivos.ajax.reload();
                        tablaInactivos.ajax.reload();
                        Swal.fire('Listo', res.msg, 'success');
                    } else {
                        Swal.fire('Error', res.msg, 'error');
                    }
                })
                .fail(function () {
                    Swal.fire('Error', 'Ocurrió un error en el servidor.', 'error');
                })
                .always(function () {
                    $btn.prop('disabled', false);
                });
        });

    });
</script>
