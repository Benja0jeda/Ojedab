<?php
include_once("../bd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = '';
    if (isset($_POST['id_cliente'])) {
        $id_cliente = $_POST['id_cliente'];
    }
    
    $ids_productos = [];
    if (isset($_POST['ids_productos'])) {
        $ids_productos = explode(',', $_POST['ids_productos']);
    }
    
    $cantidades = [];
    if (isset($_POST['cantidades'])) {
        $cantidades = explode(',', $_POST['cantidades']);
    }

    if ($id_cliente && $ids_productos && $cantidades && count($ids_productos) === count($cantidades)) {
        
        $consulta = mysqli_query($bd, "SELECT nombre, cedula, deuda FROM cliente WHERE id_cliente = '$id_cliente'");
        $cliente = mysqli_fetch_assoc($consulta);
        
        if ($cliente) {
            $total = 0;
            $total_iva_10 = 0;
            $total_iva_22 = 0;
            $subtotal = 0;
            $tabla_filas = '';
            
            foreach ($ids_productos as $i => $producto_id) {
                $cantidad = floatval($cantidades[$i]);
                $producto_query = mysqli_query($bd, "SELECT p.nombre, p.precio_venta, i.valor as iva_valor FROM producto p JOIN iva i ON p.id_iva = i.id_iva WHERE p.id_producto = '$producto_id'");
                $producto = mysqli_fetch_assoc($producto_query);

                if ($producto) {
                    $subtotal_producto = $producto['precio_venta'] * $cantidad;
                    $iva_monto = ($subtotal_producto * $producto['iva_valor']) / 100;
                    
                    if ($producto['iva_valor'] == 10) {
                        $total_iva_10 += $iva_monto;
                    } else if ($producto['iva_valor'] == 22) {
                        $total_iva_22 += $iva_monto;
                    }

                    $subtotal += $subtotal_producto;
                    $total += $subtotal_producto + $iva_monto;

                    $tabla_filas .= '<tr>
                        <td>' . $producto['nombre'] . '</td>
                        <td class="text-center">' . $producto['iva_valor'] . '%</td>
                        <td class="text-center">' . $cantidad . '</td>
                        <td class="text-right">$' . number_format($producto['precio_venta'], 2) . '</td>
                        <td class="text-right">$' . number_format($subtotal_producto + $iva_monto, 2) . '</td>
                    </tr>';
                }
            }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Venta</title>
    <style>
    :root {
        --primario: #2c3e50;
        --primario-oscuro: #1a252f;
        --blanco: #ffffff;
        --blanco2: #f8f9fa;
        --gris: #95a5a6;
        --negro: #34495e;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: var(--blanco2);
        color: var(--negro);
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .contenedor {
        display: flex;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .contenedor-izquierda {
        flex: 2;
        margin-right: 20px;
    }

    .contenedor-derecha {
        flex: 1;
    }

    .comprobante {
        background-color: var(--blanco);
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .encabezado-comprobante {
        background-color: var(--primario);
        color: var(--blanco);
        padding: 20px;
    }

    .encabezado-comprobante h1 {
        margin: 0;
        font-size: 24px;
        text-align: center;
    }

    .detalles-cliente {
        padding: 20px;
        border-bottom: 1px solid var(--gris);
    }

    .detalles-cliente p {
        margin: 5px 0;
    }

    .tabla-comprobante {
        width: 100%;
        border-collapse: collapse;
    }

    .tabla-comprobante th {
        background-color: var(--primario-oscuro);
        color: var(--blanco);
        padding: 12px;
        text-align: left;
    }

    .tabla-comprobante th.text-right,
    .tabla-comprobante td.text-right {
        text-align: right;
    }

    .tabla-comprobante th.text-center,
    .tabla-comprobante td.text-center {
        text-align: center;
    }

    .tabla-comprobante td {
        padding: 12px;
        border-bottom: 1px solid var(--gris);
    }

    .fila-total td {
        font-weight: bold;
        background-color: var(--blanco2);
        border-bottom: none;
    }

    .fila-total:last-child td {
        border-top: 2px solid var(--gris);
        font-size: 1.1em;
    }

    .formulario-pago {
        background-color: var(--blanco);
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .formulario-pago h2 {
        margin-top: 0;
        color: var(--primario);
    }

    .formulario-pago label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .formulario-pago input[type="number"],
    .formulario-pago input[type="date"],
    .formulario-pago select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid var(--gris);
        border-radius: 4px;
        box-sizing: border-box;
    }

    .formulario-pago input[type="submit"],
    .formulario-pago button {
        width: 100%;
        padding: 12px;
        background-color: var(--primario);
        color: var(--blanco);
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .formulario-pago input[type="submit"]:hover,
    .formulario-pago button:hover {
        background-color: var(--primario-oscuro);
    }

    @media (max-width: 768px) {
        .contenedor {
            flex-direction: column;
        }

        .contenedor-izquierda,
        .contenedor-derecha {
            margin-right: 0;
            margin-bottom: 20px;
        }
    }
    </style>
</head>

<body>
    <div class="contenedor">
        <div class="contenedor-izquierda">
            <div class="comprobante">
                <div class="encabezado-comprobante">
                    <h1>Comprobante de Venta</h1>
                </div>
                <div class="detalles-cliente">
                    <p><strong>Cliente:</strong> <?php echo $cliente['nombre']; ?></p>
                    <p><strong>Cédula:</strong> <?php echo $cliente['cedula']; ?></p>
                    <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
                </div>
                <table class="tabla-comprobante">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">IVA</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-right">Precio Unitario</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tabla_filas; ?>
                        <tr class="fila-total">
                            <td colspan="4" class="text-right">Subtotal:</td>
                            <td class="text-right">$<?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                        <?php if ($total_iva_10 > 0) { ?>
                        <tr class="fila-total">
                            <td colspan="4" class="text-right">IVA 10%:</td>
                            <td class="text-right">$<?php echo number_format($total_iva_10, 2); ?></td>
                        </tr>
                        <?php } ?>
                        <?php if ($total_iva_22 > 0) { ?>
                        <tr class="fila-total">
                            <td colspan="4" class="text-right">IVA 22%:</td>
                            <td class="text-right">$<?php echo number_format($total_iva_22, 2); ?></td>
                        </tr>
                        <?php } ?>
                        <tr class="fila-total">
                            <td colspan="4" class="text-right">Total:</td>
                            <td class="text-right">$<?php echo number_format($total, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="contenedor-derecha">
            <div class="formulario-pago">
                <h2>Ingreso de Pago</h2>

                <form method="POST" action="gestionar_ventas/ingresar.php">
                    <input type="hidden" name="cliente_id" value="<?php echo $id_cliente; ?>">
                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                    <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                    <input type="hidden" name="iva_10" value="<?php echo $total_iva_10; ?>">
                    <input type="hidden" name="iva_22" value="<?php echo $total_iva_22; ?>">
                    <input type="hidden" name="productos_ids" value="<?php echo implode(',', $ids_productos); ?>">
                    <input type="hidden" name="cantidades" value="<?php echo implode(',', $cantidades); ?>">

                    <label for="monto_pagado">Ingrese el dinero recibido:</label>
                    <input type="number" id="monto_pagado" name="monto_pagado" min="0" step="0.01" required>
                    <input type="submit" value="Concretar Venta">
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<?php
        }
    }
} else {
    echo "Acceso no autorizado";
}
?>