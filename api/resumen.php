<?php
include_once '../bd.php';

$fechafinal = date('Y-m-d');
$fechainicial = date('Y-m-d', strtotime('-60 days'));

$consulta = mysqli_query($bd, "SELECT DATE(fecha) AS fecha, COUNT(*) AS total FROM Venta WHERE fecha BETWEEN '$fechainicial' AND '$fechafinal' GROUP BY DATE(fecha) ORDER BY fecha");

$fechas = [];
$datos = [];

while ($fila = $consulta->fetch_assoc()) {
    $fechas[] = $fila['fecha'];
    $datos[] = $fila['total'];
}

$resultado = [
    'fechas' => $fechas,
    'datos' => $datos
];

header('Content-Type: application/json');
echo json_encode($resultado);
?>