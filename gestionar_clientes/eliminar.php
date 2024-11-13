<?php
include_once '../bd.php';

$id_cliente = $_POST['id'];

$consulta = mysqli_query($bd, "UPDATE cliente SET activo = 0, cedula = NULL, rut = NULL WHERE id_cliente = $id_cliente");

?>
