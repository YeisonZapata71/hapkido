<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo #<?php echo str_pad($movimiento['id'], 6, '0', STR_PAD_LEFT); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #f5f5f5; padding: 20px; }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border: 1px solid #ddd;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header { border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: start; }
        .company-info h2 { font-weight: 800; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        .company-info p { margin: 2px 0; font-size: 0.9rem; color: #555; }
        .doc-info { text-align: right; }
        .doc-title { font-size: 1.2rem; font-weight: 700; border: 2px solid #000; padding: 5px 15px; display: inline-block; margin-bottom: 10px; }
        .doc-number { font-size: 1.5rem; color: #d00; font-weight: bold; font-family: Arial, sans-serif; }
        
        .row-item { display: flex; margin-bottom: 15px; border-bottom: 1px dashed #ccc; padding-bottom: 5px; }
        .label { font-weight: bold; width: 180px; flex-shrink: 0; }
        .value { flex-grow: 1; font-family: Arial, sans-serif; }
        
        .amount-box { 
            background: #f0f0f0; 
            padding: 15px; 
            font-size: 1.5rem; 
            font-weight: bold; 
            text-align: right; 
            border: 1px solid #999; 
            margin: 20px 0;
            font-family: Arial, sans-serif;
        }

        .signatures { margin-top: 60px; display: flex; justify-content: space-between; }
        .sig-box { border-top: 1px solid #000; width: 40%; text-align: center; padding-top: 10px; position: relative; }

        @media print {
            body { background: #fff; padding: 0; }
            .receipt-container { box-shadow: none; border: none; padding: 0; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="text-end mb-4 btn-print">
        <button onclick="window.print()" class="btn btn-primary btn-sm">Imprimir / Guardar PDF</button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">Cerrar</button>
    </div>

    <div class="header">
        <div class="company-info d-flex align-items-center">
            <div class="me-3">
                <img src="../logo_final.png" alt="Logo" style="max-height: 80px; width: auto;">
            </div>
            <div>
                <h2>CLUB DEPORTIVO SIAO</h2>
                <p>Hapkido-Taekwondo Joong Do Ryu</p>
                <p>Coliseo de Combate Medellin</p>
                <p>Telefono: 3232897785</p>
            </div>
        </div>
        <div class="doc-info">
            <div class="doc-title"><?php echo $titulo_doc; ?></div>
            <div class="doc-number">No. <?php echo str_pad($movimiento['id'], 6, '0', STR_PAD_LEFT); ?></div>
            <div>Fecha: <?php echo date('d/m/Y', strtotime($movimiento['fecha'])); ?></div>
        </div>
    </div>

    <div class="content">
        <?php 
            $nombre_mostrar = !empty($movimiento['tercero_final']) ? $movimiento['tercero_final'] : 
                             (!empty($movimiento['tercero_nombre']) ? $movimiento['tercero_nombre'] : 'Particular');
            
            // Si tenemos documento real del join, usarlo. Si no, el controller intentaba buscarlo, pero ya el modelo lo trae.
            $doc_mostrar = !empty($movimiento['tercero_documento_real']) ? $movimiento['tercero_documento_real'] : $tercero_doc;
        ?>

        <?php if($movimiento['tipo'] == 'ingreso'): ?>
            <div class="row-item">
                <div class="label">RECIBIDO DE:</div>
                <div class="value text-uppercase"><?php echo htmlspecialchars($nombre_mostrar); ?></div>
            </div>
            <?php if($doc_mostrar): ?>
            <div class="row-item">
                <div class="label">DOCUMENTO:</div>
                <div class="value"><?php echo htmlspecialchars($doc_mostrar); ?></div>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="row-item">
                <div class="label">PAGADO A:</div>
                <div class="value text-uppercase"><?php echo htmlspecialchars($nombre_mostrar); ?></div>
            </div>
        <?php endif; ?>

        <div class="row-item">
            <div class="label">POR CONCEPTO DE:</div>
            <div class="value">
                <?php echo htmlspecialchars($movimiento['concepto_nombre']); ?>
                <?php if($movimiento['observaciones']): ?>
                    <br><small class="text-muted">(<?php echo htmlspecialchars($movimiento['observaciones']); ?>)</small>
                <?php endif; ?>
            </div>
        </div>

        <div class="row-item">
            <div class="label">FORMA DE PAGO:</div>
            <div class="value">
                <?php echo $movimiento['banco_origen'] ? 'TRANSFERENCIA / BANCO: ' . htmlspecialchars($movimiento['banco_origen']) : 'EFECTIVO'; ?>
                <?php if($movimiento['comprobante_banco']): ?>
                    (Ref: <?php echo htmlspecialchars($movimiento['comprobante_banco']); ?>)
                <?php endif; ?>
            </div>
        </div>

        <div class="amount-box">
            $ <?php echo number_format($movimiento['monto'], 0); ?> COP
        </div>
        
        <p class="text-uppercase small" style="border-top: 1px dotted #ccc; padding-top: 5px; margin-top: 15px;">
            Valor en letras: <strong><?php echo numeroALetras($movimiento['monto']); ?></strong>
        </p>
    </div>

    <div class="signatures">
        <div class="sig-box">
            RECIBIDO POR
        </div>
        <div class="sig-box">
            AUTORIZADO / FIRMA Y SELLO
            <br>
            <?php 
                $ruta_firma = __DIR__ . '/../../assets/img/firma_digital.png';
                
                if (file_exists($ruta_firma)) {
                    $type = pathinfo($ruta_firma, PATHINFO_EXTENSION);
                    $data = file_get_contents($ruta_firma);
                    
                    if ($data) {
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        // Posicionamiento absoluto para quedar sobre la línea (border-top)
                        // Aumentado tamaño (max-height) y subido con top negativo
                        echo '<img src="' . $base64 . '" alt="Firma Digital" 
                                   style="max-height: 150px; 
                                          position: absolute; 
                                          top: -80px; 
                                          left: 50%; 
                                          transform: translateX(-50%); 
                                          opacity: 0.9;">';
                    } else {
                        echo "<br><small style='color:red'>Error leyendo archivo de firma</small>";
                    }
                } else {
                   echo "<br><small style='color:red'>Archivo de firma no encontrado</small>"; 
                }
            ?>
        </div>
    </div>
</div>

<?php
// Función para convertir números a letras
function numeroALetras($amount) {
    if ($amount == 0) return 'CERO PESOS M/CTE';
    
    $amount_int = floor($amount);
    
    $units = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
    $tens = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
    $teens = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
    
    // Función auxiliar simple para menores de 1000
    // Nota: Para una implementación completa robusta se requiere más código, 
    // pero para los montos típicos de mensualidades esto servirá.
    // Usaremos una versión simplificada suficiente para recibos comunes.
    
    $text = '';
    if ($amount_int >= 1000000) {
        $millions = floor($amount_int / 1000000);
        $amount_int -= $millions * 1000000;
        if ($millions == 1) $text .= 'UN MILLON ';
        else $text .= numeroALetrasSimple($millions) . ' MILLONES ';
    }
    
    if ($amount_int >= 1000) {
        $thousands = floor($amount_int / 1000);
        $amount_int -= $thousands * 1000;
        if ($thousands == 1) $text .= 'MIL ';
        else $text .= numeroALetrasSimple($thousands) . ' MIL ';
    }
    
    if ($amount_int > 0) {
        $text .= numeroALetrasSimple($amount_int);
    }
    
    return trim($text) . ' PESOS M/CTE';
}

function numeroALetrasSimple($num) {
    $units = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
    $teens = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
    $tens = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
    $hundreds = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

    if ($num == 100) return 'CIEN';
    if ($num == 0) return '';
    
    $str = '';
    
    // Centenas
    $c = floor($num / 100);
    $num %= 100;
    if ($c > 0) $str .= $hundreds[$c] . ' ';
    
    // Decenas
    if ($num >= 10 && $num <= 19) {
        $str .= $teens[$num - 10];
        $num = 0;
    } elseif ($num >= 20) {
        $d = floor($num / 10);
        $str .= $tens[$d];
        $num %= 10;
        if ($num > 0) $str .= ' Y ';
    }
    
    // Unidades
    if ($num > 0) {
        $str .= $units[$num];
    }
    
    return trim($str);
}
?>
    </div>
</div>

</body>
</html>
