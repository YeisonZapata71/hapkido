<?php
require_once __DIR__ . '/../models/AsistenciaModel.php';
require_once __DIR__ . '/../helpers/auth.php';

// Verificar sesión y rol
verificarSesion();
verificarRol(['admin', 'instructor']); // Asegurar que tenga rol válido

$usuario_id = $_SESSION['usuario_id'];
$rol_usuario = $_SESSION['rol'];
$model = new AsistenciaModel();
$es_admin = ($rol_usuario === 'admin');

// --- Carga horarios para el usuario actual ---
try {
    $horarios = $model->obtenerHorariosPorUsuario($usuario_id, $es_admin);
} catch (Exception $e) {
    error_log("Error obteniendo horarios: " . $e->getMessage());
    die("Error al cargar los horarios. Por favor intente más tarde.");
}

// --- Guardar asistencia ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación básica de parámetros requeridos
    if (!isset($_POST['fecha'], $_POST['horario_id'], $_POST['asistencia'])) {
        header("Location: asistencia_controller.php?error=Parametros+requeridos+faltantes");
        exit;
    }

    // Sanitización y validación de datos
    $fecha = filter_var($_POST['fecha'], FILTER_SANITIZE_STRING);
    if (!DateTime::createFromFormat('Y-m-d', $fecha)) {
        header("Location: asistencia_controller.php?error=Fecha+no+valida");
        exit;
    }

    // Verificación estricta de fecha (Máximo 2 días atrás)
    $fecha_obj = new DateTime($fecha);
    $hoy_obj = new DateTime();
    $hoy_obj->setTime(0,0,0); // Normalizar a medianoche
    $fecha_obj->setTime(0,0,0);
    
    $diferencia = $hoy_obj->diff($fecha_obj);
    $dias_diferencia = (int)$diferencia->format('%r%a'); // %r signo, %a días absolutos

    // Si es futuro ($dias_diferencia > 0) o más de 2 días atrás ($dias_diferencia < -2)
    // PERMITIR EXCEPCIÓN: Si es ADMIN, dejamos pasar cualquier fecha.
    if (!$es_admin && ($dias_diferencia > 0 || $dias_diferencia < -2)) {
        header("Location: asistencia_controller.php?error=Fecha+no+permitida+(Max+2+dias+atras)");
        exit;
    }

    $horario_id = intval($_POST['horario_id']);

    // Obtener el ID real del instructor (id en tabla afiliados) asociado al usuario
    $instructor_afiliado_id = $model->obtenerIdInstructorPorUsuario($usuario_id);
    
    // Si no tiene perfil y es admin, intentamos usar el instructor ASIGNADO al horario
    if (!$instructor_afiliado_id && $es_admin) {
        $instructor_afiliado_id = $model->obtenerInstructorIdDeHorario($horario_id);
    }

    // Si sigue sin encontrar un ID válido (ni del usuario ni del horario), error fatal.
    if (!$instructor_afiliado_id) {
        error_log("Error: No se encontró un ID de instructor válido para la asistencia (Usuario: $usuario_id, Horario: $horario_id)");
        $err_msg = $es_admin 
            ? "Error: Como admin, necesitas un perfil de instructor O el horario debe tener uno asignado." 
            : "Perfil de instructor no encontrado.";
        header("Location: asistencia_controller.php?error=" . urlencode($err_msg));
        exit;
    }
    
    $instructor_id = $instructor_afiliado_id;


    // Verificar que el horario pertenece al instructor (si no es admin)
    if (!$es_admin) {
        $horario_permitido = array_filter($horarios, function($h) use ($horario_id) {
            return $h['id'] == $horario_id;
        });
        
        if (empty($horario_permitido)) {
            header("Location: asistencia_controller.php?error=Horario+no+permitido");
            exit;
        }
    }

    // Procesar datos
    $asistencias_post = is_array($_POST['asistencia'] ?? []) ? $_POST['asistencia'] : []; // IDs de los marcados (checkbox checked)
    $franjas = is_array($_POST['franjas'] ?? []) ? $_POST['franjas'] : [];
    $observaciones = is_array($_POST['observaciones'] ?? []) ? $_POST['observaciones'] : [];

    // IMPORTANTE: Necesitamos la lista completa de deportistas para saber quién faltó
    // Los checkboxes html solo envían los marcados ("presentes"). Los no marcados no llegan.
    try {
        $roster = $model->obtenerDeportistasPorHorario($horario_id);
    } catch (Exception $e) {
        error_log("Error obteniendo roster para guardar: " . $e->getMessage());
        header("Location: asistencia_controller.php?error=Error+al+procesar+lista+de+estudiantes");
        exit;
    }

    $mapa_asistencia = [];
    $count_presentes = 0;

    foreach ($roster as $estudiante) {
        $id = $estudiante['id'];
        // Si el ID está en el array de checkboxes enviados, está presente. Si no, ausente.
        $esta_presente = in_array($id, $asistencias_post);
        $mapa_asistencia[$id] = $esta_presente;
        
        if ($esta_presente) $count_presentes++;
    }

    try {
        // Enviamos el mapa [id => bool] al modelo
        $model->guardarAsistencias($fecha, $horario_id, $instructor_id, $mapa_asistencia, $franjas, $observaciones);
        
        // Redirigir con mensaje de éxito
        $msg = urlencode("Asistencia guardada: " . $count_presentes . " presentes.");
        header("Location: asistencia_controller.php?fecha=$fecha&horario_id=$horario_id&msg=$msg");
        exit;
    } catch (Exception $e) {
        error_log("Error guardando asistencia: " . $e->getMessage());
        header("Location: asistencia_controller.php?error=Error+sql:+" . urlencode($e->getMessage()));
        exit;
    }
}

// --- Visualización del formulario y deportistas ---
$fecha = $_GET['fecha'] ?? date('Y-m-d');
$horario_id = isset($_GET['horario_id']) ? intval($_GET['horario_id']) : null;
$deportistas = [];

// Validar fecha
if (!DateTime::createFromFormat('Y-m-d', $fecha)) {
    $fecha = date('Y-m-d');
}

// Obtener deportistas solo si hay horarios y el horario es válido
if ($horario_id && !empty($horarios)) {
    // Verificar que el horario pertenece al instructor (si no es admin)
    if (!$es_admin) {
        $horario_permitido = array_filter($horarios, function($h) use ($horario_id) {
            return $h['id'] == $horario_id;
        });
        
        if (!empty($horario_permitido)) {
            try {
                $deportistas = $model->obtenerDeportistasPorHorario($horario_id);
            } catch (Exception $e) {
                error_log("Error obteniendo deportistas: " . $e->getMessage());
                $deportistas = [];
            }
        }
    } else {
        try {
            $deportistas = $model->obtenerDeportistasPorHorario($horario_id);
        } catch (Exception $e) {
            error_log("Error obteniendo deportistas: " . $e->getMessage());
            $deportistas = [];
        }
    }
}

// Pasar mensajes de error a la vista si existen
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;

// Incluir vista con todos los parámetros necesarios
include __DIR__ . '/../views/asistencia/tomar.php';