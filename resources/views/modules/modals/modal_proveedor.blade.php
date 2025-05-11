<div class="modal fade" id="modalEditProveedor" tabindex="-1" role="dialog" aria-labelledby="modalProveedorLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formProveedor">
                @csrf
                <input type="hidden" id="hddIdProveedor">

                <div class="modal-header">
                    <h4 class="modal-title" id="modalProveedorLabel">Formulario de Proveedor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombreProveedor">Nombre:</label>
                        <input type="text" class="form-control" id="nombreProveedor" required>
                    </div>

                    <div class="form-group">
                        <label for="contactoProveedor">Contacto:</label>
                        <input type="text" class="form-control" id="contactoProveedor" required>
                    </div>

                    <div class="form-group">
                        <label for="telefonoProveedor">Teléfono:</label>
                        <input type="text" class="form-control" id="telefonoProveedor" required>
                    </div>
                    <div class="form-group">
                        <label for="emailProveedor">Correo electrónico:</label>
                        <input type="email" class="form-control" id="emailProveedor" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="btnRegisterProveedor" class="btn btn-success">Registrar</button>
                    <button type="button" id="btnUpdateProveedor" class="btn btn-primary">Actualizar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

