<?php
include_once 'bd.php';
$id_usuario = $_SESSION["id_usuario"];

$consulta = mysqli_query($bd, "SELECT nombre FROM usuario WHERE id_usuario = '$id_usuario'");
if ($fila = $consulta->fetch_assoc()) {
    $nombre = $fila['nombre'];
}
?>

<div class="accion">
    <div class="perfil">
        <img src="imgs/icons/Perfil.png" alt="Perfil">
    </div>
    <div class="desplegar">
        <h3> <?php echo $nombre; ?> </h3>
        <ul>
            <li><img src="imgs/icons/cerrar.svg" alt="Cerrar"><a href="cerrar.php">Cerrar sesión</a></li>
        </ul>
    </div>
</div>