<?php
function getVentas($bd) {
    $consulta = "SELECT COUNT(*) AS total FROM venta";
    $resultado = mysqli_query($bd, $consulta);
    $ventas = mysqli_fetch_assoc($resultado);
    return $ventas['total'];
}

function getCobros($bd) {
    $consulta = "SELECT COUNT(*) AS total FROM cobro";
    $resultado = mysqli_query($bd, $consulta);
    $cobros = mysqli_fetch_assoc($resultado);
    return $cobros['total'];
}

function getCompras($bd) {
    $consulta = "SELECT COUNT(*) AS total FROM compra";
    $resultado = mysqli_query($bd, $consulta);
    $compras = mysqli_fetch_assoc($resultado);
    return $compras['total'];
}

function getPagos($bd) {
    $consulta = "SELECT COUNT(*) AS total FROM pago";
    $resultado = mysqli_query($bd, $consulta);
    $pagos = mysqli_fetch_assoc($resultado);
    return $pagos['total'];
}

function getClientesCumpleaños($bd) {
    $fecha_actual = date('m-d');
    $consulta = "SELECT nombre FROM cliente WHERE DATE_FORMAT(fecha_nacimiento, '%m-%d') = '$fecha_actual'";
    $resultado = mysqli_query($bd, $consulta);
    $clientes = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $clientes[] = $fila['nombre'];
    }
    return $clientes;
}

function getFacturasPorVencer($bd) {
    $fecha_actual = date('Y-m-d');
    $consulta = "SELECT id_compra, precio, vencimiento FROM compra WHERE vencimiento > '$fecha_actual' ORDER BY vencimiento ASC";
    $resultado = mysqli_query($bd, $consulta);
    $facturas = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $facturas[] = $fila;
    }
    return $facturas;
}

function getProductosExistencias($bd) {
    $consulta = "SELECT nombre, cantidad, existencias_alerta FROM producto WHERE cantidad <= existencias_alerta ORDER BY cantidad ASC";
    $resultado = mysqli_query($bd, $consulta);
    $productos = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $productos[] = $fila;
    }
    return $productos;
}
?>