<?php
include_once '../bd.php';

$fechafinal = date('Y-m-d');
$fechainicial = date('Y-m-d', strtotime('-60 days'));

$consulta = mysqli_query($bd, "SELECT p.nombre, SUM(pv.cantidad) AS total FROM productos_vendidos pv JOIN producto p ON pv.id_producto = p.id_producto JOIN venta v ON pv.id_venta = v.id_venta WHERE v.fecha BETWEEN '$fechainicial' AND '$fechafinal' GROUP BY p.id_producto, p.nombre ORDER BY total DESC LIMIT 5");

$productos = [];
$cantidades = [];

while ($fila = mysqli_fetch_assoc($consulta)) {
    $productos[] = $fila['nombre'];
    $cantidades[] = $fila['total'];
}

$resultado = [
    'productos' => $productos,
    'cantidades' => $cantidades
];

header('Content-Type: application/json');
echo json_encode($resultado);