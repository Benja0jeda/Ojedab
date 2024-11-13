<?php
include_once '../chequeo.php';
include_once '../bd.php';

$proveedor = ['contacto' => '', 'razon_social' => '', 'rut' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $proveedor = ['contacto' => $_POST['contacto'], 'razon_social' => $_POST['razon_social'], 'rut' => $_POST['rut']];
    
    $consulta_rut = mysqli_query($bd, "SELECT * FROM proveedor WHERE rut = '{$proveedor['rut']}'");
    
    if (mysqli_num_rows($consulta_rut) > 0) {
        echo "<script>alert('Error: El RUT ya está registrado.');</script>";
    } else {
        $consulta = mysqli_query($bd, "INSERT INTO proveedor (contacto, razon_social, rut, activo) VALUES ('{$proveedor['contacto']}', '{$proveedor['razon_social']}', '{$proveedor['rut']}', 1)");

        if ($consulta) {
            header("Location: agregar.php");
            exit();
        } else {
            echo "<script>alert('Error al insertar el proveedor.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Proveedor</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
    function volver() {
        window.location.href = "../proveedores.php";
    }
    </script>
</head>

<body class="form">
    <form method="POST">
        <button type="button" class="volver" onclick="volver()">Volver</button>
        <div class="envoltorio">
            <h2>Agregar Proveedor</h2>
            <div class="entrada">
                <input type="text" id="contacto" name="contacto" placeholder="Contacto"
                    value="<?php echo $proveedor['contacto']; ?>" required>
            </div>
            <div class="entrada">
                <input type="text" id="razon_social" name="razon_social" placeholder="Razón social"
                    value="<?php echo $proveedor['razon_social']; ?>" required>
            </div>
            <div class="entrada">
                <input type="text" id="rut" name="rut" placeholder="RUT" maxlength="12"
                    value="<?php echo $proveedor['rut']; ?>" required>
            </div>
            <input type="submit" class="boton" value="Agregar">
        </div>
    </form>
</body>

</html>