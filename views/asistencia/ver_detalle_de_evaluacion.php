<?php
require_once '../../helpers/auth.php';
require_once '../../helpers/db.php';

// Determinar el método de acceso
$es_peticion_ajax = ($_SERVER['REQUEST_METHOD'] === 'POST');
$es_acceso_directo = ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']));

// Si es acceso directo, iniciar sesión y verificar
if ($es_acceso_directo) {
    session_start();
    verificarSesion();
    
    // Verificar rol permitido
    $rol = $_SESSION['rol'];
    if (!in_array($rol, ['admin', 'instructor'])) {
        die('Acceso denegado');
    }
}

// Verificar que sea una petición válida
if (!$es_peticion_ajax && !$es_acceso_directo) {
    exit('Acceso no autorizado');
}

// Obtener ID según el método
if ($es_peticion_ajax) {
    $evaluacion_id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
} else {
    $evaluacion_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
}

if (!$evaluacion_id) {
    if ($es_acceso_directo) {
        die('ID de evaluación no válido');
    } else {
        exit('<div class="alert alert-danger">ID de evaluación no válido</div>');
    }
}

try {
    $conn = conectarDB();
    
    // Obtener detalles completos de la evaluación
    $stmt = $conn->prepare("
        SELECT ef.*, 
               a.nombre_completo as afiliado_nombre,
               a.documento as afiliado_documento,
               a.telefono as afiliado_telefono,
               a.email as afiliado_email,
               a.grado as afiliado_grado_actual,
               ev.nombre_completo as evaluador_nombre,
               ef.fecha_creacion,
               ef.fecha_actualizacion
        FROM evaluaciones_franjas ef
        JOIN afiliados_siao a ON ef.afiliado_id = a.id
        LEFT JOIN afiliados_siao ev ON ef.evaluador_id = ev.id
        WHERE ef.id = ?
    ");
    
    $stmt->bind_param('i', $evaluacion_id);
    $stmt->execute();
    $evaluacion = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$evaluacion) {
        if ($es_acceso_directo) {
            die('Evaluación no encontrada');
        } else {
            exit('<div class="alert alert-warning">Evaluación no encontrada</div>');
        }
    }
    
    // Verificar permisos si es instructor
    $rol = $_SESSION['rol'];
    $usuario_id = $_SESSION['usuario_id'];
    
    if ($rol === 'instructor') {
        $stmt = $conn->prepare("SELECT id FROM afiliados_siao WHERE usuario_id = ? AND rol = 'instructor'");
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $instructor_result = $stmt->get_result()->fetch_assoc();
        $instructor_id = $instructor_result ? $instructor_result['id'] : null;
        $stmt->close();
        
        if ($instructor_id != $evaluacion['evaluador_id']) {
            if ($es_acceso_directo) {
                die('No tienes permisos para ver esta evaluación');
            } else {
                exit('<div class="alert alert-danger">No tienes permisos para ver esta evaluación</div>');
            }
        }
    }
    
    // Obtener historial de evaluaciones del mismo afiliado
    $stmt = $conn->prepare("
        SELECT ef.fecha, ef.color, ef.grado, ef.estado, ev.nombre_completo as evaluador
        FROM evaluaciones_franjas ef
        LEFT JOIN afiliados_siao ev ON ef.evaluador_id = ev.id
        WHERE ef.afiliado_id = ? AND ef.id != ?
        ORDER BY ef.fecha DESC
        LIMIT 5
    ");
    
    $stmt->bind_param('ii', $evaluacion['afiliado_id'], $evaluacion_id);
    $stmt->execute();
    $historial = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    $conn->close();
    
} catch (Exception $e) {
    error_log("Error en ver_detalle_evaluacion.php: " . $e->getMessage());
    if ($es_acceso_directo) {
        die('Error interno del servidor');
    } else {
        exit('<div class="alert alert-danger">Error interno del servidor</div>');
    }
}

// Si es acceso directo, mostrar HTML completo
if ($es_acceso_directo): ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Evaluación - Club SIAO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-clipboard-check text-primary"></i> Detalles de Evaluación</h2>
            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <!-- Información del afiliado -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-person-circle"></i> Información del Afiliado
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nombre:</strong><br>
                        <span class="text-primary"><?= htmlspecialchars($evaluacion['afiliado_nombre']) ?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Documento:</strong><br>
                        <code><?= htmlspecialchars($evaluacion['afiliado_documento']) ?></code>
                    </div>
                    <div class="col-md-6 mt-2">
                        <strong>Grado Actual:</strong><br>
                        <span class="badge bg-secondary"><?= htmlspecialchars($evaluacion['afiliado_grado_actual'] ?? 'N/A') ?></span>
                    </div>
                    <div class="col-md-6 mt-2">
                        <strong>Contacto:</strong><br>
                        <small class="text-muted">
                            <?= htmlspecialchars($evaluacion['afiliado_telefono'] ?? 'N/A') ?><br>
                            <?= htmlspecialchars($evaluacion['afiliado_email'] ?? 'N/A') ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalles de la evaluación -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="bi bi-clipboard-check"></i> Detalles de la Evaluación
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Fecha de Evaluación:</strong><br>
                        <span class="text-primary"><?= date('d/m/Y', strtotime($evaluacion['fecha'])) ?></span>
                    </div>
                    <div class="col-md-4">
                        <strong>Grado Evaluado:</strong><br>
                        <span class="badge bg-secondary fs-6"><?= htmlspecialchars($evaluacion['grado']) ?></span>
                    </div>
                    <div class="col-md-4">
                        <strong>Franja:</strong><br>
                        <span class="badge bg-primary fs-6"><?= ucfirst(htmlspecialchars($evaluacion['color'])) ?></span>
                    </div>
                    <div class="col-md-6 mt-3">
                        <strong>Resultado:</strong><br>
                        <span class="badge <?= $evaluacion['estado'] == 'aprobado' ? 'bg-success' : 'bg-danger' ?> fs-6">
                            <i class="bi bi-<?= $evaluacion['estado'] == 'aprobado' ? 'check' : 'x' ?>-circle"></i>
                            <?= strtoupper($evaluacion['estado']) ?>
                        </span>
                    </div>
                    <div class="col-md-6 mt-3">
                        <strong>Evaluador:</strong><br>
                        <span class="text-info"><?= htmlspecialchars($evaluacion['evaluador_nombre'] ?? 'N/A') ?></span>
                    </div>
                    <?php if (!empty($evaluacion['observaciones'])): ?>
                    <div class="col-12 mt-3">
                        <strong>Observaciones:</strong><br>
                        <div class="bg-light p-3 rounded">
                            <?= nl2br(htmlspecialchars($evaluacion['observaciones'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Información de registro -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history"></i> Información de Registro
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Fecha de Creación:</strong><br>
                        <small class="text-muted">
                            <?= $evaluacion['fecha_creacion'] ? date('d/m/Y H:i:s', strtotime($evaluacion['fecha_creacion'])) : 'N/A' ?>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <strong>Última Actualización:</strong><br>
                        <small class="text-muted">
                            <?= $evaluacion['fecha_actualizacion'] ? date('d/m/Y H:i:s', strtotime($evaluacion['fecha_actualizacion'])) : 'N/A' ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial previo del afiliado -->
        <?php if (!empty($historial)): ?>
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul"></i> Historial Previo del Afiliado
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Grado</th>
                                <th>Franja</th>
                                <th>Resultado</th>
                                <th>Evaluador</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial as $eval_prev): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($eval_prev['fecha'])) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($eval_prev['grado']) ?></span></td>
                                <td><span class="badge bg-primary"><?= ucfirst(htmlspecialchars($eval_prev['color'])) ?></span></td>
                                <td>
                                    <span class="badge <?= $eval_prev['estado'] == 'aprobado' ? 'bg-success' : 'bg-danger' ?>">
                                        <?= strtoupper($eval_prev['estado']) ?>
                                    </span>
                                </td>
                                <td><small><?= htmlspecialchars($eval_prev['evaluador'] ?? 'N/A') ?></small></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($es_acceso_directo): ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php endif; ?>