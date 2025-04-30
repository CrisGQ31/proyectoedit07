// VARIABLE GENERAL DE CONFIGURACION DE DATATABLES
    var configDatatableSpanish = {
        "decimal": "",
        "emptyTable": "No hay datos disponibles en la tabla",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "Mostrando 0 a 0 de 0 registros",
        "infoFiltered": "(filtrado de _MAX_ registros totales)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ registros",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "No se encontraron registros coincidentes",
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
        },
        "aria": {
            "sortAscending": ": activar para ordenar de forma ascendente",
            "sortDescending": ": activar para ordenar de forma descendente"
        }
    };

// SPINNER GENERAL DE CARGA
$(document).ajaxStart(function () {
    spinnerLoading();
});

$(document).ajaxStop(function () {
    spinnerLoading(true);
});
$.ajaxSetup({cache: false}); //deshabilitar el cache

function spinnerLoading(option = false, id = "cargandoDefault") {
    if ($("#" + id).is(":visible")) {
        if (!option) {
        } else {
            $("#" + id).remove();
        }
    } else {
        if (!option) {
            $('<div id="' + id + '" class="containerLoader modal-backdrop "><div class="loader"><span></span><span></span><span></span><span></span><span></span></div><div class="imgLoader"><div class="containerBack"></div></div></div>').appendTo(document.body).fadeIn().css({
                "background-color": 'rgba(0,0,0,.7)',
                'z-index': '999999999'
            });
        } else {
            $("#" + id).remove();
        }
    }
}

// VALIDAR CORREO
function esCorreoValido(correo = "") {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(correo);
}