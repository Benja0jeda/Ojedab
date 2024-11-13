<?php
include_once '../bd.php';

$id_sorteo = $_POST['id'];

$consulta = mysqli_query($bd,"SELECT COUNT(*) AS ganadores FROM ganador WHERE id_sorteo = $id_sorteo");
$fila = mysqli_fetch_assoc($consulta);
$ganadores = $fila['ganadores'];

if ($ganadores == 0) {
    mysqli_query($bd, "DELETE FROM sorteo WHERE id_sorteo = $id_sorteo");
} else {
    mysqli_query($bd, "UPDATE sorteo SET activo = 0 WHERE id_sorteo = $id_sorteo");
}
?>
