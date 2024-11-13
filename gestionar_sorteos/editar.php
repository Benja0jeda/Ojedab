<?php
include_once '../chequeo.php';
include_once '../bd.php';

if (isset($_POST['volver'])) {
    header("Location: ../sorteos.php");
    exit();
}

$id_sorteo = $_GET['id'];

$consulta = mysqli_query($bd, "SELECT * FROM sorteo WHERE activo = 1 AND id_sorteo = $id_sorteo");
if ($consulta->num_rows > 0) {
    $sorteo = $consulta->fetch_assoc();
} else {
    header("Location: ../sorteos.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sorteo['premio'] = $_POST['premio'];
    $sorteo['cantidad_ganadores'] = $_POST['cantidad_ganadores'];
    
    $consulta = mysqli_query($bd, "UPDATE sorteo SET premio = '{$sorteo['premio']}', cantidad_ganadores = {$sorteo['cantidad_ganadores']} WHERE id_sorteo = $id_sorteo");

    if ($consulta) {
        header("Location: editar.php?id=" . $id_sorteo);
        exit();
    } else {
        echo "<script>alert('Error al actualizar el sorteo.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sorteo</title>
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
            <h2>Editar Sorteo</h2>
            <div class="entrada">
                <input type="text" id="premio" name="premio" placeholder="Premio"
                    value="<?php echo $sorteo['premio']; ?>" required>
            </div>
            <div class="entrada">
                <input type="number" id="cantidad_ganadores" name="cantidad_ganadores"
                    placeholder="Cantidad de ganadores" min="1" value="<?php echo $sorteo['cantidad_ganadores']; ?>"
                    required>
            </div>
            <input type="submit" class="boton" value="Actualizar">
        </div>
    </form>
</body>

</html>