<?php
include_once '../chequeo.php';
include_once '../bd.php';

$sorteo = ['premio' => '', 'cantidad_ganadores' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sorteo = ['premio' => $_POST['premio'], 'cantidad_ganadores' => $_POST['cantidad_ganadores']];
    
    $consulta = mysqli_query($bd, "INSERT INTO sorteo (premio, cantidad_ganadores, activo) VALUES ('{$sorteo['premio']}', {$sorteo['cantidad_ganadores']}, 1)");

    if ($consulta) {
        header("Location: agregar.php");
        exit();
    } else {
        echo "<script>alert('Error al insertar el sorteo.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Sorteo</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
    function volver() {
        window.location.href = "../sorteos.php";
    }
    </script>
</head>

<body class="form">
    <form method="POST">
        <button type="button" class="volver" onclick="volver()">Volver</button>
        <div class="envoltorio">
            <h2>Agregar Sorteo</h2>
            <div class="entrada">
                <input type="text" id="premio" name="premio" placeholder="Premio"
                    value="<?php echo $sorteo['premio']; ?>" required>
            </div>
            <div class="entrada">
                <input type="number" id="cantidad_ganadores" name="cantidad_ganadores"
                    placeholder="Cantidad de ganadores" min="1" value="<?php echo $sorteo['cantidad_ganadores']; ?>"
                    required>
            </div>
            <input type="submit" class="boton" value="Agregar">
        </div>
    </form>
</body>

</html>