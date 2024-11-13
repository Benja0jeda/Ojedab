<?php
include_once '../chequeo.php';
include_once '../bd.php';

$id_producto = $_GET['id'];

$consulta = mysqli_query($bd, "SELECT * FROM producto WHERE activo = 1 AND id_producto = $id_producto");
if ($consulta->num_rows > 0) {
    $producto = $consulta->fetch_assoc();
} else {
    header("Location: ../productos.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto['nombre'] = $_POST['nombre'];
    $producto['precio_venta'] = $_POST['precio_venta'];
    $producto['precio_compra'] = $_POST['precio_compra'];
    $producto['codigo'] = $_POST['codigo'];
    $producto['descripcion'] = $_POST['descripcion'];
    $producto['marca'] = $_POST['marca'];
    $producto['cantidad'] = $_POST['cantidad'];
    $producto['existencias_alerta'] = $_POST['existencias_alerta'];
    $producto['id_iva'] = $_POST['id_iva'];
    $producto['id_categoria'] = $_POST['id_categoria'];
    $producto['id_medida'] = $_POST['id_medida'];
    
    $consulta_codigo = mysqli_query($bd, "SELECT * FROM producto WHERE codigo = '{$producto['codigo']}' AND id_producto != $id_producto");
    
    if (mysqli_num_rows($consulta_codigo) > 0) {
        echo "<script>alert('Error: El código ya está registrado.');</script>";
        $error = true;
    } else {
        if ($_FILES['imagen']['name']) {
            $imagen_nombre = $_FILES['imagen']['name'];
            $imagen_tmp = $_FILES['imagen']['tmp_name'];
            $imagen_ruta = "../imgs/" . $imagen_nombre;
            move_uploaded_file($imagen_tmp, $imagen_ruta);
        } else {
            $imagen_nombre = $_POST['imagen_actual'];
        }

        $consulta = mysqli_query($bd, "UPDATE producto SET nombre = '{$producto['nombre']}', precio_venta = '{$producto['precio_venta']}', precio_compra = '{$producto['precio_compra']}', codigo = '{$producto['codigo']}', descripcion = '{$producto['descripcion']}', marca = '{$producto['marca']}', cantidad = '{$producto['cantidad']}', existencias_alerta = '{$producto['existencias_alerta']}', imagen = '$imagen_nombre', id_iva = '{$producto['id_iva']}', id_categoria = '{$producto['id_categoria']}', id_medida = '{$producto['id_medida']}' WHERE id_producto = $id_producto");

        if ($consulta) {
            header("Location: editar.php?id=" . $id_producto);
            exit();
        } else {
            echo "<script>alert('Error al actualizar el producto.');</script>";
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
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../libs/select2/css/select2.min.css">
    <script src="../libs/jquery/jquery-3.7.1.min.js"></script>
    <script src="../libs/select2/js/select2.min.js"></script>
    <script>
    function volver() {
        window.location.href = "../productos.php";
    }
    </script>
</head>

<body class="form">
    <form method="POST" enctype="multipart/form-data">
        <button type="button" class="volver" onclick="volver()">Volver</button>
        <div class="envoltorio">
            <h2>Editar Producto</h2>
            <div class="entrada">
                <input type="text" id="nombre" name="nombre" placeholder="Nombre" maxlength="100"
                    value="<?php echo $producto['nombre']; ?>" required>
            </div>
            <div class="entrada">
                <input type="number" id="precio_venta" name="precio_venta" placeholder="Precio de Venta" step="0.01"
                    value="<?php echo $producto['precio_venta']; ?>" required>
            </div>
            <div class="entrada">
                <input type="number" id="precio_compra" name="precio_compra" placeholder="Precio de Compra" step="0.01"
                    value="<?php echo $producto['precio_compra']; ?>" required>
            </div>
            <div class="entrada">
                <input type="number" id="codigo" name="codigo" placeholder="Código" maxlength="50"
                    value="<?php echo $producto['codigo']; ?>" required>
            </div>
            <div class="entrada">
                <input type="text" id="descripcion" name="descripcion" placeholder="Descripción"
                    value="<?php echo $producto['descripcion']; ?>" required>
            </div>
            <div class="entrada">
                <input type="text" id="marca" name="marca" placeholder="Marca" maxlength="50" value="<?php echo $producto['marca']; ?>"
                    required>
            </div>
            <div class="entrada">
                <input type="number" id="cantidad" name="cantidad" placeholder="Cantidad"
                    value="<?php echo $producto['cantidad']; ?>" required>
            </div>
            <div class="entrada">
                <input type="number" id="existencias_alerta" name="existencias_alerta"
                    placeholder="Aviso de existencias mínimo" value="<?php echo $producto['existencias_alerta']; ?>"
                    required>
            </div>
            <div class="entrada">
                <select id="id_iva" name="id_iva" required>
                    <option value="">IVA</option>
                </select>
            </div>
            <div class="entrada">
                <select id="id_categoria" name="id_categoria" required>
                    <option value="">Categoría</option>
                </select>
            </div>
            <div class="entrada">
                <select id="id_medida" name="id_medida" required>
                    <option value="">Medida</option>
                </select>
            </div>
            <div class="entrada">
                <?php if (!empty($producto['imagen'])) { ?>
                <img src="../imgs/<?php echo $producto['imagen']; ?>" alt="Imagen del producto" width="150">
                <?php } ?>
            </div>
            <div class="entrada">
                <input type="file" id="imagen" name="imagen">
                <input type="hidden" name="imagen_actual" value="<?php echo $producto['imagen']; ?>">
            </div>
            <input type="submit" class="boton" value="Actualizar">
        </div>
    </form>
    <script src="../js/ivas.js"></script>
    <script src="../js/categorias.js"></script>
    <script src="../js/medidas.js"></script>
</body>

</html>