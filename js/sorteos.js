$(document).ready(function () {
    let actual = 1;
    let total = 1;
    let limite = 50;

    function cargarSorteos(buscar = '', pagina = 1) {
        $.ajax({
            url: 'api/sorteos.php',
            method: 'GET',
            data: { buscar: buscar, pagina: pagina, limite: limite },
            success: function (data) {
                const tbody = $('#tabla-sorteos tbody');
                tbody.empty();
                data.elementos.forEach(function (sorteo) {
                    const fila = `
                        <tr>
                            <td>${sorteo.premio}</td>
                            <td>${sorteo.cantidad_ganadores}</td>
                            <td>${sorteo.fecha ? sorteo.fecha : ''}</td>
                            <td>${sorteo.ganadores ? sorteo.ganadores : ''}</td>
                            <td>
                                <button class="editar" data-id="${sorteo.id_sorteo}">Editar</button>
                                <button class="eliminar" data-id="${sorteo.id_sorteo}">Eliminar</button>
                                <button class="sortear" data-id="${sorteo.id_sorteo}" ${sorteo.ganadores ? 'disabled' : ''}>Sortear</button>
                            </td>
                        </tr>
                    `;
                    tbody.append(fila);
                });

                actual = pagina;
                total = data.paginas;
                actualizarPaginacionTexto();
            }
        });
    }

    function actualizarPaginacionTexto() {
        $('.paginacion').text(`Página ${actual} de ${total}`);

        $('.paginacion-contenedor button:first-child').prop('disabled', actual <= 1);
        $('.paginacion-contenedor button:last-child').prop('disabled', actual >= total);
    }

    function eliminarSorteo(id) {
        if (confirm("¿Seguro que deseas eliminar este sorteo?")) {
            $.ajax({
                url: 'gestionar_sorteos/eliminar.php',
                method: 'POST',
                data: { id: id },
                success: function () {
                    cargarSorteos($('#buscar').val(), actual);
                }
            });
        }
    }

    function editarSorteo(id) {
        window.location.href = `gestionar_sorteos/editar.php?id=${id}`;
    }

    function sortearSorteo(id) {
        if (confirm("¿Estás seguro de que deseas realizar el sorteo? Todos los boletos de los clientes serán restablecidos a 0.")) {
            $.ajax({
                url: 'gestionar_sorteos/sortear.php',
                method: 'POST',
                data: { id: id },
                success: function () {
                    cargarSorteos($('#buscar').val(), actual);
                }
            });
        }
    }

    cargarSorteos();

    setInterval(function () {
        cargarSorteos($('#buscar').val(), actual);
    }, 5000);

    $('#buscar').on('input', function () {
        cargarSorteos($(this).val(), 1);
    });

    $(document).on('click', '.eliminar', function () {
        eliminarSorteo($(this).data('id'));
    });

    $(document).on('click', '.editar', function () {
        editarSorteo($(this).data('id'));
    });

    $(document).on('click', '.sortear', function () {
        sortearSorteo($(this).data('id'));
    });

    $('.paginacion-contenedor button:first-child').click(function () {
        if (actual > 1) {
            cargarSorteos($('#buscar').val(), actual - 1);
        }
    });

    $('.paginacion-contenedor button:last-child').click(function () {
        if (actual < total) {
            cargarSorteos($('#buscar').val(), actual + 1);
        }
    });
});