$(document).ready(function () {
    function cargarFinanzas() {
        $.ajax({
            url: 'api/finanzas.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('.tarjeta-1 .numeros').text(data.ventas);
                $('.tarjeta-2 .numeros').text(data.cobros);
                $('.tarjeta-3 .numeros').text(data.compras);
                $('.tarjeta-4 .numeros').text(data.pagos);
            },
        });
    }
    setInterval(cargarFinanzas, 1000);
});