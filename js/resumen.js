$(document).ready(function() {
    var chart;

    function crearLine(data) {
        var ctx = $('#Ventas');
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.fechas,
                datasets: [{
                    label: 'Ventas por Fecha',
                    data: data.datos,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                animation: {
                    duration: 0
                },
                hover: {
                    animationDuration: 0
                },
                responsiveAnimationDuration: 0
            }
        });
    }

    function cargarLine() {
        $.ajax({
            url: 'api/resumen.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (!chart) {
                    crearLine(data);
                } else {
                    chart.data.labels = data.fechas;
                    chart.data.datasets[0].data = data.datos;
                    chart.update();
                }
            }
        });
    }

    cargarLine();
    setInterval(cargarLine, 5000);
});