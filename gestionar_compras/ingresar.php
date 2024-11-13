<?php
include_once("../bd.php");
include_once("../chequeo.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["monto_pagado"]) && isset($_POST["proveedor_id"]) && isset($_POST["productos_ids"]) && isset($_POST["cantidades"])) {
        $monto_pagado = $_POST["monto_pagado"];
        $proveedor_id = $_POST["proveedor_id"];
        $productos_ids = explode(',', $_POST["productos_ids"]);
        $cantidades = explode(',', $_POST["cantidades"]);
        $total = $_POST["total"];
        $subtotal = $_POST["subtotal"];
        $fecha_compra = date("Y-m-d");

        if (isset($_POST["fechacredito"]) && $_POST["fechacredito"] != "") {
            $fecha_credito = $_POST["fechacredito"];
            $consulta = mysqli_query($bd, "INSERT INTO compra (precio, fecha, id_proveedor, vencimiento, subtotal) VALUES ('$total', '$fecha_compra', '$proveedor_id', '$fecha_credito', '$subtotal')");
        } else {
            $consulta = mysqli_query($bd, "INSERT INTO compra (precio, fecha, id_proveedor, subtotal) VALUES ('$total', '$fecha_compra', '$proveedor_id', '$subtotal')");   
        }

        $id_compra = mysqli_insert_id($bd);

        foreach ($productos_ids as $i => $producto_id) {
            $cantidad = $cantidades[$i];

            $consulta = mysqli_query($bd, "SELECT p.precio_compra, p.cantidad, i.valor as iva_valor 
                                         FROM producto p 
                                         JOIN iva i ON p.id_iva = i.id_iva 
                                         WHERE p.id_producto = '$producto_id'");
            $producto = mysqli_fetch_assoc($consulta);

            $precio_compra = $producto['precio_compra'];
            $iva_valor = $producto['iva_valor'];
            
            $iva_producto = ($precio_compra * $cantidad) * ($iva_valor / 100);

            $consulta = mysqli_query($bd, "INSERT INTO productos_comprados 
                                         (id_compra, id_producto, cantidad, precio_compra, iva_de_compra) 
                                         VALUES 
                                         ('$id_compra', '$producto_id', '$cantidad', '$precio_compra', '$iva_producto')");

            $nueva_cantidad = $producto['cantidad'] + $cantidad;
            $consulta = mysqli_query($bd, "UPDATE producto SET cantidad = '$nueva_cantidad' WHERE id_producto = '$producto_id'");
        }

        $consulta = mysqli_query($bd, "INSERT INTO pago (monto, fecha, id_proveedor, id_compra) VALUES ('$monto_pagado', '$fecha_compra', '$proveedor_id', '$id_compra')");

        $deuda = $total - $monto_pagado;
        if ($deuda > 0) {
            $consulta = mysqli_query($bd, "UPDATE proveedor SET deuda = deuda + '$deuda' WHERE id_proveedor = '$proveedor_id'");
        }

        header("Location: ../pagos.php");
        exit();
    }
}

header("Location: ../compras.php");
exit();
?>