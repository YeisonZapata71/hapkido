<?php
require_once __DIR__ . '/../../helpers/auth.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Contable - SIAO</title>
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
    
    /* Header */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2.5rem;
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
    
    .page-title h2 i {
      color: var(--accent-blue);
      font-size: 1.75rem;
    }
    
    .page-title p {
      color: var(--text-secondary);
      margin: 0.25rem 0 0 0;
      font-size: 0.9rem;
      font-weight: 400;
    }
    
    /* Glassmorphism Cards */
    .glass-card {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      padding: 1.5rem;
      transition: all 0.3s ease;
      height: 100%;
    }
    
    .glass-card:hover {
      transform: translateY(-3px);
      border-color: rgba(255, 255, 255, 0.2);
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }
    
    /* Access Cards with Neon Borders */
    .access-card {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 1.75rem;
      text-decoration: none;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 1.25rem;
      transition: all 0.3s ease;
      border: 2px solid transparent;
      position: relative;
      overflow: hidden;
    }
    
    .access-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, currentColor, transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .access-card:hover::before {
      opacity: 1;
    }
    
    .access-card.green { border-top-color: var(--border-neon-green); color: var(--accent-green); }
    .access-card.red { border-top-color: var(--border-neon-red); color: var(--accent-orange); }
    .access-card.blue { border-top-color: var(--border-neon-blue); color: var(--accent-blue); }
    
    .access-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.4);
    }
    
    .access-card.green:hover { border-color: var(--border-neon-green); box-shadow: 0 15px 50px rgba(16, 185, 129, 0.3); }
    .access-card.red:hover { border-color: var(--border-neon-red); box-shadow: 0 15px 50px rgba(239, 68, 68, 0.3); }
    .access-card.blue:hover { border-color: var(--border-neon-blue); box-shadow: 0 15px 50px rgba(59, 130, 246, 0.3); }
    .access-card.yellow:hover { border-color: var(--border-neon-yellow); box-shadow: 0 15px 50px rgba(251, 191, 36, 0.3); }
    
    .access-card.yellow { border-top-color: var(--border-neon-yellow); color: var(--accent-yellow); }
    .access-card.yellow .access-icon { background: rgba(251, 191, 36, 0.15); color: var(--accent-yellow); }
    
    .access-icon {
      width: 56px;
      height: 56px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.75rem;
      flex-shrink: 0;
    }
    
    .access-card.green .access-icon { background: rgba(16, 185, 129, 0.15); color: var(--accent-green); }
    .access-card.red .access-icon { background: rgba(251, 146, 60, 0.15); color: var(--accent-orange); }
    .access-card.blue .access-icon { background: rgba(59, 130, 246, 0.15); color: var(--accent-blue); }
    
    .access-content h5 {
      font-weight: 700;
      font-size: 1.1rem;
      margin-bottom: 0.25rem;
      color: var(--text-primary);
    }
    
    .access-content p {
      font-size: 0.85rem;
      color: var(--text-secondary);
      margin: 0;
    }
    
    /* Stats Cards */
    .stat-card {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 1.5rem;
      border-top: 3px solid;
      position: relative;
      overflow: hidden;
    }
    
    .stat-card::after {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 100px;
      height: 100px;
      background: radial-gradient(circle, currentColor 0%, transparent 70%);
      opacity: 0.05;
    }
    
    .stat-card.blue { border-top-color: var(--border-neon-blue); color: var(--accent-blue); }
    .stat-card.green { border-top-color: var(--border-neon-green); color: var(--accent-green); }
    .stat-card.yellow { border-top-color: var(--border-neon-yellow); color: var(--accent-yellow); }
    .stat-card.red { border-top-color: var(--border-neon-red); color: var(--accent-orange); }
    
    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    
    .stat-card.blue .stat-icon { background: rgba(59, 130, 246, 0.15); }
    .stat-card.green .stat-icon { background: rgba(16, 185, 129, 0.15); }
    .stat-card.yellow .stat-icon { background: rgba(251, 191, 36, 0.15); }
    .stat-card.red .stat-icon { background: rgba(251, 146, 60, 0.15); }
    
    .stat-value {
      font-size: 2rem;
      font-weight: 800;
      color: var(--text-primary);
      margin-bottom: 0.25rem;
    }
    
    .stat-label {
      font-size: 0.8rem;
      color: var(--text-secondary);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 600;
    }
    
    /* Progress Bar */
    .progress-modern {
      height: 8px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 10px;
      overflow: hidden;
      margin-top: 1rem;
    }
    
    .progress-bar-modern {
      height: 100%;
      background: linear-gradient(90deg, var(--accent-blue), var(--accent-green));
      border-radius: 10px;
      transition: width 0.6s ease;
      box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
    }
    
    /* Buttons */
    .btn-modern {
      background: rgba(59, 130, 246, 0.1);
      border: 1px solid var(--border-neon-blue);
      color: var(--accent-blue);
      padding: 0.65rem 1.5rem;
      border-radius: 10px;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .btn-modern:hover {
      background: rgba(59, 130, 246, 0.2);
      box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
      color: var(--accent-blue);
      transform: translateY(-2px);
    }
    
    .btn-modern-secondary {
      background: rgba(156, 163, 175, 0.1);
      border: 1px solid rgba(156, 163, 175, 0.3);
      color: var(--text-secondary);
    }
    
    .btn-modern-secondary:hover {
      background: rgba(156, 163, 175, 0.2);
      color: var(--text-primary);
      box-shadow: 0 0 20px rgba(156, 163, 175, 0.2);
    }
    
    /* Badge */
    .badge-modern {
      background: rgba(16, 185, 129, 0.15);
      color: var(--accent-green);
      padding: 0.35rem 0.85rem;
      border-radius: 8px;
      font-size: 0.75rem;
      font-weight: 700;
      border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    /* Section Title */
    .section-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding-left: 0.5rem;
      border-left: 3px solid var(--accent-blue);
    }
    
    /* Rubro Card */
    .rubro-card {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 1.25rem;
      border: 1px solid rgba(255, 255, 255, 0.08);
      margin-bottom: 1rem;
      transition: all 0.3s ease;
    }
    
    .rubro-card:hover {
      border-color: rgba(255, 255, 255, 0.15);
      transform: translateX(5px);
    }
    
    .rubro-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }
    
    .rubro-name {
      font-weight: 600;
      color: var(--accent-blue);
      font-size: 0.95rem;
    }
    
    .rubro-badge {
      background: rgba(16, 185, 129, 0.15);
      color: var(--accent-green);
      padding: 0.25rem 0.75rem;
      border-radius: 6px;
      font-size: 0.8rem;
      font-weight: 700;
    }
    
    .rubro-amounts {
      display: flex;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 0.75rem;
    }
    
    .amount-item {
      flex: 1;
    }
    
    .amount-label {
      font-size: 0.7rem;
      color: var(--text-secondary);
      text-transform: uppercase;
      margin-bottom: 0.25rem;
      font-weight: 600;
    }
    
    .amount-value {
      font-size: 0.95rem;
      font-weight: 700;
    }
    
    .amount-value.green { color: var(--accent-green); }
    .amount-value.red { color: var(--accent-orange); }
    
    .rubro-progress {
      font-size: 0.75rem;
      color: var(--text-secondary);
      margin-top: 0.5rem;
    }
  </style>
