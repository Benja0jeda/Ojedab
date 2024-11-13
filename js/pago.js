$(document).ready(function () {
    $('#seleccionar-proveedor').select2({
        placeholder: 'Buscar proveedor...',
        ajax: {
            url: 'api/proveedores.php',
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
                    results: data.elementos.map(function (proveedor) {
                        return {
                            id: proveedor.id_proveedor,
                            text: proveedor.razon_social + ' (' + proveedor.rut + ')'
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
        $('#id_proveedor').val(data.id);
    });

    $('#pagoForm').on('submit', function (e) {
        e.preventDefault();

        const proveedorId = $('#id_proveedor').val();
        const monto = $('#monto').val();

        if (!proveedorId) {
            alert('Por favor, selecciona un proveedor.');
            return;
        }

        if (!monto || monto <= 0) {
            alert('Por favor, ingresa un monto válido.');
            return;
        }

        this.submit();
    });
});