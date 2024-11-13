<?php
include_once '../bd.php';
include_once '../chequeo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["monto_pagado"]) && isset($_POST["cliente_id"]) && isset($_POST["productos_ids"]) && isset($_POST["cantidades"])) {
        $monto_pagado = $_POST["monto_pagado"];
        $cliente_id = $_POST["cliente_id"];
        $productos_ids = explode(',', $_POST["productos_ids"]);
        $cantidades = explode(',', $_POST["cantidades"]);
        $total = $_POST["total"];
        $subtotal = $_POST["subtotal"];
        $fecha_venta = date("Y-m-d");

        $consulta = mysqli_query($bd, "INSERT INTO venta (precio, fecha, id_cliente, subtotal) VALUES ('$total', '$fecha_venta', '$cliente_id', '$subtotal')");
        $id_venta = mysqli_insert_id($bd);

        foreach ($productos_ids as $i => $producto_id) {
            $cantidad = $cantidades[$i];
            
            $consulta = mysqli_query($bd, "SELECT p.precio_venta, p.cantidad, i.valor as iva_valor 
                                         FROM producto p 
                                         JOIN iva i ON p.id_iva = i.id_iva 
                                         WHERE p.id_producto = '$producto_id'");
            $producto = mysqli_fetch_assoc($consulta);
            
            $precio_venta = $producto['precio_venta'];
            $iva_valor = $producto['iva_valor'];
            
            $iva_producto = ($precio_venta * $cantidad) * ($iva_valor / 100);
            
            $consulta = mysqli_query($bd, "INSERT INTO productos_vendidos 
                                         (id_venta, id_producto, cantidad, precio_venta, iva_de_venta) 
                                         VALUES 
                                         ('$id_venta', '$producto_id', '$cantidad', '$precio_venta', '$iva_producto')");
            
            $nueva_cantidad = $producto['cantidad'] - $cantidad;
            $consulta = mysqli_query($bd, "UPDATE producto SET cantidad = '$nueva_cantidad' WHERE id_producto = '$producto_id'");
        }

        $consulta = mysqli_query($bd, "INSERT INTO cobro (monto, fecha, id_cliente, id_venta) VALUES ('$monto_pagado', '$fecha_venta', '$cliente_id', '$id_venta')");

        $deuda = $total - $monto_pagado;
        if ($deuda > 0) {
            $consulta = mysqli_query($bd, "UPDATE cliente SET deuda = deuda + '$deuda' WHERE id_cliente = '$cliente_id'");
        }

        $consulta = mysqli_query($bd, "SELECT precio_boletos FROM configuracion");
        $configuracion = mysqli_fetch_assoc($consulta);

        if (isset($configuracion['precio_boletos']) && $configuracion['precio_boletos'] > 0) {
            $precio_boletos = $configuracion['precio_boletos'];
            $boletos_generados = floor($monto_pagado / $precio_boletos);
            $consulta = mysqli_query($bd, "UPDATE cliente SET boletos_sorteo = boletos_sorteo + '$boletos_generados' WHERE id_cliente = '$cliente_id'");
        }

        header("Location: ../cobros.php");
        exit();
    }
}

header("Location: ../cobros.php");
exit();
?>