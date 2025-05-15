<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Proyecto Final</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('layouts.sections.librariesHeaders')

        @stack('styles')

        <style>
            .sf-dump{
                z-index: unset !important;
            }
        </style>

    </head>
    <body class="skin-blue sidebar-mini sidebar-collapse fixed">
        <div class="wrapper">

            @include('layouts.sections.header')
            @include('layouts.sections.sidebar')

            <div class="content-wrapper" id="contentGeneric">
                {{-- CONTENIDO DINAMICO --}}
            </div>

            @include('layouts.sections.footer')
            @include('layouts.sections.control-sidebar')

        </div>

        @include('layouts.sections.librariesFooters')

        @push('scripts')
            <script>
                $(document).ready(function() {
                    // Función para cargar contenido vía AJAX
                    function loadContent(url) {
                        // $('#contentGeneric').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span>Cargando...</span></div>');
                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function(data) {
                                $('#contentGeneric').html(data);
                            },
                            error: function(xhr) {

                                let errorMsg = 'Error al cargar el contenido.';

                                if (xhr.status === 404) {
                                    errorMsg += '<br>Recurso no encontrado (404).';
                                } else if (xhr.status === 500) {
                                    errorMsg += '<br>Error interno del servidor (500).';
                                }

                                if (xhr.responseText) {
                                    console.error('Detalles del error:', xhr.responseText);
                                    errorMsg += '<br><small>Detalles: ' + xhr.statusText + '</small>';
                                }

                                loadContent('{{ route('welcome') }}');

                                Swal.fire({
                                    title: 'Aviso',
                                    html: errorMsg,
                                    icon: 'error',
                                    buttonsStyling: false,
                                    customClass: {
                                        confirmButton: 'btn btn-primary'
                                    }
                                });
                                {{--loadContent('{{ route('welcome') }}');--}}
                                {{--Swal.fire({--}}
                                {{--    title: 'Aviso',--}}
                                {{--    html: 'Error al cargar el contenido. Por favor, int&eacute;ntelo de nuevo m&aacute;s tarde.',--}}
                                {{--    icon: 'warning',--}}
                                {{--    buttonsStyling: false,--}}
                                {{--    customClass: {--}}
                                {{--        confirmButton: 'btn btn-primary'--}}
                                {{--    }--}}
                                {{--});--}}
                            }
                        });
                    }

                    // Cargar la vista predeterminada al iniciar
                    loadContent('{{ route('welcome') }}');

                    // Manejar clics en enlaces con la clase 'ajax-link'
                    $('.ajax-link').on('click', function(e) {
                        e.preventDefault();
                        var url = $(this).attr('href');

                        $('.ajax-link').parent('li').removeClass('active');
                        $(this).parent('li').addClass('active');

                        loadContent(url);
                    });
                });
            </script>
        @endpush

        @stack('scripts')
    </body>
</html>
