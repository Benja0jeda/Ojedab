<?php
include_once '../chequeo.php';
include_once '../bd.php';

$id_proveedor = $_GET['id'];

$consulta = mysqli_query($bd, "SELECT * FROM proveedor WHERE activo = 1 AND id_proveedor = $id_proveedor");
if ($consulta->num_rows > 0) {
    $proveedor = $consulta->fetch_assoc();
} else {
    header("Location: ../proveedores.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $proveedor['contacto'] = $_POST['contacto'];
    $proveedor['razon_social'] = $_POST['razon_social'];
    $proveedor['rut'] = $_POST['rut'];
    
    $consulta_rut = mysqli_query($bd, "SELECT * FROM proveedor WHERE rut = '{$proveedor['rut']}' AND id_proveedor != $id_proveedor");
    
    if (mysqli_num_rows($consulta_rut) > 0) {
        echo "<script>alert('Error: El RUT ya está registrado.');</script>";
        $error = true;
    } else {
        $consulta = mysqli_query($bd, "UPDATE proveedor SET contacto = '{$proveedor['contacto']}', razon_social = '{$proveedor['razon_social']}', rut = '{$proveedor['rut']}' WHERE id_proveedor = $id_proveedor");

        if ($consulta) {
            header("Location: editar.php?id=" . $id_proveedor);
            exit();
        } else {
            echo "<script>alert('Error al actualizar el proveedor.');</script>";
            $error = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
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
            <h2>Editar Proveedor</h2>
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
            <input type="submit" class="boton" value="Actualizar">
        </div>
    </form>
</body>

</html>