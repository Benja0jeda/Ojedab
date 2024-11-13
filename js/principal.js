$(document).ready(function() {
    function actualizarDatos() {
        $.ajax({
            url: 'api/principal.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {

                $('#fecha-actual').text(data.fecha);

                var cumpleanosList = $('#lista-cumpleanos');
                cumpleanosList.empty();
                if (data.clientes_cumpleaños.length > 0) {
                    data.clientes_cumpleaños.forEach(function(cliente) {
                        cumpleanosList.append('<li>' + cliente + '</li>');
                    });
                } else {
                    cumpleanosList.append('<li>Ningún cliente está de cumpleaños hoy</li>');
                }

                var facturasList = $('#lista-facturas');
                facturasList.empty();
                if (data.facturas_por_vencer.length > 0) {
                    data.facturas_por_vencer.forEach(function(factura) {
                        facturasList.append('<li>Precio: ' + factura.precio + ', Vencimiento: ' + factura.vencimiento + '</li>');
                    });
                } else {
                    facturasList.append('<li>No hay facturas por vencer</li>');
                }

                var productosList = $('#lista-productos');
                productosList.empty();
                if (data.productos_existencias.length > 0) {
                    data.productos_existencias.forEach(function(producto) {
                        productosList.append('<li>' + producto.nombre + ' - Cantidad: ' + producto.cantidad + '</li>');
                    });
                } else {
                    productosList.append('<li>No hay productos con pocas existencias</li>');
                }
            },
        });
    }

    actualizarDatos();
    setInterval(actualizarDatos, 1000);
});