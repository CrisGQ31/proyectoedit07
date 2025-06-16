<style>
    /* Contenedor principal con sombra y bordes redondeados */
    .reporte-container {
        background: #ffffff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 35px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        border-left: 6px solid #0d6efd;
        transition: box-shadow 0.3s ease;
    }

    .reporte-container:hover {
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    .reporte-info h1 {
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0;
        color: #212529;
    }

    .reporte-subtitulo {
        margin: 0;
        font-size: 0.95rem;
        color: #6c757d;
    }

    #formReporte {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 15px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .reporte-opciones {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    #tipoReporte {
        min-width: 200px;
        border-radius: 8px;
        border: 1.5px solid #ced4da;
        padding: 10px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    #tipoReporte:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
        outline: none;
    }

    #formReporte button {
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    #formReporte button:hover {
        background-color: #0b5ed7;
        color: #fff;
        box-shadow: 0 6px 12px rgba(11, 94, 215, 0.4);
    }

    @media (max-width: 576px) {
        .reporte-container {
            flex-direction: column;
            align-items: flex-start;
        }

        #formReporte {
            justify-content: center;
            width: 100%;
        }

        .reporte-opciones {
            flex-direction: column;
            width: 100%;
        }

        #tipoReporte {
            width: 100%;
        }

        #formReporte button {
            width: 100%;
            justify-content: center;
        }
    }

</style>

<div class="reporte-container shadow-sm">
    <div class="reporte-info">
        <h1 style="font-size: 4rem;">Gestión de Carpetas</h1>
        <p class="reporte-subtitulo">Genera reportes personalizados en el formato que desees</p>
    </div>

    <form id="formReporte" method="POST" action="{{ route('carpetas.reporte') }}" target="_blank" class="needs-validation" novalidate>
        @csrf
        <div class="reporte-opciones">
            <div class="form-floating">
                <select name="tipoReporte" id="tipoReporte" class="form-select" required>
                    <option value="" selected disabled>Selecciona una opción</option>
                    <option value="pdf">📄 PDF</option>
                    <option value="excel">📊 Excel</option>
                    <option value="html">🌐 HTML</option>
                </select>
                <label>Formato del reporte</label>
            </div>
            <button type="submit" class="btn btn-outline-primary">
                <i></i> Generar Reporte
            </button>
        </div>
    </form>
</div>


<script>
    // Bootstrap 5 validation
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>


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
</div>

<!-- Modal para agregar/editar -->
<div class="modal fade" id="carpetaModal" tabindex="-1" role="dialog" aria-labelledby="carpetaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCarpeta">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carpetaModalLabel">Nueva Carpeta</h5>
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

<!-- Aquí agregamos el token CSRF para que AJAX funcione sin error 419 -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        let tablaActivas = $('#tableCarpetasActivas').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('carpetas.data') }}",
                data: { activo: 'S' }
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
                                <i class="fas fa-times-circle"></i> Desactivar
                            </button>
                            <button class="btn btn-primary btn-edit" data-id="${id}" title="Editar">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        `;
                    }
                },
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
            }
        });

        $('#btnAdd').click(function () {
            $('#formCarpeta')[0].reset();
            $('#idcarpeta').val('');
            $('#carpetaModalLabel').text('Nueva Carpeta');
            $('#carpetaModal').modal('show');
        });

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

        $('body').on('click', '.btn-toggle', function () {
            let id = $(this).data('id');
            $.post("{{ url('carpetas/toggle') }}/" + id, function (res) {
                tablaActivas.ajax.reload(null, false);
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
    });
</script>
