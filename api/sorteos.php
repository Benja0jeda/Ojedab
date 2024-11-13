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
    $consulta = mysqli_query($bd, "SELECT s.id_sorteo, s.premio, s.cantidad_ganadores, s.fecha, GROUP_CONCAT(c.nombre SEPARATOR ', <br> ') AS ganadores FROM sorteo s LEFT JOIN ganador g ON s.id_sorteo = g.id_sorteo LEFT JOIN cliente c ON g.id_cliente = c.id_cliente WHERE s.activo = 1 AND (s.premio LIKE '%$buscar%' OR s.fecha LIKE '%$buscar%') GROUP BY s.id_sorteo");
} else {
    $consulta = mysqli_query($bd, "SELECT s.id_sorteo, s.premio, s.cantidad_ganadores, s.fecha, GROUP_CONCAT(c.nombre SEPARATOR ', <br> ') AS ganadores FROM sorteo s LEFT JOIN ganador g ON s.id_sorteo = g.id_sorteo LEFT JOIN cliente c ON g.id_cliente = c.id_cliente WHERE s.activo = 1 GROUP BY s.id_sorteo");
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