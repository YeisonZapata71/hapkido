<?php
require_once '../../helpers/auth.php';
require_once '../../helpers/db.php';
verificarSesion();

$conn = conectarDB();
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];
$nombre = $_SESSION['nombre'];

// Si es instructor, obtener su ID
$instructor_id = null;
if ($rol === 'instructor') {
    $stmt = $conn->prepare("SELECT id FROM afiliados_siao WHERE usuario_id = ? AND rol = 'instructor'");
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $instructor_id = $result ? $result['id'] : null;
    $stmt->close();
}

// Parámetros de filtros
$filtro_afiliado = $_GET['afiliado'] ?? '';
$filtro_estado = $_GET['estado'] ?? '';
$filtro_color = $_GET['color'] ?? '';
$filtro_grado = $_GET['grado'] ?? '';
$filtro_fecha_desde = $_GET['fecha_desde'] ?? '';
$filtro_fecha_hasta = $_GET['fecha_hasta'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Construir consulta con filtros
$where_conditions = [];
$params = [];
$types = '';

// Filtro por instructor si no es admin
if ($rol === 'instructor' && $instructor_id) {
    $where_conditions[] = "ef.evaluador_id = ?";
    $params[] = $instructor_id;
    $types .= 'i';
}

if ($filtro_afiliado) {
    $where_conditions[] = "(a.nombre_completo LIKE ? OR a.documento LIKE ?)";
    $params[] = "%$filtro_afiliado%";
    $params[] = "%$filtro_afiliado%";
    $types .= 'ss';
}

if ($filtro_estado) {
    $where_conditions[] = "ef.estado = ?";
    $params[] = $filtro_estado;
    $types .= 's';
}

if ($filtro_color) {
    $where_conditions[] = "ef.color = ?";
    $params[] = $filtro_color;
    $types .= 's';
}

if ($filtro_grado) {
    $where_conditions[] = "ef.grado = ?";
    $params[] = $filtro_grado;
    $types .= 's';
}

if ($filtro_fecha_desde) {
    $where_conditions[] = "ef.fecha >= ?";
    $params[] = $filtro_fecha_desde;
    $types .= 's';
}

if ($filtro_fecha_hasta) {
    $where_conditions[] = "ef.fecha <= ?";
    $params[] = $filtro_fecha_hasta;
    $types .= 's';
}

$where_clause = '';
if (!empty($where_conditions)) {
    $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
}

// Contar total de registros
$count_query = "
    SELECT COUNT(*) as total
    FROM evaluaciones_franjas ef
    JOIN afiliados_siao a ON ef.afiliado_id = a.id
    LEFT JOIN afiliados_siao ev ON ef.evaluador_id = ev.id
    $where_clause
";

$stmt = $conn->prepare($count_query);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total_records = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$total_pages = ceil($total_records / $per_page);

// Obtener registros de la página actual
$query = "
    SELECT ef.*, 
           a.nombre_completo as afiliado_nombre,
           a.documento as afiliado_documento,
           ev.nombre_completo as evaluador_nombre
    FROM evaluaciones_franjas ef
    JOIN afiliados_siao a ON ef.afiliado_id = a.id
    LEFT JOIN afiliados_siao ev ON ef.evaluador_id = ev.id
    $where_clause
    ORDER BY ef.fecha DESC, ef.fecha_creacion DESC
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($query);
$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';
$stmt->bind_param($types, ...$params);
$stmt->execute();
$evaluaciones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Obtener opciones para filtros
$afiliados_options = [];
$colores_options = [];
$grados_options = [];

// Afiliados
$result = $conn->query("SELECT DISTINCT id, nombre_completo FROM afiliados_siao WHERE rol != 'instructor' ORDER BY nombre_completo");
$afiliados_options = $result->fetch_all(MYSQLI_ASSOC);

// Colores
$result = $conn->query("SELECT DISTINCT color FROM evaluaciones_franjas ORDER BY color");
$colores_options = $result->fetch_all(MYSQLI_ASSOC);

// Grados
$result = $conn->query("SELECT DISTINCT grado FROM evaluaciones_franjas WHERE grado IS NOT NULL ORDER BY grado");
$grados_options = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Evaluaciones - Club SIAO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-clock-history text-primary"></i> Historial de Evaluaciones</h2>
                <p class="text-muted mb-0">Total de <?= $total_records ?> evaluaciones registradas</p>
            </div>
            <div>
                <a href="index.php#evaluaciones" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <a href="evaluar_franjas.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nueva Evaluación
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-funnel"></i> Filtros de Búsqueda</h6>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Afiliado</label>
                        <input type="text" class="form-control" name="afiliado" 
                               value="<?= htmlspecialchars($filtro_afiliado) ?>" 
                               placeholder="Nombre o documento">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="estado">
                            <option value="">Todos</option>
                            <option value="aprobado" <?= $filtro_estado == 'aprobado' ? 'selected' : '' ?>>Aprobado</option>
                            <option value="reprobado" <?= $filtro_estado == 'reprobado' ? 'selected' : '' ?>>Reprobado</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Color</label>
                        <select class="form-select" name="color">
                            <option value="">Todos</option>
                            <?php foreach ($colores_options as $color): ?>
                            <option value="<?= htmlspecialchars($color['color']) ?>" <?= $filtro_color == $color['color'] ? 'selected' : '' ?>>
                                <?= ucfirst(htmlspecialchars($color['color'])) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Grado</label>
                        <select class="form-select" name="grado">
                            <option value="">Todos</option>
                            <?php foreach ($grados_options as $grado): ?>
                            <option value="<?= htmlspecialchars($grado['grado']) ?>" <?= $filtro_grado == $grado['grado'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($grado['grado']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Desde</label>
                        <input type="date" class="form-control" name="fecha_desde" value="<?= htmlspecialchars($filtro_fecha_desde) ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hasta</label>
                        <input type="date" class="form-control" name="fecha_hasta" value="<?= htmlspecialchars($filtro_fecha_hasta) ?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        <a href="historial_evaluaciones.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de resultados -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bi bi-table"></i> Resultados 
                    <span class="badge bg-primary"><?= $total_records ?></span>
                </h6>
                <div>
                    <button class="btn btn-outline-success btn-sm" onclick="exportarExcel()">
                        <i class="bi bi-file-earmark-excel"></i> Exportar
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($evaluaciones)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Afiliado</th>
                                <th>Documento</th>
                                <th>Grado</th>
                                <th>Franja</th>
                                <th>Estado</th>
                                <th>Evaluador</th>
                                <th>Observaciones</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evaluaciones as $eval): ?>
                            <tr>
                                <td>
                                    <strong><?= date('d/m/Y', strtotime($eval['fecha'])) ?></strong><br>
                                    <small class="text-muted"><?= date('H:i', strtotime($eval['fecha_creacion'])) ?></small>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($eval['afiliado_nombre']) ?></strong>
                                </td>
                                <td>
                                    <code><?= htmlspecialchars($eval['afiliado_documento']) ?></code>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($eval['grado'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= ucfirst(htmlspecialchars($eval['color'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $eval['estado'] == 'aprobado' ? 'bg-success' : 'bg-danger' ?>">
                                        <i class="bi bi-<?= $eval['estado'] == 'aprobado' ? 'check' : 'x' ?>-circle"></i>
                                        <?= $eval['estado'] == 'aprobado' ? 'APROBADO' : 'REPROBADO' ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars($eval['evaluador_nombre'] ?? 'N/A') ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($eval['observaciones'])): ?>
                                    <small class="text-muted" title="<?= htmlspecialchars($eval['observaciones']) ?>">
                                        <?= htmlspecialchars(substr($eval['observaciones'], 0, 30)) ?><?= strlen($eval['observaciones']) > 30 ? '...' : '' ?>
                                    </small>
                                    <?php else: ?>
                                    <small class="text-muted">-</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-info" onclick="verDetalles(<?= $eval['id'] ?>)" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <?php if ($rol === 'admin' || ($rol === 'instructor' && $eval['evaluador_id'] == $instructor_id)): ?>
                                        <button class="btn btn-outline-warning" onclick="editarEvaluacion(<?= $eval['id'] ?>)" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <?php if ($total_pages > 1): ?>
                <div class="card-footer">
                    <nav aria-label="Paginación de evaluaciones">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                            
                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Mostrando <?= ($offset + 1) ?> - <?= min($offset + $per_page, $total_records) ?> 
                            de <?= $total_records ?> evaluaciones
                        </small>
                    </div>
                </div>
                <?php endif; ?>

                <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron evaluaciones</h5>
                    <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles -->
    <div class="modal fade" id="modalDetalles" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-eye"></i> Detalles de Evaluación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalDetallesContent">
                    <!-- Contenido cargado via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function verDetalles(evaluacionId) {
        const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
        const content = document.getElementById('modalDetallesContent');
        
        content.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';
        modal.show();
        
        fetch('ver_detalle_evaluacion.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + encodeURIComponent(evaluacionId)
        })
        .then(response => response.text())
        .then(data => {
            content.innerHTML = data;
        })
        .catch(error => {
            content.innerHTML = '<div class="alert alert-danger">Error al cargar los detalles</div>';
            console.error('Error:', error);
        });
    }

    function editarEvaluacion(evaluacionId) {
        // Redirigir a página de edición
        window.location.href = 'editar_evaluacion.php?id=' + evaluacionId;
    }

    function exportarExcel() {
        // Construir URL con filtros actuales
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'excel');
        window.open('exportar_evaluaciones.php?' + params.toString(), '_blank');
    }

    // Mantener filtros en la URL
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                // Auto-submit después de un pequeño delay
                setTimeout(() => {
                    form.submit();
                }, 500);
            });
        });
    });
    </script>
</body>
</html>