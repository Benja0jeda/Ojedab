$(document).ready(function () {
    let actual = 1;
    let total = 1;
    let limite = 50;

    function cargarPagos(buscar = '', pagina = 1) {
        $.ajax({
            url: 'api/pagos.php',
            method: 'GET',
            data: { buscar: buscar, pagina: pagina, limite: limite },
            success: function (data) {
                const tbody = $('#tabla-pagos tbody');
                tbody.empty();
                data.elementos.forEach(function (pago) {
                    const tieneComprobante = pago.id_compra && pago.id_compra !== '0' && pago.id_compra !== 'null';
                    
                    const comprobante = tieneComprobante
                        ? `<a href="generar_pdf.php?id_compra=${pago.id_compra}" class="ver-comprobante">Ver comprobante</a>`
                        : '';

                    const fila = `
                        <tr>
                            <td>${pago.razon_social}</td>
                            <td>${pago.rut}</td>
                            <td>${pago.monto}</td>
                            <td>${pago.fecha}</td>
                            <td>${pago.vencimiento ? pago.vencimiento : ''}</td>
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

    cargarPagos();

    setInterval(function () {
        cargarPagos($('#buscar').val(), actual);
    }, 5000);

    $('#buscar').on('input', function () {
        cargarPagos($(this).val(), 1);
    });

    $('.paginacion-contenedor button:first-child').click(function () {
        if (actual > 1) {
            cargarPagos($('#buscar').val(), actual - 1);
        }
    });

    $('.paginacion-contenedor button:last-child').click(function () {
        if (actual < total) {
            cargarPagos($('#buscar').val(), actual + 1);
        }
    });
});