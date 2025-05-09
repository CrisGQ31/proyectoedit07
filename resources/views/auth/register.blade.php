<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    @include('layouts.sections.librariesHeaders')
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="{{ url('/') }}"><b>Proyecto Final</b></a>
    </div>
    <div class="register-box-body">
        <p class="login-box-msg">Crea una nueva cuenta</p>
        <form action="{{ route('registerUser') }}" method="POST">
            @csrf
            <div class="form-group has-feedback">
                <input type="text" name="name" class="form-control" placeholder="Nombre Completo" required value="{{ old('name') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="Correo Electrónico" required value="{{ old('email') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar Contraseña" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember"> Recordarme
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-success btn-block btn-flat">Registrar</button>
                </div>
            </div>
        </form>
        <br>
        <div class="text-center">
            <a href="{{ route('login') }}" class="btn btn-primary btn-block btn-flat">Ya tengo cuenta</a>
        </div>
    </div>
</div>

@include('layouts.sections.librariesFooters')
</body>
</html>


