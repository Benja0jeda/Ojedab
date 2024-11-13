$(document).ready(function() {
    $('.alternar').on('click', function() {
        $('.navegar, .principal').toggleClass('activo');
    });

    $('.perfil').on('click', function() {
        $('.desplegar').toggleClass('activo');
    });

    $('.confirmar').on('click', function() {
        $('#panel-transaccion').addClass('activo');
        $('.superposicion').show();
    });

    $('.cerrar-panel, .superposicion').on('click', function() {
        $('#panel-transaccion').removeClass('activo');
        $('.superposicion').hide();
    });

    $("#ingresarCobro").on('click', function() {
        $("#cobroModal").css("display", "block");
    });

    $(".close").on('click', function() {
        $("#cobroModal").css("display", "none");
        $("#pagoModal").css("display", "none");
    });

    $(window).on('click', function(event) {
        if (event.target == $("#cobroModal")[0]) {
            $("#cobroModal").css("display", "none");
        }
        if (event.target == $("#pagoModal")[0]) {
            $("#pagoModal").css("display", "none");
        }
    });

    $("#ingresarPago").on('click', function() {
        $("#pagoModal").css("display", "block");
    });
});