<div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">Gestión de Solicitantes</h5>
                <button class="btn btn-success btn-sm" id="btnNuevoSolicitante">
                    <i class="fas fa-plus-circle"></i> Nuevo Solicitante
                </button>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="tabsSolicitantes">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#activos">Activos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#inactivos">Inactivos</a>
                    </li>
                </ul>
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="activos">
                        <table class="table table-bordered" id="tablaSolicitantesActivos">
                            <thead class="thead-light">
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Teléfono</th>
                                <th>RFC</th>
                                <th>CURP</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="inactivos">
                        <table class="table table-bordered" id="tablaSolicitantesInactivos">
                            <thead class="thead-light">
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Teléfono</th>
                                <th>RFC</th>
                                <th>CURP</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Soliciante -->
    <div class="modal fade" id="modalSolicitante" tabindex="-1" role="dialog" aria-labelledby="modalSolicitanteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formSolicitante">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalSolicitanteLabel">Nuevo Solicitante</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="idsolicitante" id="idsolicitante">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Apellido Paterno</label>
                            <input type="text" name="apellidopaterno" id="apellidopaterno" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Apellido Materno</label>
                            <input type="text" name="apellidomaterno" id="apellidomaterno" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>RFC</label>
                            <input type="text" name="rfc" id="rfc" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>CURP</label>
                            <input type="text" name="curp" id="curp" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>




    <script>
        $(document).ready(function () {
            cargarTablas();

            $('#btnNuevoSolicitante').click(function () {
                $('#formSolicitante')[0].reset();
                $('#idsolicitante').val('');
                $('#modalSolicitanteLabel').text('Nuevo Solicitante');
                $('#modalSolicitante').modal('show');
            });

            $('#formSolicitante').submit(function (e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.post("{{ route('solicitantes.store') }}", formData, function (res) {
                    $('#modalSolicitante').modal('hide');
                    $('#tablaSolicitantesActivos').DataTable().ajax.reload();
                    $('#tablaSolicitantesInactivos').DataTable().ajax.reload();
                    Swal.fire('Éxito', res.message, 'success');
                }).fail(function (err) {
                    Swal.fire('Error', 'Ocurrió un error al guardar', 'error');
                });
            });
        });

        function cargarTablas() {
            $('#tablaSolicitantesActivos').DataTable({
                ajax: "{{ route('solicitantes.data', ['estado' => 1]) }}",
                columns: [
                    { data: 'nombre_completo' },
                    { data: 'telefono' },
                    { data: 'rfc' },
                    { data: 'curp' },
                    { data: 'fecharegistro' },
                    { data: 'acciones', orderable: false, searchable: false }
                ],
                destroy: true
            });

            $('#tablaSolicitantesInactivos').DataTable({
                ajax: "{{ route('solicitantes.data', ['estado' => 0]) }}",
                columns: [
                    { data: 'nombre_completo' },
                    { data: 'telefono' },
                    { data: 'rfc' },
                    { data: 'curp' },
                    { data: 'fecharegistro' },
                    { data: 'acciones', orderable: false, searchable: false }
                ],
                destroy: true
            });
        }

        function editarSolicitante(id) {
            $.get("{{ url('solicitantes') }}/" + id + "/edit", function (data) {
                $('#idsolicitante').val(data.idsolicitante);
                $('#nombre').val(data.nombre);
                $('#apellidopaterno').val(data.apellidopaterno);
                $('#apellidomaterno').val(data.apellidomaterno);
                $('#telefono').val(data.telefono);
                $('#rfc').val(data.rfc);
                $('#curp').val(data.curp);
                $('#modalSolicitanteLabel').text('Editar Solicitante');
                $('#modalSolicitante').modal('show');
            });
        }

        function cambiarEstadoSolicitante(id) {
            Swal.fire({
                title: '¿Cambiar estado?',
                text: "Este cambio actualizará el estado del solicitante.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ url('solicitantes/toggle') }}/" + id, {_token: '{{ csrf_token() }}'}, function (res) {
                        $('#tablaSolicitantesActivos').DataTable().ajax.reload();
                        $('#tablaSolicitantesInactivos').DataTable().ajax.reload();
                        Swal.fire('Actualizado', res.message, 'success');
                    });
                }
            });
        }
    </script>

