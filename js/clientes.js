$(document).ready(function () {
    let actual = 1;
    let total = 1;
    let limite = 50;

    function cargarClientes(buscar = '', pagina = 1) {
        $.ajax({
            url: 'api/clientes.php',
            method: 'GET',
            data: { buscar: buscar, pagina: pagina, limite: limite },
            success: function (data) {
                const tbody = $('#tabla-clientes tbody');
                tbody.empty();
                data.elementos.forEach(function (cliente) {
                    const fila = `
                        <tr>
                            <td>${cliente.cedula}</td>
                            <td>${cliente.nombre}</td>
                            <td>${cliente.deuda}</td>
                            <td>${cliente.fecha_nacimiento}</td>
                            <td>${cliente.boletos_sorteo}</td>
                            <td>${cliente.contacto}</td>
                            <td>${cliente.rut}</td>
                            <td>
                                <button class="editar" data-id="${cliente.id_cliente}">Editar</button>
                                <button class="eliminar" data-id="${cliente.id_cliente}">Eliminar</button>
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

    function eliminarCliente(id) {
        if (confirm("¿Seguro que deseas eliminar este cliente?")) {
            $.ajax({
                url: 'gestionar_clientes/eliminar.php',
                method: 'POST',
                data: { id: id },
                success: function () {
                    cargarClientes($('#buscar').val(), actual);
                }
            });
        }
    }

    function editarCliente(id) {
        window.location.href = `gestionar_clientes/editar.php?id=${id}`;
    }

    cargarClientes();

    setInterval(function () {
        cargarClientes($('#buscar').val(), actual);
    }, 5000);

    $('#buscar').on('input', function () {
        cargarClientes($(this).val(), 1);
    });

    $(document).on('click', '.eliminar', function () {
        eliminarCliente($(this).data('id'));
    });

    $(document).on('click', '.editar', function () {
        editarCliente($(this).data('id'));
    });

    $('.paginacion-contenedor button:first-child').click(function () {
        if (actual > 1) {
            cargarClientes($('#buscar').val(), actual - 1);
        }
    });

    $('.paginacion-contenedor button:last-child').click(function () {
        if (actual < total) {
            cargarClientes($('#buscar').val(), actual + 1);
        }
    });
});