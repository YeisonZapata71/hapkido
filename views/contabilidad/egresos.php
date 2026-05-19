<?php require_once __DIR__ . '/../../helpers/auth.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Egresos - Contabilidad SIAO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    :root {
      --bg-primary: #0f1419;
      --bg-secondary: #1a1f2e;
      --bg-card: rgba(26, 31, 46, 0.6);
      --border-neon-red: #ef4444;
      --text-primary: #e5e7eb;
      --text-secondary: #9ca3af;
      --accent-orange: #fb923c;
      --accent-blue: #60a5fa;
    }
    
    body {
      background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
      min-height: 100vh;
      font-family: 'Inter', sans-serif;
      color: var(--text-primary);
      padding-bottom: 3rem;
    }
    
    .main-container { max-width: 1400px; margin: 0 auto; padding: 2rem 1.5rem; }
    
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
    
    .page-title h2 i { color: var(--accent-orange); }
    .page-title p { color: var(--text-secondary); margin: 0.25rem 0 0 0; font-size: 0.9rem; }
    
    .glass-card {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      padding: 2rem;
      height: 100%;
    }
    
    .glass-card h5 {
      color: var(--text-primary);
      font-weight: 700;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
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
      border-color: var(--accent-orange);
      color: var(--text-primary);
      box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.1);
    }
    
    .form-control::placeholder { color: var(--text-secondary); opacity: 0.5; }
    
    .input-group-text {
      background: rgba(251, 146, 60, 0.1);
      border: 1px solid rgba(251, 146, 60, 0.3);
      color: var(--accent-orange);
      font-weight: 700;
    }
    
    .btn-danger {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      border: none;
      padding: 0.75rem 2rem;
      font-weight: 700;
      border-radius: 10px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }
    
    .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5);
    }
    
    .btn-outline-secondary {
      background: rgba(156, 163, 175, 0.1);
      border: 1px solid rgba(156, 163, 175, 0.3);
      color: var(--text-secondary);
      border-radius: 10px;
      padding: 0.65rem 1.5rem;
      font-weight: 600;
    }
    
    .btn-outline-secondary:hover {
      background: rgba(156, 163, 175, 0.2);
      color: var(--text-primary);
      border-color: rgba(156, 163, 175, 0.5);
    }
    
    .table-container {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      border: 1px solid rgba(255, 255, 255, 0.08);
      overflow: hidden;
    }
    
    .table {
      color: var(--text-primary);
      margin-bottom: 0;
    }
    
    .table thead {
      background: rgba(251, 146, 60, 0.1);
      border-bottom: 2px solid rgba(251, 146, 60, 0.3);
    }
    
    .table th {
      color: var(--accent-orange);
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
      padding: 1rem;
      border: none;
    }
    
    .table td {
      padding: 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      vertical-align: middle;
    }
    
    .table tbody tr:hover {
      background: rgba(255, 255, 255, 0.03);
    }
    
    .badge.bg-light {
      background: rgba(156, 163, 175, 0.1) !important;
      color: var(--text-secondary) !important;
      border: 1px solid rgba(156, 163, 175, 0.2);
      padding: 0.35rem 0.75rem;
      border-radius: 6px;
      font-weight: 700;
      font-size: 0.75rem;
    }
    
    .alert {
      border-radius: 12px;
      border: 1px solid;
      backdrop-filter: blur(10px);
    }
    
    .alert-success {
      background: rgba(16, 185, 129, 0.1);
      border-color: rgba(16, 185, 129, 0.3);
      color: #34d399;
    }
    
    .btn-outline-primary, .btn-outline-danger {
      padding: 0.25rem 0.5rem;
      border-radius: 6px;
      font-size: 0.8rem;
    }
    
    .btn-outline-primary {
      background: rgba(59, 130, 246, 0.1);
      border-color: rgba(59, 130, 246, 0.3);
      color: var(--accent-blue);
    }
    
    .btn-outline-primary:hover {
      background: rgba(59, 130, 246, 0.2);
      border-color: var(--accent-blue);
      color: var(--accent-blue);
    }
    
    .btn-outline-danger {
      background: rgba(239, 68, 68, 0.1);
      border-color: rgba(239, 68, 68, 0.3);
      color: var(--accent-orange);
    }
    
    .btn-outline-danger:hover {
      background: rgba(239, 68, 68, 0.2);
      border-color: var(--accent-orange);
      color: var(--accent-orange);
    }
    
    .border-danger {
      border-color: rgba(239, 68, 68, 0.3) !important;
    }
    
    .bg-light {
      background: rgba(156, 163, 175, 0.05) !important;
    }
  </style>
</head>
<body>

