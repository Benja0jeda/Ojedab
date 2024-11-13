<?php
include_once '../bd.php';

$id_producto = $_POST['id'];

$consulta = mysqli_query($bd, "UPDATE producto SET activo = 0, codigo = NULL WHERE id_producto = $id_producto");

?>
