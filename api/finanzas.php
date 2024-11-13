<?php
include_once '../bd.php';
include_once '../funciones.php';

$resultado = [];

    $resultado['ventas'] = getVentas($bd);
    $resultado['cobros'] = getCobros($bd);
    $resultado['compras'] = getCompras($bd);
    $resultado['pagos'] = getPagos($bd);


header('Content-Type: application/json');
echo json_encode($resultado);