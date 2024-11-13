<?php
include_once '../bd.php';

$buscar = '';
$pagina = 1;
$limite = 50;

if (isset($_GET['buscar'])) {
    $buscar = $_GET['buscar'];
}

if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
}

if (isset($_GET['limite'])) {
    $limite = $_GET['limite'];
}

if ($buscar != '') {
    $consulta = mysqli_query($bd, "SELECT id_cliente, cedula, nombre, deuda, fecha_nacimiento, boletos_sorteo, contacto, rut FROM cliente WHERE activo = 1 AND (cedula LIKE '%$buscar%' OR nombre LIKE '%$buscar%' OR deuda LIKE '%$buscar%' OR fecha_nacimiento LIKE '%$buscar%' OR boletos_sorteo LIKE '%$buscar%' OR contacto LIKE '%$buscar%' OR rut LIKE '%$buscar%')");
} else {
    $consulta = mysqli_query($bd, "SELECT id_cliente, cedula, nombre, deuda, fecha_nacimiento, boletos_sorteo, contacto, rut FROM cliente WHERE activo = 1");
}

$datos = [];

while ($fila = $consulta->fetch_assoc()) {
    $datos[] = $fila;
}

$total = count($datos);
$paginas = ceil($total / $limite);

if ($pagina < 1) {
    $pagina = 1;
}
if ($pagina > $paginas) {
    $pagina = $paginas;
}

$desplazar = ($pagina - 1) * $limite;
$elementos = array_slice($datos, $desplazar, $limite);

$resultado = [
    'elementos' => $elementos,
    'pagina' => $pagina,
    'limite' => $limite,
    'total' => $total,
    'paginas' => $paginas
];

header('Content-Type: application/json');
echo json_encode($resultado);
?>