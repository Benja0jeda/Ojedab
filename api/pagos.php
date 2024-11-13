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
    $consulta = mysqli_query($bd, "SELECT p.id_pago, p.monto, p.fecha, p.id_compra, pr.razon_social, pr.rut, c.vencimiento FROM pago p LEFT JOIN proveedor pr ON p.id_proveedor = pr.id_proveedor LEFT JOIN compra c ON p.id_compra = c.id_compra WHERE pr.activo = 1 AND (pr.razon_social LIKE '%$buscar%' OR pr.rut LIKE '%$buscar%' OR p.fecha LIKE '%$buscar%' OR c.vencimiento LIKE '%$buscar%')");
} else {
    $consulta = mysqli_query($bd, "SELECT p.id_pago, p.monto, p.fecha, p.id_compra, pr.razon_social, pr.rut, c.vencimiento FROM pago p LEFT JOIN proveedor pr ON p.id_proveedor = pr.id_proveedor LEFT JOIN compra c ON p.id_compra = c.id_compra WHERE pr.activo = 1");
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
