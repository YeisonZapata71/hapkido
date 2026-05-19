<?php
// views/admin/ventana_asistencia.php
require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../helpers/db.php';

verificarSesion();
verificarRol('admin');

$conn = conectarDB();

// Obtener configuración actual
$config = [
    'ventana_activa' => '0',
    'ventana_desde'  => '',
    'ventana_hasta'  => '',
    'ventana_motivo' => ''
];

$res = $conn->query("SELECT clave, valor FROM config_asistencia");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $config[$row['clave']] = $row['valor'];
    }
}

$activa = ($config['ventana_activa'] === '1');
// Validar si expiró automáticamente
$hoy = date('Y-m-d');
if ($activa && $config['ventana_hasta'] && $hoy > $config['ventana_hasta']) {
    $activa = false; // Expirada visualmente
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Ventana de Asistencia - SIAO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #d7e1ec 100%);
            min-height: 100vh;
            font-family: 'Outfit', sans-serif;
            padding: 2rem 1rem;
        }
        .main-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
        }
        .bg-active {
            background: #d1e7dd;
            color: #0f5132;
        }
        .bg-inactive {
            background: #f8d7da;
            color: #842029;
        }
        .switch-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
        }
        .form-switch .form-check-input {
            width: 3rem;
            height: 1.5rem;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="main-container">
    <a href="../../dashboard.php" class="btn btn-sm btn-outline-secondary rounded-pill px-4 mb-4">
        <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard
    </a>

    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Ventana Especial</h2>
            <span class="status-badge <?= $activa ? 'bg-active' : 'bg-inactive' ?>">
                <i class="bi <?= $activa ? 'bi-check-circle-fill' : 'bi-x-circle-fill' ?> me-1"></i>
                <?= $activa ? 'ACTIVA' : 'INACTIVA' ?>
            </span>
        </div>

        <p class="text-muted mb-4">
            Habilita un rango de fechas excepcional para que los instructores puedan registrar asistencias atrasadas.
        </p>

        <?php if(isset($_GET['ok'])): ?>
            <div class="alert alert-success rounded-3 border-0 bg-success bg-opacity-10 text-success">
                ✅ Configuración guardada correctamente.
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger rounded-3 border-0 bg-danger bg-opacity-10 text-danger">
                ❌ Error: <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <!-- Activar / Desactivar -->
        <div class="switch-container">
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="toggleVentana" <?= $activa ? 'checked' : '' ?>>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Estado de la Ventana</h6>
                <small class="text-muted">
                    <?= $activa ? 'Los instructores pueden usar el rango definido.' : 'Regla normal (solo hasta 2 días atrás).' ?>
                </small>
            </div>
        </div>

        <!-- Formulario de Configuración -->
        <form action="../../controllers/guardar_ventana_asistencia.php" method="POST" id="formVentana" style="<?= $activa ? '' : 'opacity: 0.5; pointer-events: none;' ?>">
            <input type="hidden" name="accion" id="accionInput" value="<?= $activa ? 'activar' : 'desactivar' ?>">
            
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label fw-bold">Fecha Desde</label>
                    <input type="date" name="ventana_desde" class="form-control rounded-3" value="<?= htmlspecialchars($config['ventana_desde']) ?>" <?= $activa ? 'required' : '' ?>>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Fecha Hasta</label>
                    <input type="date" name="ventana_hasta" class="form-control rounded-3" value="<?= htmlspecialchars($config['ventana_hasta']) ?>" <?= $activa ? 'required' : '' ?>>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Motivo (opcional)</label>
                <input type="text" name="ventana_motivo" class="form-control rounded-3" placeholder="Ej. Recuperación de clases por falla en el sistema" value="<?= htmlspecialchars($config['ventana_motivo']) ?>">
                <div class="form-text">Este mensaje se mostrará a los instructores.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" id="btnGuardar">
                GUARDAR CAMBIOS
            </button>
        </form>

    </div>
</div>

<script>
    const toggle = document.getElementById('toggleVentana');
    const form = document.getElementById('formVentana');
    const accionInput = document.getElementById('accionInput');
    const inputs = form.querySelectorAll('input[type="date"]');

    toggle.addEventListener('change', function() {
        if(this.checked) {
            form.style.opacity = '1';
            form.style.pointerEvents = 'auto';
            accionInput.value = 'activar';
            inputs.forEach(i => i.required = true);
        } else {
            // Si apaga el switch, guardamos como desactivar automáticamente o al presionar guardar.
            form.style.opacity = '0.5';
            form.style.pointerEvents = 'none';
            accionInput.value = 'desactivar';
            inputs.forEach(i => i.required = false);
            // Auto submit para desactivar de una vez
            form.submit();
        }
    });
</script>

</body>
</html>
