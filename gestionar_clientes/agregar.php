<?php
include_once '../chequeo.php';
include_once '../bd.php';

$cliente = ['cedula' => '', 'nombre' => '', 'fecha_nacimiento' => '', 'contacto' => '', 'rut' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = ['cedula' => $_POST['cedula'], 'nombre' => $_POST['nombre'], 'fecha_nacimiento' => $_POST['fecha_nacimiento'], 'contacto' => $_POST['contacto'], 'rut' => $_POST['rut']];
    
    $consulta_cedula = mysqli_query($bd, "SELECT * FROM cliente WHERE cedula = '{$cliente['cedula']}'");
    
    if (mysqli_num_rows($consulta_cedula) > 0) {
        echo "<script>alert('Error: La cédula ya está registrada.');</script>";
    } else {
        $consulta = mysqli_query($bd, "INSERT INTO cliente (cedula, nombre, fecha_nacimiento, contacto, rut, activo) VALUES ('{$cliente['cedula']}', '{$cliente['nombre']}', '{$cliente['fecha_nacimiento']}', '{$cliente['contacto']}', '{$cliente['rut']}', 1)");

        if ($consulta) {
            header("Location: agregar.php");
            exit();
        } else {
            echo "<script>alert('Error al insertar el cliente.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cliente</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
    function volver() {
        window.location.href = "../clientes.php";
    }
    </script>
</head>

<body class="form">
    <form method="POST">
        <button type="button" class="volver" onclick="volver()">Volver</button>
        <div class="envoltorio">
            <h2>Agregar Cliente</h2>
            <div class="entrada">
                <input type="text" id="cedula" name="cedula" placeholder="Cédula"
                    value="<?php echo $cliente['cedula']; ?>" required>
            </div>
            <div class="entrada">
                <input type="text" id="nombre" name="nombre" placeholder="Nombre"
                    value="<?php echo $cliente['nombre']; ?>" required>
            </div>
            <div class="entrada">
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                    value="<?php echo $cliente['fecha_nacimiento']; ?>" required>
            </div>
            <div class="entrada">
                <input type="text" id="contacto" name="contacto" placeholder="Contacto"
                    value="<?php echo $cliente['contacto']; ?>" required>
            </div>
            <div class="entrada">
                <input type="text" id="rut" name="rut" placeholder="RUT" maxlength="12"
                    value="<?php echo $cliente['rut']; ?>">
            </div>
            <input type="submit" class="boton" value="Agregar">
        </div>
    </form>
</body>

</html>