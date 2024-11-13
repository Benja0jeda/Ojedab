<?php
include_once 'bd.php';
include_once 'chequeo.php';
include_once 'funciones.php';

$id_usuario = $_SESSION["id_usuario"];

$consulta = mysqli_query($bd, "SELECT nombre FROM usuario WHERE id_usuario = '$id_usuario'");
if ($fila = $consulta->fetch_assoc()) {
    $nombre = $fila['nombre'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="libs/jquery/jquery-3.7.1.min.js"></script>
    <title>Principal</title>
    <style>
        .contenido {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            margin: 20px;
        }

        p {
            margin: 20px;
        }

        .datos {
            width: 100%;
            max-width: 300px;
            height: 200px;
            background-color: #f0f0f0;
            overflow-y: auto;
            margin: 20px;
            padding: 10px;
        }

        @media (min-width: 600px) {
            .datos {
                display: inline-block;
                width: calc(33.33% - 20px);
            }

            .principal {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: space-around;
            }
        }
    </style>
</head>
<body>
    <?php include_once 'barra.php'; ?>
    <div class="principal">
        <div class="barra">
            <div class="alternar">
                <img src="imgs/icons/alternar.svg" alt="Alternar menú">
            </div>
            <?php include_once 'menu.php'; ?>
        </div>
        <div class="contenedor">
            <h1>Bienvenido <?php echo $nombre; ?></h1>
            <p id="fecha-actual"></p>
            <div class="datos" id="clientes-cumpleanos">
                <h2>Clientes de cumpleaños hoy</h2>
                <div id="lista-cumpleanos"></div>
            </div>
            <div class="datos" id="facturas-vencer">
                <h2>Facturas por vencer</h2>
                <div id="lista-facturas"></div>
            </div>
            <div class="datos" id="productos-stock">
                <h2>Productos con bajo stock</h2>
                <div id="lista-productos"></div>
            </div>
        </div>
    </div>
    <script src="js/principal.js"></script>
    <script src="js/script.js"></script>
</body>
</html>