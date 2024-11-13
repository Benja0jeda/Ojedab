<?php
include_once '../bd.php';

$id_sorteo = $_POST['id'];

$consulta = mysqli_query($bd, "SELECT cantidad_ganadores FROM sorteo WHERE id_sorteo = $id_sorteo");
$fila = mysqli_fetch_assoc($consulta);
$cantidad_ganadores = $fila['cantidad_ganadores'];

$clientes = [];

$consulta = mysqli_query($bd, "SELECT id_cliente, boletos_sorteo FROM cliente WHERE boletos_sorteo > 0");
while ($fila = mysqli_fetch_assoc($consulta)) {
    $id_cliente = $fila['id_cliente'];
    $boletos = $fila['boletos_sorteo'];
    
    for ($i = 0; $i < $boletos; $i++) {
        $clientes[] = $id_cliente;
    }
}

shuffle($clientes);

$ganadores = array_unique(array_rand($clientes, $cantidad_ganadores));

foreach ($ganadores as $i) {
    $id_cliente = $clientes[$i];
    mysqli_query($bd, "INSERT INTO ganador (id_cliente, id_sorteo) VALUES ($id_cliente, $id_sorteo)");
}

mysqli_query($bd, "UPDATE cliente SET boletos_sorteo = 0");

$fecha = date("Y-m-d");
mysqli_query($bd, "UPDATE sorteo SET fecha = '$fecha' WHERE id_sorteo = $id_sorteo");
?>