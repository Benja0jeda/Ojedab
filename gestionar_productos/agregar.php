<?php
include_once '../chequeo.php';
include_once '../bd.php';

$producto = [
    'nombre' => '', 
    'precio_venta' => '', 
    'precio_compra' => '', // New field
    'cantidad' => '', // New field
    'codigo' => '', 
    'descripcion' => '', 
    'marca' => '', 
    'existencias_alerta' => '', 
    'id_iva' => '', 
    'id_categoria' => '', 
    'id_medida' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto = [
        'nombre' => $_POST['nombre'],
        'precio_venta' => $_POST['precio_venta'],
        'precio_compra' => $_POST['precio_compra'], // New field
        'cantidad' => $_POST['cantidad'], // New field
        'codigo' => $_POST['codigo'],
        'descripcion' => $_POST['descripcion'],
        'marca' => $_POST['marca'],
        'existencias_alerta' => $_POST['existencias_alerta'],
        'id_iva' => $_POST['id_iva'],
        'id_categoria' => $_POST['id_categoria'],
        'id_medida' => $_POST['id_medida']
    ];
    
    $consulta_codigo = mysqli_query($bd, "SELECT * FROM producto WHERE codigo = '{$producto['codigo']}'");
    
    if (mysqli_num_rows($consulta_codigo) > 0) {
        echo "<script>alert('Error: El código ya está registrado.');</script>";
    }
        $imagen_nombre = '';
        if ($_FILES['imagen']['name']) {
            $imagen_nombre = $_FILES['imagen']['name'];
            $imagen_tmp = $_FILES['imagen']['tmp_name'];
            $imagen_ruta = "../imgs/" . $imagen_nombre;
            move_uploaded_file($imagen_tmp, $imagen_ruta);
        }

        $consulta = mysqli_query($bd, "INSERT INTO producto (nombre, precio_venta, precio_compra, cantidad, codigo, descripcion, marca, existencias_alerta, imagen, activo, id_iva, id_categoria, id_medida) VALUES ('{$producto['nombre']}', '{$producto['precio_venta']}', '{$producto['precio_compra']}', '{$producto['cantidad']}', '{$producto['codigo']}', '{$producto['descripcion']}', '{$producto['marca']}', '{$producto['existencias_alerta']}', '$imagen_nombre', 1, '{$producto['id_iva']}', '{$producto['id_categoria']}', '{$producto['id_medida']}')");

        if ($consulta) {
            header("Location: agregar.php");
            exit();
        } else {
            echo "<script>alert('Error al insertar el producto.');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../libs/jquery/jquery-3.7.1.min.js"></script>
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
            <h2>Agregar Producto</h2>
            <div class="entrada">
                <input type="text" id="nombre" name="nombre" placeholder="Nombre"
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
                <input type="number" id="cantidad" name="cantidad" placeholder="Cantidad"
                    value="<?php echo $producto['cantidad']; ?>" required>
            </div>
            <div class="entrada">
                <input type="number" id="codigo" name="codigo" placeholder="Código"
                    value="<?php echo $producto['codigo']; ?>" required>
            </div>
            <div class="entrada">
                <input type="text" id="descripcion" name="descripcion" placeholder="Descripción"
                    value="<?php echo $producto['descripcion']; ?>" required>
            </div>
            <div class="entrada">
                <input type="text" id="marca" name="marca" placeholder="Marca" value="<?php echo $producto['marca']; ?>"
                    required>
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
                <input type="file" id="imagen" name="imagen">
            </div>
            <input type="submit" class="boton" value="Agregar">
        </div>
    </form>
    <script src="../js/ivas.js"></script>
    <script src="../js/categorias.js"></script>
    <script src="../js/medidas.js"></script>
</body>

</html>