<div class="container main-container">
    <div class="page-header">
        <div class="page-title">
            <h2><i class="bi bi-receipt"></i> Gestión de Egresos</h2>
            <p>Registra salidas de dinero y ejecución presupuestal</p>
        </div>
        <a href="contabilidad_controller.php" class="btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Formulario -->
        <div class="col-lg-5">
            <div class="glass-card">
                <h5><i class="bi bi-dash-circle me-2"></i>Registrar Nuevo Gasto</h5>
                <form action="contabilidad_controller.php" method="POST">
                    <input type="hidden" name="accion" value="registrar_egreso">

                    <div class="mb-3">
                        <label class="form-label">Fecha del Gasto</label>
                        <input type="date" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Concepto</label>
                        <select name="concepto_id" class="form-select" id="select_concepto" required>
                            <option value="">-- Seleccione el Concepto --</option>
                            <?php foreach ($conceptos as $c): ?>
                                <option value="<?php echo $c['id']; ?>" data-rubro="<?php echo $c['rubro_predeterminado_id']; ?>">
                                    <?php echo htmlspecialchars($c['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rubro Presupuestal (CDP)</label>
                        <select name="rubro_id" class="form-select border-danger" id="select_rubro" required>
                            <option value="">-- Seleccione Origen de Fondos --</option>
                            <?php foreach ($rubros as $r): ?>
                                <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small style="font-size:0.75rem; color: var(--text-secondary)"><i class="bi bi-info-circle"></i> Afectará el saldo de este rubro.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Beneficiario / Tercero</label>
                        
                        <!-- Select de afiliados -->
                        <select name="afiliado_id" class="form-select select2 mb-2" id="select_afiliado">
                            <option value="">-- Externo / Otro --</option>
                            <?php foreach ($afiliados as $af): ?>
                                <option value="<?php echo $af['id']; ?>"><?php echo htmlspecialchars($af['nombre_completo']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Input texto para externos -->
                        <div id="campo_externo">
                            <input type="text" name="beneficiario" class="form-control" placeholder="Nombre del proveedor o persona externa">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Valor ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="monto" class="form-control" placeholder="0" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Cuenta Origen (Pago) *</label>
                            <select name="cuenta_id" class="form-select" required>
                                <?php foreach ($cuentas as $cta): ?>
                                    <option value="<?php echo $cta['id']; ?>">
                                        <?php echo htmlspecialchars($cta['nombre']); ?> 
                                        (Saldo: $<?php echo number_format($cta['saldo_actual'], 0); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Comprobante</label>
                            <input type="text" name="comprobante" class="form-control" placeholder="Factura #">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 py-2 fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-right me-2"></i> Registrar Gasto
                    </button>
                </form>
            </div>
        </div>

        <!-- Historial -->
        <div class="col-lg-7">
            <div class="glass-card">
                <h5 class="mb-4">Últimos Egresos</h5>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Concepto</th>
                                    <th>Beneficiario</th>
                                    <th>Cuenta</th>
                                    <th class="text-end">Monto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movimientos as $m): 
                                    if($m['tipo'] != 'egreso') continue;    
                                ?>
                                <tr>
                                    <td><?php echo date('d/m', strtotime($m['fecha'])); ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($m['concepto_nombre']); ?></div>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($m['tercero_final'] ?? $m['tercero_nombre']); ?>
                                    </td>
                                    <td><span class="badge bg-light order"><?php echo htmlspecialchars($m['cuenta_nombre'] ?? '-'); ?></span></td>
                                    <td class="text-end fw-bold" style="color: var(--accent-orange)">-$<?php echo number_format($m['monto'], 0); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="contabilidad_controller.php?view=imprimir_recibo&id=<?php echo $m['id']; ?>" target="_blank" class="btn btn-outline-secondary py-0 px-1" title="Ver Comprobante">
                                                <i class="bi bi-printer" style="font-size:0.8rem"></i>
                                            </a>
                                            <a href="contabilidad_controller.php?view=editar_movimiento&id=<?php echo $m['id']; ?>" class="btn btn-outline-primary py-0 px-1" title="Editar">
                                                <i class="bi bi-pencil" style="font-size:0.8rem"></i>
                                            </a>
                                            <form action="contabilidad_controller.php" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar registro?');">
                                                <input type="hidden" name="accion" value="eliminar_movimiento">
                                                <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                                                <input type="hidden" name="redirect_view" value="egresos">
                                                <button type="submit" class="btn btn-outline-danger py-0 px-1" title="Eliminar">
                                                    <i class="bi bi-trash" style="font-size:0.8rem"></i>
                                                </button>
                                            </form>
                                        </div>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        $('#select_afiliado').on('change', function() {
            if ($(this).val() === "") {
                $('#campo_externo').slideDown();
                $('input[name="beneficiario"]').prop('required', true);
            } else {
                $('#campo_externo').slideUp();
                $('input[name="beneficiario"]').prop('required', false);
            }
        });
    });

    document.getElementById('select_concepto').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var rubroDefault = selectedOption.getAttribute('data-rubro');
        
        if (rubroDefault) {
            var rubroSelect = document.getElementById('select_rubro');
            rubroSelect.value = rubroDefault;
            rubroSelect.classList.add('bg-light');
            setTimeout(() => rubroSelect.classList.remove('bg-light'), 500);
        }
    });
</script>
</body>
</html>
