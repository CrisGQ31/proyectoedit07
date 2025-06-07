<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid">
    <h1 class="mb-4">Gestión de Carpetas</h1>

    <button class="btn btn-success mb-3" id="btnAdd">Nueva Carpeta</button>

    <h3>Carpetas Activas</h3>
    <table id="tableCarpetasActivas" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Solicitante</th>
            <th>Materia</th>
            <th>Tipo de Juicio</th>
            <th>Síntesis</th>
            <th>Acciones</th>
        </tr>
        </thead>
    </table>

    <h3 class="mt-5">Carpetas Inactivas</h3>
    <table id="tableCarpetasInactivas" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Solicitante</th>
            <th>Materia</th>
            <th>Tipo de Juicio</th>
            <th>Síntesis</th>
            <th>Acciones</th>
        </tr>
        </thead>
    </table>
</div>

<!-- Modal para agregar/editar -->
<div class="modal fade" id="carpetaModal" tabindex="-1" role="dialog" aria-labelledby="carpetaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCarpeta">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carpetaModalLabel">Nueva Carpeta</h5>
                    <!-- Botón de cerrar compatible con Bootstrap 4 -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idcarpeta" name="idcarpeta">

                    <div class="mb-3">
                        <label for="idsolicitante" class="form-label">Solicitante</label>
                        <select class="form-control" id="idsolicitante" name="idsolicitante" required>
                            <option value="">Seleccione solicitante</option>
                            @foreach(\App\Models\Solicitante::where('activo', 'S')->get() as $sol)
                                <option value="{{ $sol->idsolicitante }}">{{ $sol->nombre }} {{ $sol->apellidopaterno }} {{ $sol->apellidomaterno }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="idmateria" class="form-label">Materia</label>
                        <select class="form-control" id="idmateria" name="idmateria" required>
                            <option value="">Seleccione materia</option>
                            @foreach(\App\Models\Materia::where('activo', 'S')->get() as $mat)
                                <option value="{{ $mat->idmateria }}">{{ $mat->tipomateria }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="idjuicio" class="form-label">Tipo de Juicio</label>
                        <select class="form-control" id="idjuicio" name="idjuicio" required>
                            <option value="">Seleccione tipo de juicio</option>
                            @foreach(\App\Models\TipoJuicio::where('activo', 'S')->get() as $jui)
                                <option value="{{ $jui->idjuicio }}">{{ $jui->tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="sintesis" class="form-label">Síntesis</label>
                        <textarea class="form-control" id="sintesis" name="sintesis" rows="3" placeholder="(Opcional)"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSave">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        // Tabla carpetas activas
        let tablaActivas = $('#tableCarpetasActivas').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('carpetas.data') }}",
                data: { activo: 'S' }  // Filtrar activas
            },
            columns: [
                { data: 'idcarpeta' },
                { data: 'solicitante' },
                { data: 'materia' },
                { data: 'juicio' },
                { data: 'sintesis' },
                {
                    data: 'idcarpeta', orderable: false, searchable: false,
                    render: function(id) {
                        return `
                        <button class="btn btn-warning btn-toggle" data-id="${id}" title="Desactivar">
                            <i class="fas fa-times-circle"></i>
                        </button>
                        <button class="btn btn-primary btn-edit" data-id="${id}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                      `;
                    }
                },
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
            }
        });

        // Tabla carpetas inactivas
        let tablaInactivas = $('#tableCarpetasInactivas').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('carpetas.data') }}",
                data: { activo: 'N' }  // Filtrar inactivas
            },
            columns: [
                { data: 'idcarpeta' },
                { data: 'solicitante' },
                { data: 'materia' },
                { data: 'juicio' },
                { data: 'sintesis' },
                {
                    data: 'idcarpeta', orderable: false, searchable: false,
                    render: function(id) {
                        return `
                        <button class="btn btn-success btn-toggle" data-id="${id}" title="Activar">
                            <i class="fas fa-check-circle"></i>
                        </button>
                        <button class="btn btn-danger btn-delete" data-id="${id}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                      `;
                    }
                },
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
            }
        });

        // Nueva carpeta
        $('#btnAdd').click(function () {
            $('#formCarpeta')[0].reset();
            $('#idcarpeta').val('');
            $('#carpetaModalLabel').text('Nueva Carpeta');
            $('#carpetaModal').modal('show');
        });

        // Guardar/Actualizar carpeta (igual que antes)
        $('#formCarpeta').submit(function (e) {
            e.preventDefault();
            $('#btnSave').prop('disabled', true);

            $.ajax({
                url: "{{ route('carpetas.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $('#carpetaModal').modal('hide');
                    $('#btnSave').prop('disabled', false);
                    tablaActivas.ajax.reload(null, false);
                    tablaInactivas.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: res.message,
                    });
                },
                error: function (xhr) {
                    $('#btnSave').prop('disabled', false);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        for (const key in errors) {
                            errorMsg += errors[key][0] + '\n';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            text: errorMsg,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error inesperado',
                            text: xhr.status + ' - ' + (xhr.responseJSON?.message || xhr.statusText),
                        });
                    }
                }
            });
        });

        // Editar carpeta (botón editar en tabla activa)
        $('#tableCarpetasActivas').on('click', '.btn-edit', function () {
            let id = $(this).data('id');
            $.get("{{ url('carpetas/edit') }}/" + id, function (data) {
                $('#idcarpeta').val(data.idcarpeta);
                $('#idsolicitante').val(data.idsolicitante);
                $('#idmateria').val(data.idmateria);
                $('#idjuicio').val(data.idjuicio);
                $('#sintesis').val(data.sintesis);
                $('#carpetaModalLabel').text('Editar Carpeta');
                $('#carpetaModal').modal('show');
            });
        });

        // Toggle activar/desactivar en ambas tablas
        $('body').on('click', '.btn-toggle', function () {
            let id = $(this).data('id');
            $.post("{{ url('carpetas/toggle') }}/" + id, function (res) {
                tablaActivas.ajax.reload(null, false);
                tablaInactivas.ajax.reload(null, false);
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: res.message,
                });
            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error inesperado',
                    text: xhr.status + ' - ' + (xhr.responseJSON?.message || xhr.statusText),
                });
            });
        });

        // Eliminar carpeta solo en tabla inactiva
        $('#tableCarpetasInactivas').on('click', '.btn-delete', function () {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('carpetas/delete') }}/" + id,
                        type: 'DELETE',
                        success: function (res) {
                            tablaInactivas.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: res.message,
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error inesperado',
                                text: xhr.status + ' - ' + (xhr.responseJSON?.message || xhr.statusText),
                            });
                        }
                    });
                }
            });
        });
    });
</script>
