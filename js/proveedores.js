$(document).ready(function () {
    let actual = 1;
    let total = 1;
    let limite = 50;

    function cargarProveedores(buscar = '', pagina = 1) {
        $.ajax({
            url: 'api/proveedores.php',
            method: 'GET',
            data: { buscar: buscar, pagina: pagina, limite: limite },
            success: function (data) {
                const tbody = $('#tabla-proveedores tbody');
                tbody.empty();
                data.elementos.forEach(function (proveedor) {
                    const fila = `
                        <tr>
                            <td>${proveedor.contacto}</td>
                            <td>${proveedor.razon_social}</td>
                            <td>${proveedor.deuda}</td>
                            <td>${proveedor.rut}</td>
                            <td>
                                <button class="editar" data-id="${proveedor.id_proveedor}">Editar</button>
                                <button class="eliminar" data-id="${proveedor.id_proveedor}">Eliminar</button>
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

    function eliminarProveedor(id) {
        if (confirm("¿Seguro que deseas eliminar este proveedor?")) {
            $.ajax({
                url: 'gestionar_proveedores/eliminar.php',
                method: 'POST',
                data: { id: id },
                success: function () {
                    cargarProveedores($('#buscar').val(), actual);
                }
            });
        }
    }

    function editarProveedor(id) {
        window.location.href = `gestionar_proveedores/editar.php?id=${id}`;
    }

    cargarProveedores();

    setInterval(function () {
        cargarProveedores($('#buscar').val(), actual);
    }, 5000);

    $('#buscar').on('input', function () {
        cargarProveedores($(this).val(), 1);
    });

    $(document).on('click', '.eliminar', function () {
        eliminarProveedor($(this).data('id'));
    });

    $(document).on('click', '.editar', function () {
        editarProveedor($(this).data('id'));
    });

    $('.paginacion-contenedor button:first-child').click(function () {
        if (actual > 1) {
            cargarProveedores($('#buscar').val(), actual - 1);
        }
    });

    $('.paginacion-contenedor button:last-child').click(function () {
        if (actual < total) {
            cargarProveedores($('#buscar').val(), actual + 1);
        }
    });
});