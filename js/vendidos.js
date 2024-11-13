$(document).ready(function() {
    var chart;

    function crearPie(data) {
        var ctx = $('#Productos');
        chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: data.productos,
                datasets: [{
                    data: data.cantidades,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    function cargarPie() {
        $.ajax({
            url: 'api/vendidos.php',
            method: 'GET',
            success: function(data) {
                if (!chart) {
                    crearPie(data);
                } else {
                    chart.data.labels = data.productos;
                    chart.data.datasets[0].data = data.cantidades;
                    chart.update({
                        duration: 1000,
                        easing: 'easeOutQuart'
                    });
                }
            }
        });
    }

    cargarPie();
    setInterval(cargarPie, 5000);
});