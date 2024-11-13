$(document).ready(function () {
    let actual = 1;
    let total = 1;
    let limite = 50;

    function cargarCobros(buscar = '', pagina = 1) {
        $.ajax({
            url: 'api/cobros.php',
            method: 'GET',
            data: { buscar: buscar, pagina: pagina, limite: limite },
            success: function (data) {
                const tbody = $('#tabla-cobros tbody');
                tbody.empty();
                data.elementos.forEach(function (cobro) {
                    const tieneComprobante = cobro.id_venta && cobro.id_venta !== '0' && cobro.id_venta !== 'null';
                    
                    const comprobante = tieneComprobante 
                        ? `<a href="generar_pdf.php?id_venta=${cobro.id_venta}" class="ver-comprobante">Ver comprobante</a>`
                        : '';

                    const fila = `
                        <tr>
                            <td>${cobro.nombre}</td>
                            <td>${cobro.cedula}</td>
                            <td>${cobro.monto}</td>
                            <td>${cobro.fecha}</td>
                            <td>${comprobante}</td>
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

    cargarCobros();

    setInterval(function () {
        cargarCobros($('#buscar').val(), actual);
    }, 5000);

    $('#buscar').on('input', function () {
        cargarCobros($(this).val(), 1);
    });

    $('.paginacion-contenedor button:first-child').click(function () {
        if (actual > 1) {
            cargarCobros($('#buscar').val(), actual - 1);
        }
    });

    $('.paginacion-contenedor button:last-child').click(function () {
        if (actual < total) {
            cargarCobros($('#buscar').val(), actual + 1);
        }
    });
});