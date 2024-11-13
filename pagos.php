<?php
include_once 'bd.php';
include_once 'chequeo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['monto']) && isset($_POST['id_proveedor'])) {
        $monto = $_POST["monto"];
        $id_proveedor = $_POST["id_proveedor"];
        $fecha = date("Y-m-d");

        mysqli_query($bd, "INSERT INTO pago (monto, id_proveedor, fecha) VALUES ('$monto', '$id_proveedor', '$fecha')");

        $result = mysqli_query($bd, "SELECT deuda FROM proveedor WHERE id_proveedor ='$id_proveedor'");
        $deudaanterior = mysqli_fetch_assoc($result);
        $deudaactual = $deudaanterior["deuda"] - $monto;

        mysqli_query($bd, "UPDATE proveedor SET deuda='$deudaactual' WHERE id_proveedor ='$id_proveedor'");

        header("Location: pagos.php?opcion=pagoingresado");
        exit();
    }
    if (isset($_POST['compra'])) {
        header("Location: compra.php");
        exit();
    }
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
    <title>Pagos</title>
    <style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 300px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    </style>
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
            <h2>Pagos</h2>
            <form method="POST">
                <button type="submit" name="compra" class="compra">Ingresar Compra</button>
                <button type="button" id="ingresarPago" class="pago">Ingresar Pago</button>
            </form>
        </div>
        <div class="recipiente">
            <table class="contenido" id="tabla-pagos">
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th>RUT</th>
                        <th>Monto</th>
                        <th>Fecha de Pago</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Comprobante</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table content will be dynamically populated by JavaScript -->
                </tbody>
            </table>
        </div>
        <div class="paginacion-contenedor">
            <button id="anterior">Anterior</button>
            <div class="paginacion"></div>
            <button id="siguiente">Siguiente</button>
        </div>

        <div id="pagoModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Ingresar Pago</h2>
                <form method="POST" id="pagoForm">
                    <label for="monto">Monto</label>
                    <input type="number" id="monto" name="monto" min="1" max="1000000" required>

                    <label for="seleccionar-proveedor">Seleccionar Proveedor</label>
                    <select id="seleccionar-proveedor" style="width: 100%;"></select>
                    <input type="hidden" id="id_proveedor" name="id_proveedor" />

                    <input type="submit" value="Agregar Pago">
                </form>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="js/pagos.js"></script>
    <script src="js/pago.js"></script>
</body>

</html>
?>