</head>
<body>

<div class="container main-container">
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <h2><i class="bi bi-wallet2"></i> Dashboard Contable</h2>
            <p>Visión general de finanzas y presupuestos</p>
        </div>
        <div class="d-flex gap-2">
            <a href="../dashboard.php" class="btn-modern-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="examenes_controller.php?view=lista" class="btn-modern" style="border-color: var(--border-neon-yellow); color: var(--accent-yellow); background: rgba(251, 191, 36, 0.1);">
                <i class="bi bi-mortarboard-fill"></i> Gestionar Exámenes
            </a>
            <a href="contabilidad_controller.php?view=config" class="btn-modern">
                <i class="bi bi-gear-fill"></i> Configurar Plan
            </a>
        </div>
    </div>

    <!-- Access Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <a href="contabilidad_controller.php?view=ingresos" class="access-card green">
                <div class="access-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div class="access-content">
                    <h5>Registrar Ingreso</h5>
                    <p>Mensualidades...</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="contabilidad_controller.php?view=egresos" class="access-card red">
                <div class="access-icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="access-content">
                    <h5>Registrar Egreso</h5>
                    <p>Gastos, pagos...</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="examenes_controller.php?view=lista" class="access-card yellow">
                <div class="access-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="access-content">
                    <h5>Exámenes</h5>
                    <p>Programar ascensos</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="contabilidad_controller.php?view=reportes" class="access-card blue">
                <div class="access-icon">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <div class="access-content">
                    <h5>Reportes</h5>
                    <p>Estadísticas</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card blue">
                <div class="stat-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-value">$<?php echo number_format($ingresos_reales, 0); ?></div>
                <div class="stat-label">Ingresos del Año</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card green">
                <div class="stat-icon">
                    <i class="bi bi-bullseye"></i>
                </div>
                <div class="stat-value">$<?php echo number_format($meta, 0); ?></div>
                <div class="stat-label">Meta Anual</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card yellow">
                <div class="stat-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="stat-value"><?php echo $count_examenes ?? 0; ?></div>
                <div class="stat-label">Próximos Exámenes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card red">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-value"><?php echo $stats_dashboard['total_deportistas_activos'] ?? 0; ?></div>
                <div class="stat-label">Deportistas Activos</div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="glass-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="section-title mb-0">Progreso Meta Anual <?php echo $anio_actual; ?></h5>
            <div class="d-flex align-items-center gap-3">
                <span class="badge-modern">
                    <?php echo number_format($porcentaje_avance, 1); ?>%
                </span>
                <span style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">
                    $<?php echo number_format($ingresos_reales, 0); ?> / $<?php echo number_format($meta, 0); ?>
                </span>
            </div>
        </div>
        <div class="progress-modern">
            <div class="progress-bar-modern" style="width: <?php echo min($porcentaje_avance, 100); ?>%"></div>
        </div>
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 1rem; margin-bottom: 0;">
            <i class="bi bi-info-circle"></i> Calculado sobre ingresos operativos vs meta configurada. El "Saldo Anterior" suma disponibilidad pero no afecta esta meta.
        </p>
    </div>

    <!-- Rubros Section -->
    <div class="section-title">Estado de Rubros (CDPs)</div>
    <div class="row g-3">
        <?php foreach ($saldos_rubros as $rubro): 
            $ejecucion_pct = $rubro['presupuesto'] > 0 ? ($rubro['egresos'] / $rubro['presupuesto']) * 100 : 0;
        ?>
        <div class="col-md-6">
            <div class="rubro-card">
                <div class="rubro-header">
                    <div class="rubro-name"><?php echo htmlspecialchars($rubro['nombre']); ?></div>
                    <div class="rubro-badge">$<?php echo number_format($rubro['saldo'], 0); ?></div>
                </div>
                
                <div class="rubro-amounts">
                    <div class="amount-item">
                        <div class="amount-label">Ingresos</div>
                        <div class="amount-value green">+ $<?php echo number_format($rubro['ingresos'], 0); ?></div>
                    </div>
                    <div class="amount-item">
                        <div class="amount-label">Egresos</div>
                        <div class="amount-value red">- $<?php echo number_format($rubro['egresos'], 0); ?></div>
                    </div>
                </div>
                
                <div class="rubro-progress">
                    Ejecución: <?php echo number_format($ejecucion_pct, 1); ?>%
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Próximos Exámenes Section -->
    <div class="glass-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="section-title mb-0">
                <i class="bi bi-mortarboard-fill" style="color: var(--accent-yellow);"></i>
                Próximos Exámenes de Ascenso
            </h5>
            <a href="examenes_controller.php?view=lista" class="btn-modern" style="border-color: var(--border-neon-yellow); color: var(--accent-yellow); background: rgba(251, 191, 36, 0.1);">
                <i class="bi bi-list-ul"></i> Gestionar Todos
            </a>
        </div>

        <?php if (!empty($proximos_examenes)): ?>
        <div class="table-responsive">
            <table class="table" style="color: var(--text-primary);">
                <thead style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <tr>
                        <th style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">Deportista</th>
                        <th style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">Grado Actual</th>
                        <th style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;"></th>
                        <th style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">Grado Siguiente</th>
                        <th style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">Fecha</th>
                        <th style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">Horario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($proximos_examenes, 0, 10) as $examen): ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 1rem 0.5rem;">
                            <div style="font-weight: 600;"><?php echo htmlspecialchars($examen['nombre_completo']); ?></div>
                            <small style="color: var(--text-secondary);"><?php echo htmlspecialchars($examen['documento']); ?></small>
                        </td>
                        <td style="padding: 1rem 0.5rem;">
                            <span class="badge-modern" style="background: rgba(59, 130, 246, 0.15); color: var(--accent-blue);">
                                <?php echo htmlspecialchars($examen['grado_actual']); ?>
                            </span>
                        </td>
                        <td style="padding: 1rem 0.5rem; text-align: center;">
                            <i class="bi bi-arrow-right" style="color: var(--accent-yellow);"></i>
                        </td>
                        <td style="padding: 1rem 0.5rem;">
                            <span class="badge-modern" style="background: rgba(251, 191, 36, 0.15); color: var(--accent-yellow);">
                                <?php echo htmlspecialchars($examen['grado_siguiente']); ?>
                            </span>
                        </td>
                        <td style="padding: 1rem 0.5rem;">
                            <div style="font-weight: 600; color: var(--accent-yellow);">
                                <?php echo date('d/m/Y', strtotime($examen['fecha_examen'])); ?>
                            </div>
                            <small style="color: var(--text-secondary);">
                                <?php 
                                $dias = ceil((strtotime($examen['fecha_examen']) - time()) / 86400);
                                echo $dias > 0 ? "En $dias días" : "Hoy";
                                ?>
                            </small>
                        </td>
                        <td style="padding: 1rem 0.5rem;">
                            <small style="color: var(--text-secondary);"><?php echo htmlspecialchars($examen['horario']); ?></small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-mortarboard fs-1 text-secondary opacity-25 mb-3 d-block"></i>
            <p class="text-secondary">No hay exámenes programados para los próximos 30 días.</p>
            <a href="examenes_controller.php?view=lista" class="btn btn-outline-warning btn-sm rounded-pill px-4">
                Programar Primer Examen
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
