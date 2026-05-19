<?php
// views/contabilidad/reportes.php
// Este archivo es cargado por contabilidad_controller.php?view=reportes

// Los datos ya vienen del controlador:
// $stats_mensuales, $distribucion_grados, $horarios, $stats_horario (opcional)
$mes_filtro = $_GET['mes'] ?? date('Y-m');
$horario_filtro = $_GET['horario'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes y Estadísticas - SIAO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
          --bg-primary: #0f1419;
          --bg-secondary: #1a1f2e;
          --bg-card: rgba(26, 31, 46, 0.6);
          --border-neon-blue: #3b82f6;
          --border-neon-green: #10b981;
          --border-neon-yellow: #fbbf24;
          --border-neon-red: #ef4444;
          --text-primary: #e5e7eb;
          --text-secondary: #9ca3af;
          --accent-blue: #60a5fa;
          --accent-green: #34d399;
          --accent-yellow: #fcd34d;
          --accent-orange: #fb923c;
        }
        
        body {
          background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
          min-height: 100vh;
          font-family: 'Inter', sans-serif;
          color: var(--text-primary);
          padding-bottom: 3rem;
        }
        
        .main-container {
          max-width: 1400px;
          margin: 0 auto;
          padding: 2rem 1.5rem;
        }
        
        .page-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 2rem;
          flex-wrap: wrap;
          gap: 1rem;
        }
        
        .page-title h2 {
          font-weight: 700;
          font-size: 2rem;
          color: var(--text-primary);
          margin: 0;
          display: flex;
          align-items: center;
          gap: 0.75rem;
        }
        
        .page-title h2 i { color: var(--accent-blue); }
        .page-title p { color: var(--text-secondary); margin: 0.25rem 0 0 0; font-size: 0.9rem; }
        
        .glass-card {
          background: var(--bg-card);
          backdrop-filter: blur(10px);
          border-radius: 16px;
          border: 1px solid rgba(255, 255, 255, 0.1);
          padding: 1.5rem;
          margin-bottom: 1.5rem;
        }

        .filter-panel {
            background: rgba(15, 20, 25, 0.4);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .form-select, .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border-radius: 8px;
        }

        .form-select:focus, .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-blue);
            color: var(--text-primary);
            box-shadow: none;
        }

        /* Fix for dropdown options visibility */
        option {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }

        .stat-card-mini {
            padding: 1.25rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
        }

        .stat-card-mini h3 { margin: 0; font-weight: 800; font-size: 1.5rem; }
        .stat-card-mini p { margin: 0; color: var(--text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 600; }

        .report-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .table { color: var(--text-primary); }
        .table thead th { border-bottom: 1px solid rgba(255,255,255,0.1); color: var(--text-secondary); font-size: 0.8rem; }
        .table tbody td { border-bottom: 1px solid rgba(255,255,255,0.05); padding: 0.75rem 0.5rem; }
        
        .progress-slim { height: 6px; background: rgba(255,255,255,0.05); border-radius: 10px; }
        .progress-bar-slim { border-radius: 10px; }
    </style>
</head>
<body>

<div class="container main-container">
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <h2><i class="bi bi-bar-chart-line-fill"></i> Reportes y Estadísticas</h2>
            <p>Análisis detallado de movimientos y población deportiva</p>
        </div>
        <a href="contabilidad_controller.php" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard
        </a>
    </div>

    <!-- Filters -->
    <div class="filter-panel">
        <form method="GET" action="contabilidad_controller.php" class="row g-3 align-items-end">
            <input type="hidden" name="view" value="reportes">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Mes a Consultar</label>
                <input type="month" name="mes" class="form-control" value="<?php echo $mes_filtro; ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Filtrar por Horario</label>
                <select name="horario" class="form-select">
                    <option value="">Todos los horarios</option>
                    <?php foreach ($horarios as $h): ?>
                        <option value="<?php echo htmlspecialchars($h['id']); ?>" <?php echo $horario_filtro == $h['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($h['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="bi bi-funnel-fill me-2"></i>Aplicar
                </button>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-info w-100 fw-bold" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
            </div>
        </form>
    </div>

    <!-- Monthly Finance Section -->
    <h5 class="report-section-title"><i class="bi bi-calendar-check text-success"></i> Resumen Financiero del Mes</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card-mini" style="border-top: 3px solid var(--accent-green);">
                <p>Total Ingresos</p>
                <h3 class="text-success">$<?php echo number_format($stats_mensuales['total_ingresos'], 0); ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-mini" style="border-top: 3px solid var(--accent-orange);">
                <p>Total Egresos</p>
                <h3 class="text-danger">$<?php echo number_format($stats_mensuales['total_egresos'], 0); ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-mini" style="border-top: 3px solid var(--accent-blue);">
                <p>Balance Neto</p>
                <h3 class="<?php echo $stats_mensuales['balance'] >= 0 ? 'text-info' : 'text-danger'; ?>">
                    $<?php echo number_format($stats_mensuales['balance'], 0); ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="glass-card h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2 text-success"></i>Ingresos por Concepto</h6>
                <?php if (empty($stats_mensuales['ingresos_por_concepto'])): ?>
                    <p class="text-muted small">No hay ingresos registrados en este periodo.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Concepto</th><th class="text-end">Monto</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats_mensuales['ingresos_por_concepto'] as $c): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($c['nombre']); ?></td>
                                    <td class="text-end fw-bold">$<?php echo number_format($c['total'], 0); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="glass-card h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-dash-circle me-2 text-danger"></i>Egresos por Concepto</h6>
                <?php if (empty($stats_mensuales['egresos_por_concepto'])): ?>
                    <p class="text-muted small">No hay egresos registrados en este periodo.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Concepto</th><th class="text-end">Monto</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats_mensuales['egresos_por_concepto'] as $c): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($c['nombre']); ?></td>
                                    <td class="text-end fw-bold text-danger">$<?php echo number_format($c['total'], 0); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Concept Breakdown Section (Accordion) -->
    <h5 class="report-section-title"><i class="bi bi-layers text-primary"></i> Detalle de Ingresos por Concepto</h5>
    <div class="row mb-5">
        <div class="col-12">
            <?php 
            // Agrupar movimientos de INGRESO por concepto
            $ingresos_agrupados = [];
            if (!empty($movimientos_detallados)) {
                foreach ($movimientos_detallados as $mov) {
                    if ($mov['tipo'] == 'ingreso') {
                        $ingresos_agrupados[$mov['concepto_nombre']][] = $mov;
                    }
                }
            }
            ?>
            
            <?php if (empty($ingresos_agrupados)): ?>
                <div class="glass-card text-center py-4">
                    <p class="text-muted mb-0">No hay ingresos para desglosar en este periodo.</p>
                </div>
            <?php else: ?>
                <div class="accordion" id="accordionConceptos">
                    <?php 
                    $i = 0;
                    foreach ($ingresos_agrupados as $concepto => $lista): 
                        $total_concepto = array_sum(array_column($lista, 'monto'));
                        $id_acc = "collapse" . $i;
                        $i++;
                    ?>
                    <div class="accordion-item mb-2" style="background: var(--bg-card); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $id_acc; ?>" aria-expanded="false" 
                                    style="background: rgba(255,255,255,0.03); color: var(--text-primary); box-shadow: none;">
                                <div class="d-flex w-100 justify-content-between align-items-center me-3">
                                    <span>
                                        <i class="bi bi-tag-fill me-2 text-warning"></i>
                                        <strong><?php echo htmlspecialchars($concepto); ?></strong>
                                        <span class="badge bg-secondary ms-2 rounded-pill"><?php echo count($lista); ?> pagos</span>
                                    </span>
                                    <span class="fw-bold text-success">$<?php echo number_format($total_concepto, 0); ?></span>
                                </div>
                            </button>
                        </h2>
                        <div id="<?php echo $id_acc; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionConceptos">
                            <div class="accordion-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                                        <thead style="background: rgba(0,0,0,0.2);">
                                            <tr>
                                                <th class="ps-4">Fecha</th>
                                                <th>Deportista</th>
                                                <th>Horario</th>
                                                <th>Medio</th>
                                                <th class="text-end pe-4">Valor</th>
                                                <th class="text-center">Recibo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($lista as $mov): ?>
                                            <tr>
                                                <td class="ps-4 text-secondary"><?php echo date('d/m/Y', strtotime($mov['fecha'])); ?></td>
                                                <td class="fw-bold"><?php echo htmlspecialchars($mov['tercero_final']); ?></td>
                                                <td class="small text-muted"><?php echo htmlspecialchars($mov['deportista_horario'] ?? '-'); ?></td>
                                                <td class="small"><?php echo htmlspecialchars($mov['banco_origen'] ?? 'Efectivo'); ?></td>
                                                <td class="text-end fw-bold text-success pe-4">$<?php echo number_format($mov['monto'], 0); ?></td>
                                                <td class="text-center">
                                                    <a href="contabilidad_controller.php?view=imprimir_recibo&id=<?php echo $mov['id']; ?>" target="_blank" class="btn btn-sm btn-outline-light py-0">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <h5 class="report-section-title"><i class="bi bi-people text-info"></i> Análisis de Deportistas</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="glass-card">
                <h6 class="fw-bold mb-3">Distribución por Grados (Total)</h6>
                <div class="p-2">
                    <?php 
                    $total_deportistas = array_sum(array_column($distribucion_grados, 'cantidad'));
                    foreach ($distribucion_grados as $g): 
                        $pct = $total_deportistas > 0 ? ($g['cantidad'] / $total_deportistas) * 100 : 0;
                    ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1 small">
                                <span><?php echo htmlspecialchars($g['grado_cinturon']); ?></span>
                                <span class="fw-bold"><?php echo $g['cantidad']; ?> (<?php echo round($pct, 1); ?>%)</span>
                            </div>
                            <div class="progress progress-slim">
                                <div class="progress-bar progress-bar-slim" style="width: <?php echo $pct; ?>%; background: var(--accent-blue);"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="glass-card h-100">
                <h6 class="fw-bold mb-3">
                    Analítica por Horario: 
                    <span class="text-info">
                        <?php 
                        if ($horario_filtro) {
                            // Buscar nombre del horario en el array
                            $key = array_search($horario_filtro, array_column($horarios, 'id'));
                            echo $key !== false ? htmlspecialchars($horarios[$key]['nombre']) : 'Desconocido';
                        } else {
                            echo 'Todos';
                        }
                        ?>
                    </span>
                </h6>
                <?php if ($stats_horario): ?>
                    <div class="text-center py-4 mb-3" style="background: rgba(255,255,255,0.03); border-radius: 12px;">
                        <span class="text-secondary small d-block">Deportistas Activos en este Horario</span>
                        <h2 class="fw-800 m-0 text-info"><?php echo $stats_horario['total_deportistas']; ?></h2>
                    </div>

                    <h7 class="small fw-bold text-secondary d-block mb-2">Composición del Grupo:</h7>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <?php foreach (array_slice($stats_horario['distribucion_grados'], 0, 5) as $dg): ?>
                                <tr>
                                    <td class="small"><?php echo htmlspecialchars($dg['grado_cinturon']); ?></td>
                                    <td class="text-end fw-bold small"><?php echo $dg['cantidad']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-info-circle fs-2 text-secondary d-block mb-3"></i>
                        <p class="text-muted small">Selecciona un horario en el filtro para ver estadísticas específicas de población y grados por grupo.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Detailed Movements Section -->
    <h5 class="report-section-title mt-5"><i class="bi bi-list-check text-warning"></i> Detalle de Movimientos (Recibos y Comprobantes)</h5>
    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Deportista / Tercero</th>
                        <th>Horario</th>
                        <th>Concepto / Detalle</th>
                        <th>Medio</th>
                        <th class="text-end">Valor</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($movimientos_detallados)): ?>
                        <tr><td colspan="8" class="text-center text-secondary py-4">No hay movimientos registrados con estos filtros.</td></tr>
                    <?php else: ?>
                        <?php foreach ($movimientos_detallados as $mov): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td><?php echo date('d/m/Y', strtotime($mov['fecha'])); ?></td>
                            <td>
                                <?php if($mov['tipo'] == 'ingreso'): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success">Ingreso</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-25 text-danger">Egreso</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold">
                                <?php echo htmlspecialchars($mov['tercero_final']); ?>
                                <?php if($mov['tercero_id']): ?>
                                    <i class="bi bi-person-check-fill ms-1 text-info small" title="Afiliado Verificado"></i>
                                <?php endif; ?>
                            </td>
                            <td><small class="text-secondary"><?php echo htmlspecialchars($mov['deportista_horario'] ?? '-'); ?></small></td>
                            <td>
                                <div><?php echo htmlspecialchars($mov['concepto_nombre']); ?></div>
                                <small class="text-secondary fst-italic"><?php echo htmlspecialchars($mov['observaciones']); ?></small>
                            </td>
                            <td><small><?php echo htmlspecialchars($mov['banco_origen'] ?? 'Efectivo'); ?></small></td>
                            <td class="text-end fw-bold <?php echo $mov['tipo']=='ingreso'?'text-success':'text-danger'; ?>">
                                $<?php echo number_format($mov['monto'], 0); ?>
                            </td>
                            <td class="text-center">
                                <a href="contabilidad_controller.php?view=imprimir_recibo&id=<?php echo $mov['id']; ?>" target="_blank" class="btn btn-sm btn-outline-light" title="Ver Recibo/Comprobante">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
