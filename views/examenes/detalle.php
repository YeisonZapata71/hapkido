<?php
// views/examenes/detalle.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Examen - SIAO</title>
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
          max-width: 1000px;
          margin: 0 auto;
          padding: 2rem 1.5rem;
        }
        
        .glass-card {
          background: var(--bg-card);
          backdrop-filter: blur(10px);
          border-radius: 16px;
          border: 1px solid rgba(255, 255, 255, 0.1);
          padding: 2rem;
          margin-bottom: 2rem;
        }
        
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 700;
        }
        
        .badge-pending { background: rgba(59, 130, 246, 0.15); color: var(--accent-blue); }
        .badge-approved { background: rgba(16, 185, 129, 0.15); color: var(--accent-green); }
        .badge-rejected { background: rgba(239, 68, 68, 0.15); color: var(--accent-orange); }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 1.5rem;
            border-left: 2px solid rgba(255, 255, 255, 0.1);
        }
        
        .timeline-item:last-child { border-left-color: transparent; }
        
        .timeline-marker {
            position: absolute;
            left: -9px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--accent-blue);
            border: 4px solid var(--bg-secondary);
        }
        
        .timeline-item.approved .timeline-marker { background: var(--accent-green); }
        .timeline-item.pending .timeline-marker { background: var(--accent-yellow); }
    </style>
</head>
<body>

<div class="container main-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="examenes_controller.php?view=lista" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Volver al Listado
        </a>
        <h4 class="m-0 fw-bold">Detalles del Examen</h4>
    </div>

    <!-- Main Detail Card -->
    <div class="glass-card">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h2 class="fw-800 mb-1"><?php echo htmlspecialchars($examen['nombre_completo']); ?></h2>
                <p class="text-secondary mb-0">
                    <i class="bi bi-person-badge me-2"></i><?php echo htmlspecialchars($examen['documento']); ?> • 
                    <i class="bi bi-clock me-2"></i><?php echo htmlspecialchars($examen['horario']); ?>
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <?php if ($examen['aprobado'] === null): ?>
                    <span class="badge-status badge-pending"><i class="bi bi-hourglass-split me-2"></i>PENDIENTE</span>
                <?php elseif ($examen['aprobado'] == 1): ?>
                    <span class="badge-status badge-approved"><i class="bi bi-check-circle-fill me-2"></i>APROBADO</span>
                <?php else: ?>
                    <span class="badge-status badge-rejected"><i class="bi bi-x-circle-fill me-2"></i>REPROBADO</span>
                <?php endif; ?>
            </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.05);">

        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <label class="text-secondary small fw-bold text-uppercase mb-1">Grado Evaluado</label>
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 px-3 rounded text-center" style="background: rgba(59, 130, 246, 0.1); color: var(--accent-blue);">
                        <div class="small fw-bold">DE</div>
                        <div class="fw-bold"><?php echo htmlspecialchars($examen['grado_actual']); ?></div>
                    </div>
                    <i class="bi bi-arrow-right text-secondary"></i>
                    <div class="p-2 px-3 rounded text-center" style="background: rgba(251, 191, 36, 0.1); color: var(--accent-yellow);">
                        <div class="small fw-bold">A</div>
                        <div class="fw-bold"><?php echo htmlspecialchars($examen['grado_siguiente']); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="text-secondary small fw-bold text-uppercase mb-1">Fecha de Examen</label>
                <div class="fs-5 fw-bold"><?php echo date('d \d\e F, Y', strtotime($examen['fecha_examen'])); ?></div>
                <small class="text-secondary">Programado el <?php echo date('d/m/Y', strtotime($examen['creado_en'])); ?></small>
            </div>
            <div class="col-md-4 text-md-end">
                <?php if ($examen['aprobado'] === null): ?>
                    <button class="btn btn-warning fw-bold px-4 rounded-pill" onclick="window.location.href='examenes_controller.php?view=lista'">
                        Calificar Resultado
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($examen['observaciones'])): ?>
        <div class="mt-4 p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
            <label class="text-secondary small fw-bold text-uppercase mb-2 d-block">Observaciones / Feedback</label>
            <p class="mb-0 italic text-primary" style="font-style: italic;">"<?php echo nl2br(htmlspecialchars($examen['observaciones'])); ?>"</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- History Section -->
    <h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-info"></i>Historial de Exámenes del Deportista</h5>
    <div class="glass-card p-4">
        <?php if (empty($historial)): ?>
            <p class="text-center text-muted m-0 py-4">No hay registros previos para este deportista.</p>
        <?php else: ?>
            <?php foreach ($historial as $h): ?>
                <div class="timeline-item <?php echo $h['aprobado'] == 1 ? 'approved' : ($h['aprobado'] === null ? 'pending' : ''); ?>">
                    <div class="timeline-marker"></div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-bold"><?php echo htmlspecialchars($h['grado_actual']); ?> → <?php echo htmlspecialchars($h['grado_siguiente']); ?></div>
                            <div class="small text-secondary"><?php echo date('d/m/Y', strtotime($h['fecha_examen'])); ?></div>
                        </div>
                        <div>
                            <?php if ($h['aprobado'] == 1): ?>
                                <span class="text-success fw-bold small"><i class="bi bi-check-circle me-1"></i>APROBADO</span>
                            <?php elseif ($h['aprobado'] === null): ?>
                                <span class="text-warning fw-bold small"><i class="bi bi-clock me-1"></i>PENDIENTE</span>
                            <?php else: ?>
                                <span class="text-danger fw-bold small"><i class="bi bi-x-circle me-1"></i>REPROBADO</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($h['observaciones'])): ?>
                        <div class="mt-2 small text-muted" style="font-style: italic;">
                            - <?php echo htmlspecialchars(substr($h['observaciones'], 0, 100)); ?><?php echo strlen($h['observaciones']) > 100 ? '...' : ''; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
