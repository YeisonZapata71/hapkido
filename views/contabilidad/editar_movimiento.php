<?php
// views/contabilidad/editar_movimiento.php
if (!defined('BASE_PATH')) {
    // Definir si no existe (aunque idealmente debería venir del index)
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Movimiento - SIAO Contabilidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
          --bg-primary: #0f1419;
          --bg-card: rgba(26, 31, 46, 0.6);
          --text-primary: #e5e7eb;
          --text-secondary: #9ca3af;
          --accent-blue: #60a5fa;
        }
        
        body {
          background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
          min-height: 100vh;
          font-family: 'Inter', sans-serif;
          color: var(--text-primary);
        }
        
        .glass-card {
          background: var(--bg-card);
          backdrop-filter: blur(10px);
          border-radius: 16px;
          border: 1px solid rgba(255, 255, 255, 0.1);
          padding: 2rem;
        }
        
        .form-label {
          color: var(--text-secondary);
          font-size: 0.85rem;
          font-weight: 600;
          margin-bottom: 0.5rem;
          text-transform: uppercase;
          letter-spacing: 0.3px;
        }
        
        .form-control, .form-select {
          background: rgba(15, 20, 25, 0.5);
          border: 1px solid rgba(255, 255, 255, 0.1);
          color: var(--text-primary);
          border-radius: 8px;
          padding: 0.65rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
          background: rgba(15, 20, 25, 0.7);
          border-color: var(--accent-blue);
          color: var(--text-primary);
          box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .input-group-text {
          background: rgba(59, 130, 246, 0.1);
          border: 1px solid rgba(59, 130, 246, 0.3);
          color: var(--accent-blue);
          font-weight: 700;
        }
        
        .btn-primary {
          background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
          border: none;
          padding: 0.75rem 2rem;
          font-weight: 700;
          border-radius: 10px;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
          transform: translateY(-2px);
          box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
        }
        
        .btn-outline-secondary {
          background: rgba(156, 163, 175, 0.1);
          border: 1px solid rgba(156, 163, 175, 0.3);
          color: var(--text-secondary);
          border-radius: 10px;
          padding: 0.5rem 1rem;
          font-weight: 600;
          font-size: 0.9rem;
        }
        
        .btn-outline-secondary:hover {
          background: rgba(156, 163, 175, 0.2);
          color: var(--text-primary);
        }
        
        .alert-warning {
          background: rgba(251, 191, 36, 0.1);
          border: 1px solid rgba(251, 191, 36, 0.3);
          color: #fcd34d;
          border-radius: 12px;
        }
        
        .text-info {
          color: var(--accent-blue) !important;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0 fw-bold" style="color: var(--accent-blue)">
                        <i class="bi bi-pencil-square me-2"></i>Editar Movimiento #<?php echo $movimiento['id']; ?>
                    </h4>
                    <a href="contabilidad_controller.php?view=<?php echo ($movimiento['tipo'] == 'ingreso') ? 'ingresos' : 'egresos'; ?>" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="bi bi-x-lg"></i> Cancelar
                    </a>
                </div>

                <form action="contabilidad_controller.php" method="POST">
                    <input type="hidden" name="accion" value="editar_movimiento">
                    <input type="hidden" name="id" value="<?php echo $movimiento['id']; ?>">
                    <input type="hidden" name="redirect_view" value="<?php echo ($movimiento['tipo'] == 'ingreso') ? 'ingresos' : 'egresos'; ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="fecha" class="form-control" required value="<?php echo $movimiento['fecha']; ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Tipo</label>
                            <input type="text" class="form-control" value="<?php echo ucfirst($movimiento['tipo']); ?>" readonly disabled>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Concepto *</label>
                            <select name="concepto_id" class="form-select" required>
                                <?php foreach ($conceptos as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo ($c['id'] == $movimiento['concepto_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if ($movimiento['tipo'] == 'egreso' && !empty($rubros)): ?>
                        <div class="col-12">
                            <label class="form-label">Rubro (Egreso) *</label>
                            <?php 
                                $dist = json_decode($movimiento['detalle_distribucion_json'], true);
                                $rubro_actual = null;
                                if ($dist) {
                                    $rubro_actual = array_key_first($dist);
                                }
                            ?>
                            <select name="rubro_id" class="form-select">
                                <?php foreach ($rubros as $r): ?>
                                    <option value="<?php echo $r['id']; ?>" <?php echo ($r['id'] == $rubro_actual) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($r['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-info d-block mt-1"><i class="bi bi-info-circle"></i> Cambiar el rubro reasignará el 100% del gasto a este CDP.</small>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-6">
                            <label class="form-label">Cuenta Bancaria *</label>
                            <select name="cuenta_id" class="form-select" required>
                                <?php foreach ($cuentas as $cta): ?>
                                    <option value="<?php echo $cta['id']; ?>" <?php echo ($cta['id'] == $movimiento['cuenta_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cta['nombre']); ?> (<?php echo $cta['tipo_cuenta']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Monto total *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="monto" class="form-control fw-bold" required min="0" step="0.01" value="<?php echo $movimiento['monto']; ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="2"><?php echo htmlspecialchars($movimiento['observaciones']); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary py-2 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-save me-2"></i> Guardar Cambios
                        </button>
                    </div>

                    <div class="alert alert-warning mt-4 mb-0 py-2">
                        <small><i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Atención:</strong> Modificar el monto o la cuenta revertirá automáticamente los saldos anteriores y aplicará los nuevos. Asegúrese de que los datos sean correctos.</small>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
