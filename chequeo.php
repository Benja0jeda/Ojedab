<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>window.close();</script>";
    exit();
}
?>