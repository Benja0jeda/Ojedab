<?php
include_once '../bd.php';

$consulta = mysqli_query($bd, "SELECT id_medida, unidad FROM medida");

$datos = [];

while ($fila = $consulta->fetch_assoc()) {
    $datos[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($datos);
?>
