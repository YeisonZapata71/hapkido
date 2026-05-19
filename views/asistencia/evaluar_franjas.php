<?php
// SOLUCIÓN: Iniciar sesión PRIMERO, antes de cualquier include
session_start();
require_once '../../helpers/db.php';
require_once '../../helpers/auth.php';
verificarSesion();

// SOLUCIÓN: Verificar rol permitido (admin o instructor) - SIGUIENDO EL PATRÓN DEL EJEMPLO
$rol = $_SESSION['rol'];
if (!in_array($rol, ['admin', 'instructor'])) {
    header('Location: acceso-denegado.php'); // Ajusta según tu estructura
    exit;
}

// Inicializar variables
$mensaje_exito = '';
$mensaje_error = '';
$afiliados = [];
$grados = [];

// Obtener ID del instructor logueado
$conn = conectarDB();
$usuario_id = $_SESSION['usuario_id'];
$instructor_id = null;

// Si es instructor, obtener su ID de afiliado (como en el ejemplo)
if ($rol === 'instructor') {
    $stmt = $conn->prepare("SELECT id, nombre_completo FROM afiliados_siao WHERE usuario_id = ? AND rol = 'instructor'");
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result_instructor = $stmt->get_result()->fetch_assoc();
    if ($result_instructor) {
        $instructor_id = $result_instructor['id'];
    }
    $stmt->close();
} else if ($rol === 'admin') {
    // Para admin, podrías permitir seleccionar instructor o usar uno por defecto
    // Por ahora mantenemos $instructor_id como null para admin
}
// Obtener lista de afiliados para el select (excluir instructores)
try {
    $result = $conn->query("SELECT id, nombre_completo, documento FROM afiliados_siao WHERE rol != 'instructor' AND activo = 1 ORDER BY nombre_completo");
    $afiliados = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $mensaje_error = "Error al cargar la lista de afiliados: " . $e->getMessage();
}

