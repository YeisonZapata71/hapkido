<?php require_once __DIR__ . '/../../helpers/auth.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuración - Contabilidad SIAO</title>
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
      --accent-green: #34d399;
      --accent-orange: #fb923c;
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
    
    .page-title h2 i { color: var(--accent-blue); }
    .page-title p { color: var(--text-secondary); margin: 0.25rem 0 0 0; font-size: 0.9rem; }
    
    .glass-card {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      padding: 2rem;
      margin-bottom: 1.5rem;
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
      padding: 0.5rem 1.5rem;
      font-weight: 700;
      border-radius: 10px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
    }
    
    .btn-success {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border: none;
      padding: 0.5rem 1.5rem;
      font-weight: 700;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }
    
    .btn-danger {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      border: none;
      padding: 0.5rem 1.5rem;
      font-weight: 700;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }
    
    .btn-outline-secondary {
      background: rgba(156, 163, 175, 0.1);
      border: 1px solid rgba(156, 163, 175, 0.3);
      color: var(--text-secondary);
      border-radius: 10px;
      padding: 0.5rem 1rem;
      font-weight: 600;
    }
    
    .list-group-item {
      background: rgba(15, 20, 25, 0.3);
      border: 1px solid rgba(255, 255, 255, 0.05);
      color: var(--text-primary);
      padding: 1rem;
      margin-bottom: 0.5rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .list-group-item:hover {
      background: rgba(255, 255, 255, 0.05);
      border-color: rgba(255, 255, 255, 0.1);
    }
    
    .btn-link {
      color: var(--accent-blue);
      text-decoration: none;
    }
    
    .btn-link:hover {
      color: var(--accent-blue);
      text-decoration: underline;
    }
    
    .btn-outline-primary {
      background: rgba(59, 130, 246, 0.1);
      border: 1px solid rgba(59, 130, 246, 0.3);
      color: var(--accent-blue);
      padding: 0.35rem 0.75rem;
      border-radius: 8px;
      font-size: 0.85rem;
    }
    
    .btn-outline-danger {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: var(--accent-orange);
      padding: 0.35rem 0.75rem;
      border-radius: 8px;
      font-size: 0.85rem;
    }
    
    .alert-success {
      background: rgba(16, 185, 129, 0.1);
      border: 1px solid rgba(16, 185, 129, 0.3);
      color: var(--accent-green);
      border-radius: 12px;
    }
    
    .modal-content {
      background: var(--bg-card);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
    }
    
    .modal-header {
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .modal-footer {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .btn-close {
      filter: invert(1);
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
      color: var(--accent-orange) !important;
      border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .pct-input {
      max-width: 80px;
      text-align: center;
      font-weight: 700;
    }
    
    .text-success { color: var(--accent-green) !important; }
    .text-warning { color: #fcd34d !important; }
    .text-danger { color: var(--accent-orange) !important; }
    .text-primary { color: var(--accent-blue) !important; }
  </style>
</head>
<body>

<div class="container main-container">
    <div class="page-header">
        <div class="page-title">
            <h2><i class="bi bi-sliders"></i> Configuración Avanzada</h2>
            <p>Gestiona conceptos, metas y reglas de distribución</p>
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
        <!-- Columna Izquierda -->
        <div class="col-lg-7">
            <!-- Metas Anuales -->
            <div class="glass-card">
                <form action="contabilidad_controller.php" method="POST">
                    <input type="hidden" name="accion" value="guardar_conf_anual">
                    <h5 class="text-primary">Metas Financieras <?php echo $anio_actual; ?></h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Meta Ingresos (Año)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="meta_ingreso" class="form-control fw-bold" value="<?php echo $presupuesto['meta_ingreso_total'] ?? 0; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Utilidad Año Anterior</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="saldo_anterior" class="form-control" value="<?php echo $presupuesto['saldo_anterior'] ?? 0; ?>">
                            </div>
                        </div>
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-primary">Guardar Metas</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Conceptos de Ingreso -->
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-success mb-0"><i class="bi bi-arrow-down-circle me-2"></i>Conceptos de Ingreso</h5>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoConcepto" onclick="setModalTipo('ingreso')">
                        <i class="bi bi-plus-lg"></i> Nuevo
                    </button>
                </div>
                
                <p style="font-size: 0.85rem; color: var(--text-secondary)"><i class="bi bi-info-circle"></i> Haz clic en "Distribución" para definir qué porcentaje va a cada Rubro (CDP).</p>

                <div class="list-group">
                    <?php foreach ($conceptos_ingreso as $c): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold"><?php echo htmlspecialchars($c['nombre']); ?></div>
                            <button class="btn btn-link btn-sm p-0" onclick='editarConcepto(<?php echo json_encode($c); ?>)'>
                                <small><i class="bi bi-pencil"></i> Editar</small>
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                             <a href="contabilidad_controller.php?view=config&editar_concepto=<?php echo $c['id']; ?>#distribucion" 
                                class="btn btn-outline-primary">
                                <i class="bi bi-pie-chart-fill me-1"></i> Distribución
                             </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Conceptos de Egreso -->
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-danger mb-0"><i class="bi bi-arrow-up-circle me-2"></i>Conceptos de Gasto</h5>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoConcepto" onclick="setModalTipo('egreso')">
                        <i class="bi bi-plus-lg"></i> Nuevo
                    </button>
                </div>

                <div class="list-group">
                    <?php foreach ($conceptos_egreso as $c): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold"><?php echo htmlspecialchars($c['nombre']); ?></div>
                            <button class="btn btn-link btn-sm p-0" onclick='editarConcepto(<?php echo json_encode($c); ?>)'>
                                <small><i class="bi bi-pencil"></i> Editar</small>
                            </button>
                        </div>
                        <span class="badge bg-danger"><?php echo htmlspecialchars($c['rubro_nombre'] ?? 'Sin rubro'); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Columna Derecha -->
        <div class="col-lg-5">
            <!-- Firma Digital -->
            <div class="glass-card">
                <h5 class="mb-3"><i class="bi bi-pen-fill me-2"></i>Firma Digital</h5>
                <div class="text-center mb-3 p-3" style="background: rgba(255,255,255,0.05); border-radius: 8px;">
                    <?php 
                        $ruta_firma = __DIR__ . '/../../assets/img/firma_digital.png';
                        $url_firma = '../../assets/img/firma_digital.png';
                        if (file_exists($ruta_firma)): 
                    ?>
                        <img src="<?php echo $url_firma; ?>?v=<?php echo time(); ?>" alt="Firma Actual" style="max-height: 80px; max-width: 100%;">
                        <p class="small text-success mt-2 mb-0"><i class="bi bi-check-circle"></i> Firma cargada</p>
                    <?php else: ?>
                        <p class="text-muted mb-0">No hay firma configurada</p>
                    <?php endif; ?>
                </div>
                
                <form action="contabilidad_controller.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="accion" value="subir_firma">
                    <div class="mb-3">
                        <label class="form-label">Subir nueva firma (PNG/JPG)</label>
                        <input type="file" name="firma_imagen" class="form-control form-control-sm" accept="image/png, image/jpeg" required>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-cloud-upload me-2"></i>Actualizar Firma
                    </button>
                    <small class="d-block mt-2 text-muted" style="font-size: 0.75rem;">Se usará en los recibos impresos.</small>
                </form>
            </div>

            <!-- Cuentas Bancarias -->
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0"><i class="bi bi-bank me-2"></i>Cuentas y Saldos</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCuenta" onclick="limpiarModalCuenta()">
                        <i class="bi bi-plus-lg"></i> Nueva
                    </button>
                </div>

                <div class="list-group">
                    <?php foreach ($cuentas as $cta): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold"><?php echo htmlspecialchars($cta['nombre']); ?></div>
                            <small style="color: var(--text-secondary)"><?php echo htmlspecialchars($cta['tipo_cuenta']); ?> <?php echo $cta['numero_cuenta'] ? '• '.$cta['numero_cuenta'] : ''; ?></small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success">$<?php echo number_format($cta['saldo_actual'], 0); ?></span>
                            <button class="btn btn-outline-primary btn-sm py-0 px-2" onclick='editarCuenta(<?php echo json_encode($cta); ?>)'>
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Rubros Presupuestales -->
            <div class="glass-card" id="distribucion">
                <h5><i class="bi bi-folder-fill me-2"></i>Rubros Presupuestales (CDPs)</h5>
                
                <?php if (isset($_GET['editar_concepto'])): 
                    $concepto_id = intval($_GET['editar_concepto']);
                    $concepto_edit = null;
                    foreach (array_merge($conceptos_ingreso, $conceptos_egreso) as $c) {
                        if ($c['id'] == $concepto_id) {
                            $concepto_edit = $c;
                            break;
                        }
                    }
                    if ($concepto_edit):
                ?>
                <div class="alert alert-success mb-3">
                    <strong>Editando distribución de:</strong> <?php echo htmlspecialchars($concepto_edit['nombre']); ?>
                </div>
                <form action="contabilidad_controller.php" method="POST">
                    <input type="hidden" name="accion" value="guardar_distribucion">
                    <input type="hidden" name="concepto_id" value="<?php echo $concepto_id; ?>">
                    
                    <?php foreach ($rubros as $r): 
                        $pct_actual = 0;
                        if (isset($concepto_edit['distribucion'][$r['id']])) {
                            $pct_actual = $concepto_edit['distribucion'][$r['id']];
                        }
                    ?>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="form-label mb-0"><?php echo htmlspecialchars($r['nombre']); ?></label>
                        <div class="input-group" style="max-width: 120px;">
                            <input type="number" name="pct[<?php echo $r['id']; ?>]" class="form-control pct-input" min="0" max="100" step="0.01" value="<?php echo $pct_actual; ?>">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="text-end mt-3">
                        <span class="me-3">Total: <strong id="total_display">0.00%</strong></span>
                        <button type="submit" class="btn btn-success">Guardar Distribución</button>
                    </div>
                </form>
                <?php endif; ?>
                <?php else: ?>
                <p style="font-size: 0.85rem; color: var(--text-secondary)">Selecciona un concepto de ingreso para editar su distribución.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Concepto -->
<div class="modal fade" id="modalNuevoConcepto" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form action="contabilidad_controller.php" method="POST" class="modal-content">
      <input type="hidden" name="accion" value="crear_concepto">
      <input type="hidden" name="tipo" id="modalTipoInput">
      
      <div class="modal-header" id="modalHeaderBg">
        <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Concepto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
            <label class="form-label">Nombre del Concepto</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3" id="divRubroDefault">
            <label class="form-label">Rubro Predeterminado (Solo Egresos)</label>
            <select name="rubro_predeterminado_id" class="form-select">
                <option value="">-- Sin rubro --</option>
                <?php foreach ($rubros as $r): ?>
                    <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Cuenta Bancaria -->
<div class="modal fade" id="modalCuenta" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form action="contabilidad_controller.php" method="POST" class="modal-content">
      <input type="hidden" name="accion" value="guardar_cuenta">
      <input type="hidden" name="id" id="cuentaId">
      
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modalCuentaTitulo">Nueva Cuenta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" id="cuentaNombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" id="cuentaTipo" class="form-select">
                <option value="Efectivo">Efectivo</option>
                <option value="Ahorros">Ahorros</option>
                <option value="Corriente">Corriente</option>
                <option value="Billetera Digital">Billetera Digital</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Número de Cuenta</label>
            <input type="text" name="numero_cuenta" id="cuentaNumero" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Saldo Inicial</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="saldo_inicial" id="cuentaSaldo" class="form-control" value="0">
            </div>
            <small style="color: var(--text-secondary)">Si edita, el saldo no se actualiza aquí, solo nombre/tipo.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Editar Concepto -->
<div class="modal fade" id="modalEditarConcepto" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form action="contabilidad_controller.php" method="POST" class="modal-content">
      <input type="hidden" name="accion" value="editar_concepto_simple">
      <input type="hidden" name="id" id="editConceptoId">
      
      <div class="modal-header">
        <h5 class="modal-title fw-bold text-primary">Editar Concepto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" id="editConceptoNombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" id="editConceptoTipo" class="form-select">
                <option value="ingreso">Ingreso</option>
                <option value="egreso">Egreso</option>
            </select>
            <div class="alert alert-warning mt-2 py-2" style="font-size: 0.85rem">
                <i class="bi bi-exclamation-triangle"></i> Cambiar el tipo puede afectar reportes históricos.
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function setModalTipo(tipo) {
        document.getElementById('modalTipoInput').value = tipo;
        const header = document.getElementById('modalHeaderBg');
        const divRubro = document.getElementById('divRubroDefault');
        
        if (tipo === 'ingreso') {
            header.className = 'modal-header';
            document.getElementById('modalTitulo').textContent = 'Nuevo Concepto de Ingreso';
            divRubro.style.display = 'none';
        } else {
            header.className = 'modal-header';
            document.getElementById('modalTitulo').textContent = 'Nuevo Concepto de Gasto';
            divRubro.style.display = 'block';
        }
    }

    const inputs = document.querySelectorAll('.pct-input');
    const totalDisplay = document.getElementById('total_display');
    
    if (inputs.length > 0) {
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                let sum = 0;
                inputs.forEach(i => sum += parseFloat(i.value || 0));
                totalDisplay.textContent = sum.toFixed(2) + '%';
                
                if (Math.abs(sum - 100) > 0.01) {
                    totalDisplay.className = 'text-warning fw-bold';
                } else {
                    totalDisplay.className = 'text-success fw-bold';
                }
            });
        });
    }

    function limpiarModalCuenta() {
        document.getElementById('cuentaId').value = '';
        document.getElementById('cuentaNombre').value = '';
        document.getElementById('cuentaNumero').value = '';
        document.getElementById('cuentaSaldo').value = '';
        document.getElementById('cuentaSaldo').disabled = false;
        document.getElementById('modalCuentaTitulo').textContent = 'Nueva Cuenta';
    }

    function editarCuenta(cta) {
        document.getElementById('cuentaId').value = cta.id;
        document.getElementById('cuentaNombre').value = cta.nombre;
        document.getElementById('cuentaTipo').value = cta.tipo_cuenta;
        document.getElementById('cuentaNumero').value = cta.numero_cuenta;
        document.getElementById('cuentaSaldo').value = cta.saldo_actual;
        document.getElementById('cuentaSaldo').disabled = true;
        document.getElementById('modalCuentaTitulo').textContent = 'Editar Cuenta';
        
        var modal = new bootstrap.Modal(document.getElementById('modalCuenta'));
        modal.show();
    }

    function editarConcepto(c) {
        document.getElementById('editConceptoId').value = c.id;
        document.getElementById('editConceptoNombre').value = c.nombre;
        document.getElementById('editConceptoTipo').value = c.tipo;
        
        var modal = new bootstrap.Modal(document.getElementById('modalEditarConcepto'));
        modal.show();
    }
</script>
</body>
</html>
