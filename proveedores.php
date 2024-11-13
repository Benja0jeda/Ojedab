<?php
include_once 'chequeo.php';

if (isset($_POST['agregar'])) {
    header("Location: gestionar_proveedores/agregar.php");
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
    <script src="libs/jquery/jquery-3.7.1.min.js"></script>
    <title>Proveedores</title>
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
            <h2>Proveedores</h2>
            <form method="POST">
                <button type="submit" name="agregar" class="agregar">Agregar</button>
            </form>
        </div>
        <div class="recipiente">
            <table class="contenido" id="tabla-proveedores">
                <thead>
                    <tr>
                        <th>Contacto</th>
                        <th>Razón Social</th>
                        <th>Deuda</th>
                        <th>RUT</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="paginacion-contenedor">
            <button>Anterior</button>
            <div class="paginacion"></div>
            <button>Siguiente</button>
        </div>
    </div>
    <script src="js/proveedores.js"></script>
    <script src="js/script.js"></script>
</body>

</html>