<?php
include_once 'chequeo.php';

if (isset($_POST['realizar-venta'])) {
    header("Location: gestionar_ventas/confirmar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/table-styles.css">
    <link rel="stylesheet" href="libs/select2/css/select2.min.css">
    <script src="libs/jquery/jquery-3.7.1.min.js"></script>
    <script src="libs/select2/js/select2.min.js"></script>
    <title>Ingresar Venta</title>
</head>

<body>
    <?php include_once 'barra.php'; ?>
    <div class="principal">
        <div class="barra">
            <div class="alternar">
                <img src="imgs/icons/alternar.svg">
            </div>
            <div class="buscar">
                <label>
                    <input type="text" placeholder="Buscar aquí" id="buscar">
                    <img src="imgs/icons/buscar.svg">
                </label>
            </div>
            <?php include_once 'menu.php'; ?>
        </div>

        <div class="tabla-encabezado">
            <h2>Ingresar Venta</h2>
            <div class="confirmar">
                <img src="imgs/icons/confirmar.svg">
            </div>
        </div>
        <div class="recipiente">
            <table class="contenido" id="tabla-productos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Precio de Venta</th>
                        <th>Código de Barras</th>
                        <th>Descripción</th>
                        <th>Marca</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="paginacion-contenedor">
            <button id="anterior">Anterior</button>
            <div class="paginacion"></div>
            <button id="siguiente">Siguiente</button>
        </div>

        <div class="superposicion"></div>
        <div id="panel-transaccion">
            <button class="cerrar-panel">&times;</button>
            <h3>Detalles de la Venta</h3>
            <form id="formulario-venta" method="POST" enctype="multipart/form-data">
                <select id="seleccionar-cliente" style="width: 100%;"></select>
                <input type="hidden" id="id_cliente" name="id_cliente" />
                <input type="hidden" id="ids_productos" name="ids_productos" />
                <table id="tabla-transaccion">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio de Venta</th>
                            <th>Cantidad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <button type="submit" id="realizar-venta" name="realizar-venta">Realizar Venta</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="js/venta.js"></script>
</body>

</html>