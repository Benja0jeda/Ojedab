<?php
include_once '../bd.php';

$id_proveedor = $_POST['id'];

$consulta = mysqli_query($bd, "UPDATE proveedor SET activo = 0, rut = NULL WHERE id_proveedor = $id_proveedor");

?>
