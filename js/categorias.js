$(document).ready(function() {
    $.getJSON('../api/categorias.php', function(data) {
        var select = $('#id_categoria');
        $.each(data, function(i, elemento) {
            select.append($('<option>', {
                value: elemento.id_categoria,
                text: elemento.titulo
            }));
        });
    });
});