$(document).ready(function () {
    let actual = 1;
    let total = 1;
    let limite = 50;

    function cargarProductos(buscar = '', pagina = 1) {
        $.ajax({
            url: 'api/productos.php',
            method: 'GET',
            data: { buscar: buscar, pagina: pagina, limite: limite },
            success: function (data) {
                const tbody = $('#tabla-productos tbody');
                tbody.empty();
                data.elementos.forEach(function (producto) {
                    const fila = `
                        <tr>
                            <td>${producto.nombre}</td>
                            <td>${producto.precio_compra}</td>
                            <td>${producto.precio_venta}</td>
                            <td>${producto.codigo}</td>
                            <td>${producto.descripcion}</td>
                            <td>${producto.marca}</td>
                            <td>${producto.cantidad}</td>
                            <td><img src="imgs/${producto.imagen}" class="producto-imagen"></td>
                            <td>
                                <button class="editar" data-id="${producto.id_producto}">Editar</button>
                                <button class="eliminar" data-id="${producto.id_producto}">Eliminar</button>
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

    function eliminarProducto(id) {
        if (confirm("¿Seguro que deseas eliminar este producto?")) {
            $.ajax({
                url: 'gestionar_productos/eliminar.php',
                method: 'POST',
                data: { id: id },
                success: function () {
                    cargarProductos($('#buscar').val(), actual);
                }
            });
        }
    }

    function editarProducto(id) {
        window.location.href = `gestionar_productos/editar.php?id=${id}`;
    }

    cargarProductos();

    setInterval(function () {
        cargarProductos($('#buscar').val(), actual);
    }, 5000);

    $('#buscar').on('input', function () {
        cargarProductos($(this).val(), 1);
    });

    $(document).on('click', '.eliminar', function () {
        eliminarProducto($(this).data('id'));
    });

    $(document).on('click', '.editar', function () {
        editarProducto($(this).data('id'));
    });

    $('.paginacion-contenedor button:first-child').click(function () {
        if (actual > 1) {
            cargarProductos($('#buscar').val(), actual - 1);
        }
    });

    $('.paginacion-contenedor button:last-child').click(function () {
        if (actual < total) {
            cargarProductos($('#buscar').val(), actual + 1);
        }
    });
});
