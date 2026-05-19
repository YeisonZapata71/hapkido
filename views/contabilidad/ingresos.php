<?php require_once __DIR__ . '/../../helpers/auth.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ingresos - Contabilidad SIAO</title>
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
      --border-neon-green: #10b981;
      --text-primary: #e5e7eb;
      --text-secondary: #9ca3af;
      --accent-green: #34d399;
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
    
    .page-title h2 i { color: var(--accent-green); }
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
      border-color: var(--accent-green);
      color: var(--text-primary);
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    
    .form-control::placeholder { color: var(--text-secondary); opacity: 0.5; }
    
    .input-group-text {
      background: rgba(16, 185, 129, 0.1);
      border: 1px solid rgba(16, 185, 129, 0.3);
      color: var(--accent-green);
      font-weight: 700;
    }
    
    .btn-success {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border: none;
      padding: 0.75rem 2rem;
      font-weight: 700;
      border-radius: 10px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }
    
    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
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
      background: rgba(16, 185, 129, 0.1);
      border-bottom: 2px solid rgba(16, 185, 129, 0.3);
    }
    
    .table th {
      color: var(--accent-green);
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
    
    .badge {
      padding: 0.35rem 0.75rem;
      border-radius: 6px;
      font-weight: 700;
      font-size: 0.75rem;
    }
    
    .bg-success {
      background: rgba(16, 185, 129, 0.2) !important;
      color: var(--accent-green) !important;
      border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .bg-danger {
      background: rgba(239, 68, 68, 0.2) !important;
      color: #fb923c !important;
      border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .bg-light {
      background: rgba(156, 163, 175, 0.1) !important;
      color: var(--text-secondary) !important;
      border: 1px solid rgba(156, 163, 175, 0.2);
    }
    
    .nav-tabs {
      border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }
    
    .nav-tabs .nav-link {
      border: none;
      color: var(--text-secondary);
      font-weight: 600;
      padding: 1rem 1.5rem;
      transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link.active {
      color: var(--accent-green);
      background: transparent;
      border-bottom: 3px solid var(--accent-green);
    }
    
    .nav-tabs .nav-link:hover {
      color: var(--text-primary);
    }
    
    .alert {
      border-radius: 12px;
      border: 1px solid;
      backdrop-filter: blur(10px);
    }
    
    .alert-success {
      background: rgba(16, 185, 129, 0.1);
      border-color: rgba(16, 185, 129, 0.3);
      color: var(--accent-green);
    }
    
    .alert-danger {
      background: rgba(239, 68, 68, 0.1);
      border-color: rgba(239, 68, 68, 0.3);
      color: #fb923c;
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
      color: #fb923c;
    }
    
    .btn-outline-danger:hover {
      background: rgba(239, 68, 68, 0.2);
      border-color: #fb923c;
      color: #fb923c;
    }
    
    .btn-outline-success {
      background: rgba(16, 185, 129, 0.1);
      border-color: rgba(16, 185, 129, 0.3);
      color: var(--accent-green);
      border-radius: 50%;
      width: 32px;
      height: 32px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    
    .btn-outline-success:hover {
      background: rgba(16, 185, 129, 0.2);
      color: var(--accent-green);
      box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
    }
    
    /* Select2 Dark Theme */
    .select2-container--bootstrap-5 .select2-selection {
      background: rgba(15, 20, 25, 0.5);
      border-color: rgba(255, 255, 255, 0.1);
      color: var(--text-primary);
    }
    
    .select2-container--bootstrap-5 .select2-dropdown {
      background: var(--bg-secondary);
      border-color: rgba(255, 255, 255, 0.2);
    }
    
    .select2-container--bootstrap-5 .select2-results__option {
      color: var(--text-primary);
    }
    
    .select2-container--bootstrap-5 .select2-results__option--highlighted {
      background: rgba(16, 185, 129, 0.2);
      color: var(--accent-green);
    }
  </style>
</head>
<body>

<div class="container main-container">
    <div class="page-header">
        <div class="page-title">
            <h2><i class="bi bi-cash-coin"></i> Gestión de Ingresos</h2>
            <p>Registra pagos y gestiona estados de cuenta</p>
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
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Formulario -->
        <div class="col-lg-5">
            <div class="glass-card">
                <h5><i class="bi bi-plus-circle me-2"></i>Registrar Nuevo Pago</h5>
                <form action="contabilidad_controller.php" method="POST">
                    <input type="hidden" name="accion" value="registrar_ingreso">

                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deportista / Afiliado</label>
                        <select name="afiliado_id" class="form-select select2" id="select_afiliado">
                            <option value="">-- Seleccionar (Opcional) --</option>
                            <?php foreach ($afiliados as $af): ?>
                                <option value="<?php echo $af['id']; ?>"><?php echo htmlspecialchars($af['nombre_completo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3" id="campo_externo" style="display:none;">
                        <label class="form-label">Nombre Tercero (Externo)</label>
                        <input type="text" name="tercero_nombre" class="form-control" placeholder="Ej: Evento Externo">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Concepto</label>
                        <select name="concepto_id" class="form-select" required>
                            <?php foreach ($conceptos as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
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
                            <label class="form-label">Cuenta Destino *</label>
                            <select name="cuenta_id" class="form-select" required>
                                <?php foreach ($cuentas as $cta): ?>
                                    <option value="<?php echo $cta['id']; ?>">
                                        <?php echo htmlspecialchars($cta['nombre']); ?> 
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Ref. Pago / Banco Origen</label>
                            <input type="text" name="banco_origen" class="form-control" placeholder="Ej. Nequi, etc.">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Comprobante #</label>
                            <input type="text" name="comprobante" class="form-control" placeholder="Opcional">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i> Registrar Pago
                    </button>
                </form>
            </div>
        </div>

        <!-- Tablas -->
        <div class="col-lg-7">
            <div class="glass-card p-0 overflow-hidden">
                <div class="p-3 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#deudores" role="tab">Estado de Cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#historial" role="tab">Historial Reciente</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content p-4">
                    <!-- Panel Deudores -->
                    <div class="tab-pane fade show active" id="deudores" role="tabpanel">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="fw-bold text-uppercase" style="color: var(--text-secondary); font-size: 0.85rem;">Cobro Mensualidad</h6>
                            <input type="text" class="form-control form-control-sm w-50" placeholder="Buscar deportista..." id="buscarDeudor">
                        </div>
                        <div class="table-container" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Deportista</th>
                                        <th>Último Pago</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaDeudores">
                                    <?php 
                                    $hoy = new DateTime();
                                    foreach ($deudores as $d): 
                                        $estado = 'Mora';
                                        $color = 'danger';
                                        $ultimo = 'Nunca';

                                        if ($d['ultima_mensualidad']) {
                                            $fecha_pago = new DateTime($d['ultima_mensualidad']);
                                            $ultimo = $fecha_pago->format('d/m/Y');
                                            $interval = $hoy->diff($fecha_pago);
                                            if ($interval->days < 32) {
                                                $estado = 'Al día';
                                                $color = 'success';
                                            }
                                        }

                                        $phone = preg_replace('/[^0-9]/', '', $d['celular']);
                                        if (substr($phone, 0, 1) != '5' && strlen($phone) == 10) $phone = '57' . $phone;
                                        $msg = "Hola {$d['nombre_completo']}, recordatorio de pago mensualidad Joong Do Ryu.";
                                        $wa_link = "https://wa.me/$phone?text=" . urlencode($msg);
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($d['nombre_completo']); ?></div>
                                            <span class="badge bg-<?php echo $color; ?> mt-1" style="font-size:0.7em"><?php echo $estado; ?></span>
                                        </td>
                                        <td class="small" style="color: var(--text-secondary)"><?php echo $ultimo; ?></td>
                                        <td>
                                            <a href="<?php echo $wa_link; ?>" target="_blank" class="btn btn-outline-success" title="Cobrar por WhatsApp">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Panel Historial -->
                    <div class="tab-pane fade" id="historial" role="tabpanel">
                        <div class="table-container">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Concepto</th>
                                        <th>Cuenta</th>
                                        <th>Monto</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($movimientos as $m): 
                                        if($m['tipo'] != 'ingreso') continue;    
                                    ?>
                                    <tr>
                                        <td><?php echo date('d/m', strtotime($m['fecha'])); ?></td>
                                        <td>
                                            <div class="fw-bold mb-0"><?php echo htmlspecialchars($m['concepto_nombre']); ?></div>
                                            <small style="color: var(--text-secondary); font-size:0.75rem"><?php echo htmlspecialchars(substr($m['tercero_final'] ?? $m['tercero_nombre'], 0, 20)); ?></small>
                                        </td>
                                        <td><span class="badge bg-light"><?php echo htmlspecialchars($m['cuenta_nombre'] ?? '-'); ?></span></td>
                                        <td class="fw-bold" style="color: var(--accent-green)">+$<?php echo number_format($m['monto'], 0); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="contabilidad_controller.php?view=imprimir_recibo&id=<?php echo $m['id']; ?>" target="_blank" class="btn btn-outline-secondary py-0 px-1" title="Ver Recibo">
                                                    <i class="bi bi-printer" style="font-size:0.8rem"></i>
                                                </a>
                                                <a href="contabilidad_controller.php?view=editar_movimiento&id=<?php echo $m['id']; ?>" class="btn btn-outline-primary py-0 px-1" title="Editar">
                                                    <i class="bi bi-pencil" style="font-size:0.8rem"></i>
                                                </a>
                                                <form action="contabilidad_controller.php" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar registro?');">
                                                    <input type="hidden" name="accion" value="eliminar_movimiento">
                                                    <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                                                    <input type="hidden" name="redirect_view" value="ingresos">
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
            } else {
                $('#campo_externo').slideUp();
            }
        });

        $('#buscarDeudor').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $("#tablaDeudores tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
</body>
</html>
