<?php
include_once '../bd.php';
include_once '../funciones.php';

$fecha = new DateTime();
$dia = $fecha->format('j');
$mes_ingles = $fecha->format('F');
$año = $fecha->format('Y');

$meses = [
    'January' => 'Enero',
    'February' => 'Febrero',
    'March' => 'Marzo',
    'April' => 'Abril',
    'May' => 'Mayo',
    'June' => 'Junio',
    'July' => 'Julio',
    'August' => 'Agosto',
    'September' => 'Septiembre',
    'October' => 'Octubre',
    'November' => 'Noviembre',
    'December' => 'Diciembre'
];

$mes = $meses[$mes_ingles];

$clientes_cumpleaños = getClientesCumpleaños($bd);
$facturas_por_vencer = getFacturasPorVencer($bd);
$productos_existencias = getProductosExistencias($bd);

$resultado = [
    'fecha' => "Hoy es $dia de $mes de $año",
    'clientes_cumpleaños' => $clientes_cumpleaños,
    'facturas_por_vencer' => $facturas_por_vencer,
    'productos_existencias' => $productos_existencias
];

header('Content-Type: application/json');
echo json_encode($resultado);
exit;
?>
