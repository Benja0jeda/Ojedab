$(document).ready(function() {
    $.getJSON('../api/medidas.php', function(data) {
        var select = $('#id_medida');
        $.each(data, function(i, elemento) {
            select.append($('<option>', {
                value: elemento.id_medida,
                text: elemento.unidad
            }));
        });
    });
});