// Función para consultar datos de un producto y abrir el modal
function getProductData(idProduct) {
    $.ajax({
        type: 'GET',
        url: '/products/data',
        data: { id: idProduct },
        success: function(data) {
            // Aquí puedes llenar el modal con los datos del producto
            $('#nameProduct').val(data.nombre);
            $('#priceProduct').val(data.precio);
            $('#hddIdProduct').val(idProduct);
            $('#btnRegisterProduct').hide();
            $('#btnUpdateProduct').show();
            $('#modalEditProduct').modal('show');
        }
    });
}

// Función para activar/desactivar un producto
function toggleProductActive(idProduct, status) {
    $.ajax({
        type: 'POST',
        url: '/products/toggle',
        data: {
            _token: '{{ csrf_token() }}',
            id: idProduct,
            activo: status
        },
        success: function(data) {
            Swal.fire({
                icon: 'success',
                title: 'Aviso',
                text: data.msg,
            });
            actualizarDatatablesProductos();
        }
    });
}

// Función para actualizar la tabla de productos
function actualizarDatatablesProductos() {
    tblProductosActivos.ajax.reload(null, false);
    tblProductosInactivos.ajax.reload(null, false);
}

