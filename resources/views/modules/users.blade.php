<section class="content-header">
    <h1>
        Usuarios
        <small>Gesti&oacute;n de usuarios</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active">Usuarios</li>
    </ol>
</section>

<section class="content">
    <div>
        <button class="btn btn-primary" id="btnAddUser" onclick="abrirModalUsuarios()">Agregar Usuario</button>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h3>Usuarios Activos</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table id="tblUsuariosActivos" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <h3>Usuarios Inactivos</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table id="tblUsuariosInactivos" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<!-- MODAL USUARIOS -->
<div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-labelledby="modalEditUserLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEditUser">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalEditUserLabel">Usuario</h4>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="hddIdUser" name="hddIdUser">
                    <div class="form-group has-feedback">
                        <label for="nameUser" class="needed">Nombre</label>
                        <input type="text" class="form-control" id="nameUser" name="nameUser">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group has-feedback">
                                <label for="emailUser" class="needed">Correo Electr&oacute;nico</label>
                                <input type="email" class="form-control" id="emailUser" name="emailUser">
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="divCheckChangePassword" style="display: none">
                        <div class="col-xs-4 col-xs-offset-4">
                            <center>
                                <div class="checkbox icheck">
                                    <label>
                                    <input type="checkbox" id="passwordChange" name="passwordChange"> Cambiar Contrase&ntilde;a
                                    </label>
                                </div>
                            </center>
                        </div>
                    </div>

                    <div class="row" id="divPasswordUserAnt">
                        <div class="col-xs-8 col-xs-offset-2">
                            <div class="form-group has-feedback">
                                <label for="passwordUserAnt">Contraseña</label>
                                <input type="password" class="form-control" id="passwordUserAnt" name="passwordUserAnt">
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            </div>
                        </div>
                    </div>
                    

                    <div id="divChangePasswordUser" style="display: none">
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-2">
                                <div class="form-group has-feedback">
                                    <label for="passwordUser">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="passwordUser" name="passwordUser">
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-2">
                                <div class="form-group has-feedback">
                                    <label for="passwordUserConfirm">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="passwordUserConfirm" name="passwordUserConfirm">
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button id="btnRegisterUser" type="button" class="btn btn-primary">Guardar</button>
                    <button id="btnUpdateUser" type="button" class="btn btn-primary" style="display: none">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    $('#modalEditUser').on('hidden.bs.modal', function () {
        $("#btnRegisterUser").show();
        $("#btnUpdateUser").hide();

        $("#hddIdUser").val("");
        $("#nameUser").val("");
        $("#emailUser").val("");

        $('#passwordChange').iCheck('uncheck').change();

        $("#passwordUserAnt").val("");
        $("#divPasswordUserAnt").show();
        $("#divCheckChangePassword").hide();

        actualizarDatatables();
    });

    validaInfoUsers = function(paso){
        rs = true;
        msg = 'Valida los siguientes datos:<br>';
        if(paso == 1){ // REGISTRAR USUARIO 
            if($("#nameUser").val() == ""){
                msg += '- Nombre<br>';
                rs = false;
            }

            if($("#emailUser").val() == ""){
                msg += '- Correo Electr&oacute;nico<br>';
                rs = false;
            }else if(esCorreoValido($("#emailUser").val()) == false){
                msg += '- Correo Electr&oacute;nico no v&aacute;lido<br>';
                rs = false;
            }
            
            if($("#passwordUserAnt").val() == ""){ // REQUERIMOS CONTRASENIA ANTERIOR PARA ACTUALIZAR INFO POR SEGURIDAD
                msg += '- Contraseña<br>';
                rs = false;
            }
        }

        if(paso == 2){ // ACTUALIZAR USUARIO
            if($("#nameUser").val() == ""){
                msg += '- Nombre<br>';
                rs = false;
            }
            if($("#emailUser").val() == ""){
                msg += '- Correo Electr&oacute;nico<br>';
                rs = false;
            }else if(esCorreoValido($("#emailUser").val()) == false){
                msg += '- Correo Electr&oacute;nico no v&aacute;lido<br>';
                rs = false;
            }

            if($('#passwordChange').is(':checked')){
                if($("#passwordUserAnt").val() == ""){ // REQUERIMOS CONTRASENIA ANTERIOR PARA ACTUALIZAR INFO POR SEGURIDAD
                    msg += '- Contraseña<br>';
                    rs = false;
                }

                if($("#passwordUser").val() == ""){
                    msg += '- Nueva Contraseña<br>';
                    rs = false;
                }else if(paso == 2 && $("#passwordUser").val() == $("#passwordUserAnt").val()){
                    msg += '- La nueva contrase&ntilde;a no puede ser igual a la actual<br>';
                    rs = false;
                }else if($("#passwordUserConfirm").val() == ""){
                    msg += '- Confirmar Contraseña<br>';
                    rs = false;
                }else if($("#passwordUser").val() != $("#passwordUserConfirm").val()){
                    msg += '- Las contrase&ntilde;as no coinciden<br>';
                    rs = false;
                }
            }
        }
        
        if(!rs){
            Swal.fire({
                icon: 'warning',
                title: 'Aviso:',
                html: msg,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        }
        return rs;
    }

    $('#btnUpdateUser').on('click', function (event) {
        if (validaInfoUsers(2)) {
            updateUser();
        }
    });
    $('#btnRegisterUser').on('click', function (event) {
        if (validaInfoUsers(1)) {
            createUser();
        }
    });
    $('#passwordChange').on('ifChanged', function (event) {
        if ($(this).is(':checked')) {
            $("#divPasswordUserAnt").show();
            $("#divChangePasswordUser").show();
        } else {
            $("#divPasswordUserAnt").hide();
            $("#passwordUserAnt").val("");
            $("#divChangePasswordUser").hide();
            $("#passwordUser").val("");
            $("#passwordUserConfirm").val("");
        }
    });

    abrirModalUsuarios = function() {
        $("#modalEditUser").modal('show');
    }

    actualizarDatatables = function() {
        tblUsuariosActivos.ajax.reload(null, false);
        tblUsuariosInactivos.ajax.reload(null, false);
    }

    function getUserData(idUser) {
        $.ajax({
            type: 'GET',
            url: '{{ route('users.data') }}',
            // async: false,
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}', // TOKEN DE VALIDACION
                id: idUser,
            },
            // to dataForSend
            // contentType: false,
            // processData: false,
            beforeSend: function(objeto) {
                
            },
            success: function(dataGral) {
                if (dataGral.status == 'success') {
                    dataUser = dataGral.data[0];
                    $("#hddIdUser").val(dataUser.id);
                    $("#nameUser").val(dataUser.name);
                    $("#emailUser").val(dataUser.email);
                    
                    // CAMBIAMOS BTNS
                    $("#btnRegisterUser").hide();
                    $("#btnUpdateUser").show();
                    $("#passwordUserAnt").val("");
                    $("#divPasswordUserAnt").hide();
                    $("#divCheckChangePassword").show();

                    abrirModalUsuarios();
                }else{
                    swal({
                        type: dataGral.type,
                        title: 'Error:',
                        html: dataGral.msg,
                    });
                }
            },
            complete: function(objeto, quepaso, otroobj){
                
            },
            error: function(objeto, quepaso, otroobj) {
                
            },
        });
    }

    function deteleActiveUser(idUser, tipo = 2) {
        Swal.fire({
            title: '¿Quieres '+(tipo==1?"activar":"desactivar")+' a este usuario?',
            html: 'Podr&aacute; revertir esta acci&oacute;n.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'S&iacute;, continuar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('users.deleteactive') }}',
                    // async: false,
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}', // TOKEN DE VALIDACION
                        id: idUser,
                        activo: (tipo==1?"S":"N"),
                    },
                    beforeSend: function(objeto) {
                        
                    },
                    success: function(dataGral) {
                        if (dataGral.status == 'success') {
                            actualizarDatatables();
                            Swal.fire({
                                icon: 'success',
                                title: 'Aviso:',
                                html: 'Usuario '+(tipo==1?"activado":"desactivado")+' correctamente.',
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        }else{
                            Swal.fire({
                                icon: dataGral.type,
                                title: 'Aviso:',
                                html: dataGral.msg,
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        }
                    },
                    complete: function(objeto, quepaso, otroobj){
                        
                    },
                    error: function(objeto, quepaso, otroobj) {
                        
                    },
                });
            }
        });
    }

    function createUser() {
        Swal.fire({
            title: '¿Quieres registrar a este usuario?',
            html: 'No se podr&aacute; revertir esta acci&oacute;n.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'S&iacute;, continuar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('users.create') }}',
                    // async: false,
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}', // TOKEN DE VALIDACION
                        id: $("#hddIdUser").val(),
                        name: $("#nameUser").val(),
                        email: $("#emailUser").val(),
                        password: $("#passwordUserAnt").val(),
                    },
                    beforeSend: function(objeto) {
                        
                    },
                    success: function(dataGral) {
                        if (dataGral.status == 'success') {
                            $("#modalEditUser").modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Aviso:',
                                html: 'Usuario registrado correctamente.',
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        }else{
                            Swal.fire({
                                icon: dataGral.type,
                                title: 'Aviso:',
                                html: dataGral.msg,
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        }
                    },
                    complete: function(objeto, quepaso, otroobj){
                        
                    },
                    error: function(objeto, quepaso, otroobj) {
                        
                    },
                });
            }
        });
    }

    function updateUser() {
        Swal.fire({
            title: '¿Quieres actualizar la informaci&oacute;n de este usuario?',
            html: 'No se podr&aacute; revertir esta acci&oacute;n.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'S&iacute;, continuar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('users.update') }}',
                    // async: false,
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}', // TOKEN DE VALIDACION
                        id: $("#hddIdUser").val(),
                        name: $("#nameUser").val(),
                        email: $("#emailUser").val(),
                        
                        passwordChange: $('#passwordChange').is(':checked') ? 'S' : 'N',
                        password: $("#passwordUser").val(),
                        passwordUserAnt: $("#passwordUserAnt").val(),
                        passwordUserConfirm: $("#passwordUserConfirm").val(),
                    },
                    beforeSend: function(objeto) {
                        
                    },
                    success: function(dataGral) {
                        if (dataGral.status == 'success') {
                            $("#modalEditUser").modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Aviso:',
                                html: 'Usuario actualizado correctamente.',
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        }else{
                            Swal.fire({
                                icon: dataGral.type,
                                title: 'Aviso:',
                                html: dataGral.msg,
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        }
                    },
                    complete: function(objeto, quepaso, otroobj){
                        
                    },
                    error: function(objeto, quepaso, otroobj) {
                        
                    },
                });
            }
        });
    }

    $(document).ready(function() {
        tblUsuariosActivos = $('#tblUsuariosActivos').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route('users.data') }}',
                data: function (d) {
                    d.activo = "S";
                    d.dataTable = 'S';
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'activo', name: 'activo' },
                {
                    data: null,
                    name: 'acciones',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return `
                            <button class="btn btn-sm btn-warning" onclick="getUserData(${row.id})">Consultar</button>
                            <button class="btn btn-sm btn-danger" onclick="deteleActiveUser(${row.id})">Desactivar</button>
                        `;
                    }
                }
            ]
        });

        tblUsuariosInactivos = $('#tblUsuariosInactivos').DataTable({
            processing: true,
            serverSide: true,
            language: configDatatableSpanish,
            ajax: {
                url: '{{ route('users.data') }}',
                data: function (d) {
                    d.activo = "N";
                    d.dataTable = 'S';
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'activo', name: 'activo' },
                {
                    data: null,
                    name: 'acciones',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return `
                            <button class="btn btn-sm btn-success" onclick="deteleActiveUser(${row.id},1)">Activar</button>
                        `;
                    }
                }
            ]
        });

        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        });

    });
</script>
