$(document).ready(function() {
    $('#seleccionar-cliente').select2({
        placeholder: 'Buscar cliente...',
        ajax: {
            url: 'api/clientes.php',
            delay: 250,
            data: function (params) {
                return {
                    buscar: params.term,
                    pagina: params.page || 1,
                    limite: 50
                };
            },
            processResults: function (data) {
                return {
                    results: data.elementos.map(function (cliente) {
                        return {
                            id: cliente.id_cliente,
                            text: cliente.nombre + ' (' + cliente.cedula + ')'
                        };
                    }),
                    pagination: {
                        more: data.pagina < data.paginas
                    }
                };
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        var data = e.params.data;
        $('#id_cliente').val(data.id);
    });

    $('#cobroForm').on('submit', function(e) {
        e.preventDefault();
        
        const clienteId = $('#id_cliente').val();
        const monto = $('#monto').val();

        if (!clienteId) {
            alert('Por favor, selecciona un cliente.');
            return;
        }

        if (!monto || monto <= 0) {
            alert('Por favor, ingresa un monto válido.');
            return;
        }

        this.submit();
    });
});