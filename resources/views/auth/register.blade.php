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
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" required value="{{ old('nombre') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group has-feedback">
                <input type="text" name="apellidopaterno" class="form-control" placeholder="Apellido Paterno" required value="{{ old('apellidopaterno') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @error('apellidopaterno') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group has-feedback">
                <input type="text" name="apellidomaterno" class="form-control" placeholder="Apellido Materno" value="{{ old('apellidomaterno') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @error('apellidomaterno') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group has-feedback">
                <input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" required value="{{ old('correo') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @error('correo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group has-feedback">
                <input type="password" name="contraseña" class="form-control" placeholder="Contraseña" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @error('contraseña') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group has-feedback">
                <input type="password" name="contraseña_confirmation" class="form-control" placeholder="Confirmar Contraseña" required>
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

