<?php
include_once '../bd.php';

$buscar = '';
$pagina = 1;
$limite = 50;

if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];

    $consulta = mysqli_query($bd, "SELECT p.id_producto, p.nombre, p.precio_compra, p.precio_venta, p.codigo, p.descripcion, p.marca, p.cantidad, p.imagen, p.id_iva, i.valor FROM producto p LEFT JOIN iva i ON p.id_iva = i.id_iva WHERE p.id_producto = '$id_producto' AND p.activo = 1");
    $fila = mysqli_fetch_assoc($consulta);
    
    header('Content-Type: application/json');
    echo json_encode($fila);
    exit();
}

if (isset($_GET['buscar'])) {
    $buscar = $_GET['buscar'];
}

if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
}

if (isset($_GET['limite'])) {
    $limite = $_GET['limite'];
}

if ($buscar != '') {
    $consulta = mysqli_query($bd, "SELECT p.id_producto, p.nombre, p.precio_compra, p.precio_venta, p.codigo, p.descripcion, p.marca, p.cantidad, p.imagen, p.id_iva, i.valor FROM producto p LEFT JOIN iva i ON p.id_iva = i.id_iva WHERE activo = 1 AND (p.nombre LIKE '%$buscar%' OR p.codigo LIKE '%$buscar%' OR p.descripcion LIKE '%$buscar%' OR p.marca LIKE '%$buscar%')");
} else {
    $consulta = mysqli_query($bd, "SELECT p.id_producto, p.nombre, p.precio_compra, p.precio_venta, p.codigo, p.descripcion, p.marca, p.cantidad, p.imagen, p.id_iva, i.valor FROM producto p LEFT JOIN iva i ON p.id_iva = i.id_iva WHERE activo = 1");
}

$datos = [];
while ($fila = $consulta->fetch_assoc()) {
    $datos[] = $fila;
}

$total = count($datos);
$paginas = ceil($total / $limite);

if ($pagina < 1) {
    $pagina = 1;
}
if ($pagina > $paginas) {
    $pagina = $paginas;
}

$desplazar = ($pagina - 1) * $limite;
$elementos = array_slice($datos, $desplazar, $limite);

$resultado = [
    'elementos' => $elementos,
    'pagina' => $pagina,
    'limite' => $limite,
    'total' => $total,
    'paginas' => $paginas
];

header('Content-Type: application/json');
echo json_encode($resultado);
?>