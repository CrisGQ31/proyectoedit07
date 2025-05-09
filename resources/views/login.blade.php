<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @include('layouts.sections.librariesHeaders')

    <style>
        .login-logo a{
            color: white !important;
        }
        .login-background {
            background: url('{{ asset('img/login/back.jpg') }}') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="hold-transition login-page login-background">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>Proyecto Final</b></a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Ingresa tus credenciales para comenzar tu sesión</p>

        <!-- Formulario de login -->
        <form action="{{ route('validateUser') }}" method="POST">
            @csrf
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="Correo Electrónico" required autofocus>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
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
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
                </div>
            </div>
        </form>

        <br>
        <!-- Enlace para ir al formulario de registro -->
        <div class="text-center">
            <a href="{{ route('register') }}" class="btn btn-success btn-block btn-flat">Crear una cuenta</a>
        </div>
    </div>
</div>

@include('layouts.sections.librariesFooters')

@if(session('error'))
    <script>
        Swal.fire({
            title: 'Aviso',
            html: '{{ session('error') }}',
            icon: 'warning',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });
    </script>
@endif

<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });
    });
</script>
</body>
</html>



{{--<!DOCTYPE html>--}}
{{--<html lang="es">--}}
{{--    <head>--}}
{{--        <meta charset="UTF-8">--}}
{{--        <title>Iniciar Sesión</title>--}}
{{--        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">--}}
{{--        --}}
{{--        @include('layouts.sections.librariesHeaders')--}}
{{--        --}}
{{--        <style>--}}
{{--            .login-logo a{--}}
{{--                color: white !important;--}}
{{--            }--}}
{{--            .login-background {--}}
{{--                background: url('{{ asset('img/login/back.jpg') }}') no-repeat center center fixed;--}}
{{--                background-size: cover;--}}
{{--            }--}}
{{--        </style>--}}

{{--    </head>--}}
{{--    <body class="hold-transition login-page login-background">--}}
{{--        <div class="login-box">--}}
{{--            <div class="login-logo">--}}
{{--                <a href="{{ url('/') }}"><b>Proyecto Final</b></a>--}}
{{--            </div>--}}
{{--            <div class="login-box-body">--}}
{{--                <p class="login-box-msg">Ingresa tus credenciales para comenzar tu sesi&oacute;n</p>--}}
{{--                <form action="{{ route('validateUser') }}" method="POST">--}}
{{--                    @csrf--}}
{{--                    <div class="form-group has-feedback">--}}
{{--                            <input type="email" name="email" class="form-control" placeholder="Correo Electrónico" required autofocus>--}}
{{--                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>--}}
{{--                    </div>--}}
{{--                    <div class="form-group has-feedback">--}}
{{--                            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>--}}
{{--                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>--}}
{{--                    </div>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-xs-8">--}}
{{--                            <div class="checkbox icheck">--}}
{{--                                <label>--}}
{{--                                <input type="checkbox" name="remember"> Recordarme--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-xs-4">--}}
{{--                            <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        --}}
{{--        @include('layouts.sections.librariesFooters')--}}

{{--        @if(session('error'))--}}
{{--            <script>--}}
{{--                Swal.fire({--}}
{{--                    title: 'Aviso',--}}
{{--                    html: '{{ session('error') }}',--}}
{{--                    icon: 'warning',--}}
{{--                    buttonsStyling: false,--}}
{{--                    customClass: {--}}
{{--                        confirmButton: 'btn btn-primary'--}}
{{--                    }--}}
{{--                });--}}
{{--            </script>--}}
{{--        @endif--}}

{{--        <script>--}}
{{--            $(function () {--}}
{{--                $('input').iCheck({--}}
{{--                    checkboxClass: 'icheckbox_square-blue',--}}
{{--                    radioClass: 'iradio_square-blue',--}}
{{--                    increaseArea: '20%'--}}
{{--                });--}}
{{--            });--}}
{{--        </script>--}}
{{--    </body>--}}
{{--</html>--}}
