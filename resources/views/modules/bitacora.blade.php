
    <div class="card shadow p-3">
        <h4 class="mb-4">Bitácora de acciones</h4>
        <table class="table table-bordered" id="tablaBitacora" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Observaciones</th>
                <th>Fecha Registro</th>
            </tr>
            </thead>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#tablaBitacora').DataTable({
                ajax: '{{ route("bitacora.data") }}',
                columns: [
                    { data: 'idbitacora' },
                    { data: 'nombre' },
                    { data: 'descripcion' },
                    { data: 'observaciones' },
                    { data: 'fecharegistro' }
                ],
                responsive: true,
                language: {
                    url: '{{ asset("js/datatables/spanish.json") }}'
                }
            });
        });
    </script>

