<?php
include_once '../bd.php';

$consulta = mysqli_query($bd, "SELECT id_categoria, titulo FROM categoria");

$datos = [];

while ($fila = $consulta->fetch_assoc()) {
    $datos[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($datos);
?>
