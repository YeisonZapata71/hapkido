<?php
// views/examenes/lista.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Exámenes - SIAO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        
        .page-title h2 i { color: var(--accent-yellow); }
        .page-title p { color: var(--text-secondary); margin: 0.25rem 0 0 0; font-size: 0.9rem; }
        
        .glass-card {
          background: var(--bg-card);
          backdrop-filter: blur(10px);
          border-radius: 16px;
          border: 1px solid rgba(255, 255, 255, 0.1);
          padding: 1.5rem;
          margin-bottom: 1.5rem;
        }
        
        /* Stats row */
        .stat-mini-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-mini-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .stat-mini-info h4 { margin: 0; font-weight: 700; font-size: 1.2rem; }
        .stat-mini-info p { margin: 0; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; }
        
        /* Tabs */
        .nav-tabs {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            gap: 0.5rem;
        }
        
        .nav-tabs .nav-link {
            color: var(--text-secondary);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 10px 10px 0 0;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
        }
        
        .nav-tabs .nav-link.active {
            color: var(--accent-yellow);
            background: rgba(251, 191, 36, 0.1);
            border-bottom: 2px solid var(--accent-yellow);
        }
        
        /* Table */
        .table { color: var(--text-primary); margin-bottom: 0; }
        .table thead th {
            color: var(--text-secondary);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0.5rem;
        }
        .table tbody td {
            padding: 1rem 0.5rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Buttons */
        .btn-modern {
          background: rgba(251, 191, 36, 0.1);
          border: 1px solid var(--border-neon-yellow);
          color: var(--accent-yellow);
          padding: 0.65rem 1.5rem;
          border-radius: 10px;
          font-weight: 600;
          font-size: 0.9rem;
          transition: all 0.3s ease;
          display: inline-flex;
          align-items: center;
          gap: 0.5rem;
          text-decoration: none;
        }
        
        .btn-modern:hover {
          background: rgba(251, 191, 36, 0.2);
          box-shadow: 0 0 20px rgba(251, 191, 36, 0.4);
          color: var(--accent-yellow);
          transform: translateY(-2px);
        }
        
        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: all 0.2s;
            border: 1px solid transparent;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-secondary);
        }
        
        .btn-action:hover {
            transform: scale(1.1);
            color: var(--text-primary);
        }

        .btn-action.success:hover { background: rgba(16, 185, 129, 0.2); color: var(--accent-green); border-color: var(--border-neon-green); }
        .btn-action.primary:hover { background: rgba(59, 130, 246, 0.2); color: var(--accent-blue); border-color: var(--border-neon-blue); }
        
        /* Badges */
        .badge-status {
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        
        .badge-pending { background: rgba(251, 191, 36, 0.15); color: var(--accent-yellow); border: 1px solid rgba(251, 191, 36, 0.3); }
        .badge-approved { background: rgba(16, 185, 129, 0.15); color: var(--accent-green); border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge-rejected { background: rgba(239, 68, 68, 0.15); color: var(--accent-orange); border: 1px solid rgba(239, 68, 68, 0.3); }

        /* Modal */
        .modal-content {
            background: #1a1f2e;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            color: var(--text-primary);
        }
        .modal-header { border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding: 1.5rem; }
        .modal-footer { border-top: 1px solid rgba(255, 255, 255, 0.05); padding: 1.5rem; }
        .modal-title { font-weight: 700; color: var(--accent-yellow); }
        .btn-close { filter: invert(1); }
        
        .form-label { color: var(--text-secondary); font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem; }
        .form-control, .form-select {
            background: rgba(15, 20, 25, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 0.75rem;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(15, 20, 25, 0.8);
            border-color: var(--accent-yellow);
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.1);
            color: var(--text-primary);
        }
        
        /* Select2 Dark Modification */
        .select2-container--default .select2-selection--single {
            background-color: rgba(15, 20, 25, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            height: 48px;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text-primary);
            padding-left: 12px;
        }
        .select2-dropdown {
            background-color: #1a1f2e;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--accent-yellow);
            color: #000;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(251, 191, 36, 0.2);
        }
        .select2-search--dropdown .select2-search__field {
            background-color: rgba(15, 20, 25, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }
    </style>
</head>
<body>

<div class="container main-container">
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <h2><i class="bi bi-mortarboard-fill"></i> Gestión de Exámenes</h2>
            <p>Programación, calificación y seguimiento de ascensos de grado</p>
        </div>
        <div class="d-flex gap-2">
            <a href="../../dashboard.php" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard
            </a>
            <button class="btn-modern" data-bs-toggle="modal" data-bs-target="#modalProgramar">
                <i class="bi bi-plus-lg"></i> Programar Examen
            </button>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="background: rgba(16, 185, 129, 0.15); color: var(--accent-green); border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="background: rgba(239, 68, 68, 0.15); color: var(--accent-orange); border-radius: 12px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
    </div>
    <?php endif; ?>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-mini-card">
                <div class="stat-mini-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--accent-blue);">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <div class="stat-mini-info">
                    <h4><?php echo $estadisticas['total_examenes']; ?></h4>
                    <p>Total Exámenes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-mini-card">
                <div class="stat-mini-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">
                    <i class="bi bi-check-all"></i>
                </div>
                <div class="stat-mini-info">
                    <h4><?php echo $estadisticas['aprobados']; ?></h4>
                    <p>Aprobados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-mini-card">
                <div class="stat-mini-icon" style="background: rgba(251, 191, 36, 0.1); color: var(--accent-yellow);">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-mini-info">
                    <h4><?php echo $estadisticas['pendientes']; ?></h4>
                    <p>Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-mini-card">
                <div class="stat-mini-icon" style="background: rgba(251, 146, 60, 0.1); color: var(--accent-orange);">
                    <i class="bi bi-percent"></i>
                </div>
                <div class="stat-mini-info">
                    <h4><?php echo $estadisticas['tasa_aprobacion']; ?>%</h4>
                    <p>Éxito</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tables Container -->
    <div class="glass-card">
        <ul class="nav nav-tabs mb-4" id="examTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="proximos-tab" data-bs-toggle="tab" data-bs-target="#proximos" type="button">
                    Próximos <span class="badge rounded-pill ms-1" style="background: var(--accent-yellow); color: #000;"><?php echo count($proximos_examenes); ?></span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes" type="button">
                    Por Calificar <span class="badge rounded-pill ms-1" style="background: var(--accent-orange); color: #fff;"><?php echo count($pendientes_calificacion); ?></span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="todos-tab" data-bs-toggle="tab" data-bs-target="#todos" type="button">Todo el Historial</button>
            </li>
        </ul>

        <div class="tab-content" id="examTabsContent">
            <!-- Tab: Próximos -->
            <div class="tab-pane fade show active" id="proximos">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Deportista</th>
                                <th>Grado Actual</th>
                                <th></th>
                                <th>Grado Siguiente</th>
                                <th>Fecha</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($proximos_examenes)): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">No hay exámenes programados para los próximos 30 días.</td></tr>
                            <?php else: foreach ($proximos_examenes as $ex): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($ex['nombre_completo']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($ex['documento']); ?> • <?php echo htmlspecialchars($ex['horario']); ?></small>
                                </td>
                                <td><span class="badge-status badge-pending" style="background: rgba(59, 130, 246, 0.1); color: var(--accent-blue); border-color: rgba(59, 130, 246, 0.2);"><?php echo htmlspecialchars($ex['grado_actual']); ?></span></td>
                                <td class="text-center"><i class="bi bi-arrow-right text-muted"></i></td>
                                <td><span class="badge-status badge-pending"><?php echo htmlspecialchars($ex['grado_siguiente']); ?></span></td>
                                <td>
                                    <div class="fw-bold"><?php echo date('d/m/Y', strtotime($ex['fecha_examen'])); ?></div>
                                    <small class="text-warning"><?php 
                                        $dif = ceil((strtotime($ex['fecha_examen']) - time()) / 86400);
                                        echo $dif > 0 ? "En $dif días" : "Hoy";
                                    ?></small>
                                </td>
                                <td class="text-end">
                                    <a href="examenes_controller.php?view=detalle&id=<?php echo $ex['id']; ?>" class="btn-action" title="Ver Detalles"><i class="bi bi-eye"></i></a>
                                    <button class="btn-action primary" onclick="abrirModalCalificar(<?php echo htmlspecialchars(json_encode($ex)); ?>)" title="Calificar"><i class="bi bi-check2-circle"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Pendientes -->
            <div class="tab-pane fade" id="pendientes">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Deportista</th>
                                <th>Grado Actual</th>
                                <th>Grado Siguiente</th>
                                <th>Fecha Original</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pendientes_calificacion)): ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">No hay exámenes pendientes de calificación.</td></tr>
                            <?php else: foreach ($pendientes_calificacion as $ex): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($ex['nombre_completo']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($ex['documento']); ?></small>
                                </td>
                                <td><span class="badge-status badge-pending" style="background: rgba(59, 130, 246, 0.1); color: var(--accent-blue); border-color: rgba(59, 130, 246, 0.2);"><?php echo htmlspecialchars($ex['grado_actual']); ?></span></td>
                                <td><span class="badge-status badge-pending"><?php echo htmlspecialchars($ex['grado_siguiente']); ?></span></td>
                                <td><div class="fw-bold text-danger"><?php echo date('d/m/Y', strtotime($ex['fecha_examen'])); ?></div></td>
                                <td class="text-end">
                                    <button class="btn btn-warning btn-sm fw-bold px-3 rounded-pill" onclick="abrirModalCalificar(<?php echo htmlspecialchars(json_encode($ex)); ?>)">
                                        Calificar Ahora
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Todos -->
            <div class="tab-pane fade" id="todos">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Deportista</th>
                                <th>Ascenso</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($todos_examenes as $ex): ?>
                            <tr>
                                <td><?php echo date('d/m/y', strtotime($ex['fecha_examen'])); ?></td>
                                <td>
                                    <div class="fw-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($ex['nombre_completo']); ?></div>
                                </td>
                                <td>
                                    <small class="text-muted"><?php echo htmlspecialchars($ex['grado_actual']); ?></small> 
                                    <i class="bi bi-arrow-right mx-1"></i>
                                    <small class="text-warning fw-bold"><?php echo htmlspecialchars($ex['grado_siguiente']); ?></small>
                                </td>
                                <td>
                                    <?php if ($ex['aprobado'] === null): ?>
                                        <span class="badge-status badge-pending">Esperando</span>
                                    <?php elseif ($ex['aprobado'] == 1): ?>
                                        <span class="badge-status badge-approved"><i class="bi bi-check-circle"></i> Aprobado</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-rejected"><i class="bi bi-x-circle"></i> Reprobado</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="examenes_controller.php?view=detalle&id=<?php echo $ex['id']; ?>" class="btn-action"><i class="bi bi-three-dots-vertical"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Programar -->
<div class="modal fade" id="modalProgramar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="examenes_controller.php" method="POST" class="modal-content">
            <input type="hidden" name="accion" value="programar">
            <div class="modal-header">
                <h5 class="modal-title">Programar Nuevo Examen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label">Deportista *</label>
                    <select name="afiliado_id" id="afiliado_select" class="form-select select2" required style="width: 100%;">
                        <option value="">Seleccione deportista...</option>
                        <?php foreach ($deportistas as $d): ?>
                            <option value="<?php echo $d['id']; ?>" data-grado="<?php echo $d['grado_cinturon']; ?>">
                                <?php echo htmlspecialchars($d['nombre_completo']); ?> (<?php echo $d['grado_cinturon']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Grado Actual</label>
                        <input type="text" name="grado_actual" id="grado_actual_input" class="form-control" readonly required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Grado Siguiente *</label>
                        <select name="grado_siguiente" id="grado_siguiente_select" class="form-select" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Fecha del Examen *</label>
                        <input type="date" name="fecha_examen" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2" placeholder="Opcional..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn-modern px-4 rounded-pill fw-bold shadow-sm">Confirmar Programación</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Calificar -->
<div class="modal fade" id="modalCalificar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="examenes_controller.php" method="POST" class="modal-content">
            <input type="hidden" name="accion" value="calificar">
            <input type="hidden" name="id" id="calificar_id">
            <div class="modal-header">
                <h5 class="modal-title">Calificar Examen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <h5 id="calificar_nombre" class="fw-bold mb-1"></h5>
                <p class="text-muted small mb-4">Ascenso: <span id="calificar_grado_de" class="text-primary"></span> → <span id="calificar_grado_a" class="text-warning fw-bold"></span></p>
                
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <input type="radio" class="btn-check" name="aprobado" id="res_aprobado" value="1" required>
                    <label class="btn btn-outline-success px-4 py-2 fw-bold" for="res_aprobado">
                        <i class="bi bi-check-circle me-2"></i> Aprobado
                    </label>

                    <input type="radio" class="btn-check" name="aprobado" id="res_reprobado" value="0" required>
                    <label class="btn btn-outline-danger px-4 py-2 fw-bold" for="res_reprobado">
                        <i class="bi bi-x-circle me-2"></i> Reprobado
                    </label>
                </div>

                <div class="text-start">
                    <label class="form-label">Comentarios / Feedback</label>
                    <textarea name="observaciones" class="form-control" rows="3" placeholder="Puntos a mejorar o felicitaciones..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm">Guardar Resultado</button>
            </div>
        </form>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const grados = <?php echo json_encode($grados); ?>;

    $(document).ready(function() {
        $('.select2').select2({
            dropdownParent: $('#modalProgramar'),
            placeholder: 'Buscar deportista...',
            language: { noResults: () => "No se encontraron resultados" }
        });

        $('#afiliado_select').on('change', function() {
            const option = $(this).find(':selected');
            const gradoActual = option.data('grado');
            $('#grado_actual_input').val(gradoActual);
            
            // Sugerir grado siguiente
            const selectSiguiente = $('#grado_siguiente_select');
            selectSiguiente.empty().append('<option value="">Seleccione...</option>');
            
            const index = grados.indexOf(gradoActual);
            if (index !== -1 && index < grados.length - 1) {
                // Agregar todos los grados superiores, pero seleccionar el siguiente por defecto
                for (let i = index + 1; i < grados.length; i++) {
                    const selected = (i === index + 1) ? 'selected' : '';
                    selectSiguiente.append(`<option value="${grados[i]}" ${selected}>${grados[i]}</option>`);
                }
            } else {
                grados.forEach(g => {
                    selectSiguiente.append(`<option value="${g}">${g}</option>`);
                });
            }
        });
    });

    function abrirModalCalificar(ex) {
        $('#calificar_id').val(ex.id);
        $('#calificar_nombre').text(ex.nombre_completo);
        $('#calificar_grado_de').text(ex.grado_actual);
        $('#calificar_grado_a').text(ex.grado_siguiente);
        
        var modal = new bootstrap.Modal(document.getElementById('modalCalificar'));
        modal.show();
    }
</script>
</body>
</html>
