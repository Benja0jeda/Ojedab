<?php
require('libs/fpdf186/fpdf.php');
include_once('bd.php');

mb_internal_encoding('UTF-8');

if (isset($_GET['id_compra'])) {
    $comprobante = 'compra';
    $id = $_GET['id_compra'];
} elseif (isset($_GET['id_venta'])) {
    $comprobante = 'venta';
    $id = $_GET['id_venta'];
} else {
    die('No se especificó un tipo de comprobante válido');
}

class PDF extends FPDF {
    function Header() {
        $this->Image("imgs/Mana.jpg",160,5,30,0,"jpg");
        
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 0, 0);
        
        $this->SetY(10);
        $this->Cell(0, 5, mb_convert_encoding("Minimarket Maná", "ISO-8859-1", "UTF-8"), 0, 1, 'C');
        $this->Cell(0, 5, mb_convert_encoding("RUT: 160 297 850 016", "ISO-8859-1", "UTF-8"), 0, 1, 'C');
        $this->Cell(0, 5, mb_convert_encoding("Dirección: Amorim 805 esq Charrúa", "ISO-8859-1", "UTF-8"), 0, 1, 'C');
        
        $this->Ln(10);

        $title = $GLOBALS['comprobante'] == 'venta' ? 'Comprobante de Venta' : 'Comprobante de Compra';
        $this->Cell(0, 10, mb_convert_encoding($title, "ISO-8859-1", "UTF-8"), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, mb_convert_encoding('Página ' . $this->PageNo() . '/{nb}', "ISO-8859-1", "UTF-8"), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

if ($comprobante == 'venta') {
    $consulta = mysqli_query($bd, "SELECT nombre, cedula FROM cliente WHERE id_cliente = (SELECT id_cliente FROM venta WHERE id_venta = '$id')");
    $cliente = mysqli_fetch_assoc($consulta);
    
    $consulta_venta = mysqli_query($bd, "SELECT fecha, subtotal FROM venta WHERE id_venta = '$id'");
    $venta = mysqli_fetch_assoc($consulta_venta);
    
    $pdf->Cell(0, 10, mb_convert_encoding('Cliente: ' . $cliente['nombre'], "ISO-8859-1", "UTF-8"), 0, 1);
    $pdf->Cell(0, 10, mb_convert_encoding('Cédula: ' . $cliente['cedula'], "ISO-8859-1", "UTF-8"), 0, 1);
    $pdf->Cell(0, 10, mb_convert_encoding('Fecha: ' . date('d/m/Y H:i:s', strtotime($venta['fecha'])), "ISO-8859-1", "UTF-8"), 0, 1);
    
} else {
    $consulta = mysqli_query($bd, "SELECT razon_social, rut FROM proveedor WHERE id_proveedor = (SELECT id_proveedor FROM compra WHERE id_compra = '$id')");
    $proveedor = mysqli_fetch_assoc($consulta);
    
    $consulta_compra = mysqli_query($bd, "SELECT fecha, subtotal FROM compra WHERE id_compra = '$id'");
    $compra = mysqli_fetch_assoc($consulta_compra);
    
    $pdf->Cell(0, 10, mb_convert_encoding('Proveedor: ' . $proveedor['razon_social'], "ISO-8859-1", "UTF-8"), 0, 1);
    $pdf->Cell(0, 10, mb_convert_encoding('RUT: ' . $proveedor['rut'], "ISO-8859-1", "UTF-8"), 0, 1);
    $pdf->Cell(0, 10, mb_convert_encoding('Fecha: ' . date('d/m/Y H:i:s', strtotime($compra['fecha'])), "ISO-8859-1", "UTF-8"), 0, 1);
}

$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, mb_convert_encoding('Producto', "ISO-8859-1", "UTF-8"), 1);
$pdf->Cell(30, 10, 'IVA', 1);
$pdf->Cell(30, 10, mb_convert_encoding('Cantidad', "ISO-8859-1", "UTF-8"), 1);
$pdf->Cell(40, 10, 'Precio Unit.', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$total = 0;
$total_iva = 0;

if ($comprobante == 'venta') {
    $productos_consulta = mysqli_query($bd, "SELECT p.nombre, pv.precio_venta, pv.cantidad, pv.iva_de_venta FROM productos_vendidos pv JOIN producto p ON pv.id_producto = p.id_producto WHERE pv.id_venta = '$id'");
    
    while ($producto = mysqli_fetch_assoc($productos_consulta)) {
        $pdf->Cell(60, 10, mb_convert_encoding($producto['nombre'], "ISO-8859-1", "UTF-8"), 1);
        
        $subtotal_producto = $producto['precio_venta'] * $producto['cantidad'];
        $porcentaje_iva = ($producto['iva_de_venta'] > 0) ? 
            round(($producto['iva_de_venta'] / $subtotal_producto) * 100) : 0;
            
        $pdf->Cell(30, 10, $porcentaje_iva . '%', 1);
        $pdf->Cell(30, 10, $producto['cantidad'], 1);
        $pdf->Cell(40, 10, '$' . number_format($producto['precio_venta'], 2), 1);
        $pdf->Ln();
        
        $total_iva += $producto['iva_de_venta'];
        $total += $subtotal_producto + $producto['iva_de_venta'];
    }
    
    $subtotal = $venta['subtotal'];
} else {
    $productos_consulta = mysqli_query($bd, "SELECT p.nombre, pc.precio_compra, pc.cantidad, pc.iva_de_compra FROM productos_comprados pc JOIN producto p ON pc.id_producto = p.id_producto WHERE pc.id_compra = '$id'");
    
    while ($producto = mysqli_fetch_assoc($productos_consulta)) {
        $pdf->Cell(60, 10, mb_convert_encoding($producto['nombre'], "ISO-8859-1", "UTF-8"), 1);
        
        $subtotal_producto = $producto['precio_compra'] * $producto['cantidad'];
        $porcentaje_iva = ($producto['iva_de_compra'] > 0) ? 
            round(($producto['iva_de_compra'] / $subtotal_producto) * 100) : 0;
            
        $pdf->Cell(30, 10, $porcentaje_iva . '%', 1);
        $pdf->Cell(30, 10, $producto['cantidad'], 1);
        $pdf->Cell(40, 10, '$' . number_format($producto['precio_compra'], 2), 1);
        $pdf->Ln();
        
        $total_iva += $producto['iva_de_compra'];
        $total += $subtotal_producto + $producto['iva_de_compra'];
    }
    
    $subtotal = $total - $total_iva;
}

$pdf->Ln(5);
$pdf->Cell(120, 10, '', 0);
$pdf->Cell(30, 10, mb_convert_encoding('Subtotal:', "ISO-8859-1", "UTF-8"), 0);
$pdf->Cell(40, 10, '$' . number_format($subtotal, 2), 0);
$pdf->Ln();

$pdf->Cell(120, 10, '', 0);
$pdf->Cell(30, 10, 'IVA Total:', 0);
$pdf->Cell(40, 10, '$' . number_format($total_iva, 2), 0);
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(120, 10, '', 0);
$pdf->Cell(30, 10, 'Total:', 0);
$pdf->Cell(40, 10, '$' . number_format($total, 2), 0);

$pdf->Ln(20);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, mb_convert_encoding('Información de Pago:', "ISO-8859-1", "UTF-8"), 0, 1);
$pdf->SetFont('Arial', '', 12);

if ($comprobante == 'compra') {
    $compra_info = mysqli_fetch_assoc(mysqli_query($bd, "SELECT vencimiento FROM compra WHERE id_compra = '$id'"));
    if ($compra_info['vencimiento']) {
        $pdf->Cell(0, 10, mb_convert_encoding('Método de pago: Crédito', "ISO-8859-1", "UTF-8"), 0, 1);
        $pdf->Cell(0, 10, mb_convert_encoding('Fecha de vencimiento: ' . date('d/m/Y', strtotime($compra_info['vencimiento'])), "ISO-8859-1", "UTF-8"), 0, 1);
    } else {
        $pdf->Cell(0, 10, mb_convert_encoding('Método de pago: Contado', "ISO-8859-1", "UTF-8"), 0, 1);
    }
    
    $pagos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT SUM(monto) as total_pagado FROM pago WHERE id_compra = '$id'"));
    $monto_pagado = $pagos['total_pagado'] ?? 0;
    
    $pdf->Cell(0, 10, mb_convert_encoding('Monto abonado: $' . number_format($monto_pagado, 2), "ISO-8859-1", "UTF-8"), 0, 1);
    $monto_pendiente = $total - $monto_pagado;
    if ($monto_pendiente > 0) {
        $pdf->Cell(0, 10, mb_convert_encoding('Monto pendiente: $' . number_format($monto_pendiente, 2), "ISO-8859-1", "UTF-8"), 0, 1);
    }
} else {
    $cobros = mysqli_fetch_assoc(mysqli_query($bd, "SELECT SUM(monto) as total_cobrado FROM cobro WHERE id_venta = '$id'"));
    $monto_cobrado = $cobros['total_cobrado'] ?? 0;
    
    $pdf->Cell(0, 10, mb_convert_encoding('Monto abonado: $' . number_format($monto_cobrado, 2), "ISO-8859-1", "UTF-8"), 0, 1);
    $monto_pendiente = $total - $monto_cobrado;
    if ($monto_pendiente > 0) {
        $pdf->Cell(0, 10, mb_convert_encoding('Monto pendiente: $' . number_format($monto_pendiente, 2), "ISO-8859-1", "UTF-8"), 0, 1);
    }
}

$pdf->Output();
?>