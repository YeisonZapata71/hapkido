<?php
error_reporting(E_ALL);
/**
 * Controlador de Exámenes
 * Gestión de exámenes de ascenso de grado
 */

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/db.php';
require_once __DIR__ . '/../models/ExamenesModel.php';

// Verificar autenticación y rol
verificarSesion();
verificarRol('admin');

$conn = conectarDB();
$examenes_model = new ExamenesModel($conn);

// Determinar la acción
$accion = $_GET['accion'] ?? $_POST['accion'] ?? null;
$view = $_GET['view'] ?? 'lista';

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion) {
    switch ($accion) {
        case 'programar':
            $resultado = $examenes_model->programarExamen(
                $_POST['afiliado_id'],
                $_POST['grado_actual'],
                $_POST['grado_siguiente'],
                $_POST['fecha_examen'],
                $_POST['observaciones'] ?? null
            );
            
            if ($resultado['success']) {
                header('Location: examenes_controller.php?view=lista&msg=' . urlencode($resultado['message']));
            } else {
                header('Location: examenes_controller.php?view=lista&error=' . urlencode($resultado['message']));
            }
            exit;
            
        case 'calificar':
            $resultado = $examenes_model->calificarExamen(
                $_POST['id'],
                $_POST['aprobado'],
                $_POST['observaciones'] ?? null
            );
            
            if ($resultado['success']) {
                header('Location: examenes_controller.php?view=lista&msg=' . urlencode($resultado['message']));
            } else {
                header('Location: examenes_controller.php?view=lista&error=' . urlencode($resultado['message']));
            }
            exit;
            
        case 'editar':
            $datos = [
                'fecha_examen' => $_POST['fecha_examen'],
                'observaciones' => $_POST['observaciones'] ?? null
            ];
            
            $resultado = $examenes_model->actualizarExamen($_POST['id'], $datos);
            
            if ($resultado['success']) {
                header('Location: examenes_controller.php?view=lista&msg=' . urlencode($resultado['message']));
            } else {
                header('Location: examenes_controller.php?view=lista&error=' . urlencode($resultado['message']));
            }
            exit;
    }
}

// Preparar datos para las vistas
switch ($view) {
    case 'lista':
        // Obtener todos los exámenes
        $todos_examenes = $examenes_model->obtenerExamenes(200);
        
        // Separar por categorías
        $proximos_examenes = $examenes_model->obtenerProximosExamenes(30);
        $pendientes_calificacion = $examenes_model->obtenerExamenesPendientesCalificacion();
        
        // Obtener estadísticas
        $estadisticas = $examenes_model->obtenerEstadisticas();
        
        // Obtener deportistas para el selector - Con manejo de error
        $res_dep = $conn->query("SELECT id, nombre_completo, documento, grado_cinturon, horario FROM afiliados_siao WHERE activo = 1 ORDER BY nombre_completo");
        $deportistas = $res_dep ? $res_dep->fetch_all(MYSQLI_ASSOC) : [];
        
        // Grados disponibles
        $grados = [
            'Blanco', 'Amarillo', 'Naranja', 'Verde', 'Azul', 'Púrpura',
            'Rojo', 'Rojo-Marrón', 'Marrón', 'Marrón-Negro',
            'Negro 1 Dan', 'Negro 2 Dan', 'Negro 3 Dan', 
            'Negro 4 Dan', 'Negro 5 Dan', 'Negro 6 Dan'
        ];
        
        include __DIR__ . '/../views/examenes/lista.php';
        break;
        
    case 'detalle':
        $id = $_GET['id'] ?? 0;
        $examen = $examenes_model->obtenerExamen($id);
        
        if (!$examen) {
            header('Location: examenes_controller.php?view=lista&error=' . urlencode('Examen no encontrado'));
            exit;
        }
        
        // Obtener historial del deportista
        $historial = $examenes_model->obtenerExamenesPorDeportista($examen['afiliado_id']);
        
        include __DIR__ . '/../views/examenes/detalle.php';
        break;
        
    default:
        header('Location: examenes_controller.php?view=lista');
        exit;
}
?>
