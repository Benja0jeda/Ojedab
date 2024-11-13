<?php
include_once 'bd.php';
include_once 'chequeo.php';
include_once 'funciones.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="libs/jquery/jquery-3.7.1.min.js"></script>
    <script src="libs/chart.js/chart.umd.js"></script>
    <title>Resumen</title>
</head>

<body>
<?php include_once 'barra.php'; ?>
    <div class="principal">
        <div class="barra">
            <div class="alternar">
                <img src="imgs/icons/alternar.svg">
            </div>
            <?php include_once 'menu.php'; ?>
        </div>

        <div class="caja">
            <div class="tarjeta tarjeta-1">
                <div>
                    <div class="numeros"></div>
                    <div class="nombres">Ventas</div>
                </div>
                <div class="icono-tarjeta">
                    <img src="imgs/icons/ventas.svg">
                </div>
            </div>

            <div class="tarjeta tarjeta-2">
                <div>
                    <div class="numeros"></div>
                    <div class="nombres">Cobros</div>
                </div>
                <div class="icono-tarjeta">
                    <img src="imgs/icons/cobros resumen.svg">
                </div>
            </div>

            <div class="tarjeta tarjeta-3">
                <div>
                    <div class="numeros"></div>
                    <div class="nombres">Compras</div>
                </div>
                <div class="icono-tarjeta">
                    <img src="imgs/icons/compras.svg">
                </div>
            </div>

            <div class="tarjeta tarjeta-4">
                <div>
                    <div class="numeros"></div>
                    <div class="nombres">Pagos</div>
                </div>
                <div class="icono-tarjeta">
                    <img src="imgs/icons/pagos resumen.svg">
                </div>
            </div>
        </div>

        <div class="con">
            <div class="sub">
                <h2>Productos más vendidos</h2>
                <canvas id="Productos" width="400" height="200"></canvas>
            </div>
            <div class="sub">
                <h2>Ventas por día</h2>
                <canvas id="Ventas" width="400" height="200"></canvas>
            </div>
        </div>

    <script src="js/vendidos.js"></script>
    <script src="js/finanzas.js"></script>
    <script src="js/resumen.js"></script>
    <script src="js/script.js"></script>
</script>
</body>
</html>