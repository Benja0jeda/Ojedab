$(document).ready(function() {
    $.getJSON('../api/ivas.php', function(data) {
        var select = $('#id_iva');
        $.each(data, function(i, elemento) {
            select.append($('<option>', {
                value: elemento.id_iva,
                text: elemento.tipo
            }));
        });
    });
});