// Obtener lista de grados disponibles
try {
    $result = $conn->query("SELECT DISTINCT grado FROM afiliados_siao WHERE grado IS NOT NULL AND grado != '' ORDER BY 
        CASE 
            WHEN grado = 'Blanco' THEN 1
            WHEN grado = 'Amarillo' THEN 2
            WHEN grado = 'Naranja' THEN 3
            WHEN grado = 'Verde' THEN 4
            WHEN grado = 'Azul' THEN 5
            WHEN grado = 'Marrón' THEN 6
            WHEN grado = 'Negro' THEN 7
            ELSE 8
        END");
    $grados = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    // Si no hay grados en la BD, usar valores por defecto
    $grados = [
        ['grado' => 'Blanco'],
        ['grado' => 'Amarillo'],
        ['grado' => 'Naranja'],
        ['grado' => 'Verde'],
        ['grado' => 'Azul'],
        ['grado' => 'Marrón'],
        ['grado' => 'Negro']
    ];
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y sanitizar inputs
    $afiliado_id = filter_var($_POST['afiliado_id'] ?? null, FILTER_VALIDATE_INT);
    $color = trim($_POST['franja'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $fecha = trim($_POST['fecha'] ?? '');
    $grado_evaluado = trim($_POST['grado'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');

    // Validaciones
    $estados_permitidos = ['aprobado', 'reprobado'];
    $colores_permitidos = ['amarilla', 'roja', 'verde', 'negra', 'blanca', 'azul'];
    
    if (!$afiliado_id || !in_array($color, $colores_permitidos) || !in_array($estado, $estados_permitidos) || empty($fecha) || empty($grado_evaluado) || !$instructor_id) {
        $mensaje_error = "Error: Datos de evaluación incompletos o no válidos";
    } else {
        try {
            // Verificar si ya existe una evaluación para este afiliado, color y grado en la misma fecha
            $check_query = "SELECT id FROM evaluaciones_franjas WHERE afiliado_id = ? AND color = ? AND grado = ? AND fecha = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("isss", $afiliado_id, $color, $grado_evaluado, $fecha);
            $check_stmt->execute();
            $check_stmt->store_result();
            
            if ($check_stmt->num_rows > 0) {
                // Actualizar registro existente
                $query = "UPDATE evaluaciones_franjas SET estado = ?, evaluador_id = ?, observaciones = ?, fecha_actualizacion = NOW() WHERE afiliado_id = ? AND color = ? AND grado = ? AND fecha = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sisssss", $estado, $instructor_id, $observaciones, $afiliado_id, $color, $grado_evaluado, $fecha);
            } else {
                // Insertar nuevo registro
                $query = "INSERT INTO evaluaciones_franjas (afiliado_id, color, estado, fecha, grado, evaluador_id, observaciones, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("issssis", $afiliado_id, $color, $estado, $fecha, $grado_evaluado, $instructor_id, $observaciones);
            }
            
            if ($stmt->execute()) {
                $mensaje_exito = "Evaluación guardada correctamente";
                // Limpiar el formulario después de un envío exitoso
                $_POST = [];
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $conn->error);
            }
            
            $stmt->close();
            $check_stmt->close();
            
        } catch (Exception $e) {
            $mensaje_error = "Error interno del sistema. Por favor, contacte al administrador.";
            error_log("Error en evaluar_franjas.php: " . $e->getMessage());
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación de Franjas - Club SIAO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-clipboard-check text-primary"></i> Evaluación de Franjas</h2>
                <p class="text-muted mb-0">Registrar resultado por afiliado</p>
                <?php if ($result_instructor): ?>
                <small class="text-info"><i class="bi bi-person-badge"></i> Evaluador: <?= htmlspecialchars($result_instructor['nombre_completo']) ?></small>
                <?php endif; ?>
            </div>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <!-- Mensajes -->
        <?php if (!empty($mensaje_exito)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?= $mensaje_exito ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($mensaje_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?= $mensaje_error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (!$instructor_id): ?>
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-exclamation-triangle"></i> No se pudo identificar el instructor. Contacte al administrador.
        </div>
        <?php else: ?>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Nueva Evaluación</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Afiliado <span class="text-danger">*</span></label>
                            <select class="form-select" name="afiliado_id" required>
                                <option value="">Selecciona un afiliado</option>
                                <?php foreach ($afiliados as $afiliado): ?>
                                <option value="<?= $afiliado['id'] ?>" <?= (isset($_POST['afiliado_id']) && $_POST['afiliado_id'] == $afiliado['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($afiliado['nombre_completo']) ?> - <?= htmlspecialchars($afiliado['documento']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Grado Evaluado <span class="text-danger">*</span></label>
                            <select class="form-select" name="grado" required>
                                <option value="">Selecciona el grado</option>
                                <?php foreach ($grados as $grado_item): ?>
                                <option value="<?= htmlspecialchars($grado_item['grado']) ?>" <?= (isset($_POST['grado']) && $_POST['grado'] == $grado_item['grado']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($grado_item['grado']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Franja <span class="text-danger">*</span></label>
                            <select class="form-select" name="franja" required>
                                <option value="">Selecciona una franja</option>
                                <?php foreach (['amarilla' => 'Amarilla', 'roja' => 'Roja', 'verde' => 'Verde', 'negra' => 'Negra', 'blanca' => 'Blanca', 'azul' => 'Azul'] as $valor => $texto): ?>
                                <option value="<?= $valor ?>" <?= (isset($_POST['franja']) && $_POST['franja'] == $valor) ? 'selected' : '' ?>>
                                    <?= $texto ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Resultado <span class="text-danger">*</span></label>
                            <select class="form-select" name="estado" required>
                                <option value="">Selecciona resultado</option>
                                <option value="aprobado" <?= (isset($_POST['estado']) && $_POST['estado'] == 'aprobado') ? 'selected' : '' ?>>
                                    <i class="bi bi-check-circle"></i> Aprobó
                                </option>
                                <option value="reprobado" <?= (isset($_POST['estado']) && $_POST['estado'] == 'reprobado') ? 'selected' : '' ?>>
                                    <i class="bi bi-x-circle"></i> Reprobó
                                </option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha" value="<?= isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Evaluador</label>
                            <input type="text" class="form-control" value="<?= $result_instructor ? htmlspecialchars($result_instructor['nombre_completo']) : 'No identificado' ?>" readonly>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="3" placeholder="Comentarios adicionales sobre la evaluación..."><?= isset($_POST['observaciones']) ? htmlspecialchars($_POST['observaciones']) : '' ?></textarea>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="d-grid d-md-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar Evaluación
                        </button>
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Limpiar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>