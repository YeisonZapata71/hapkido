<?php
require_once '../../helpers/auth.php';
require_once '../../helpers/db.php';

// Verificar que sea una petición POST y esté autenticado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

verificarSesion();

header('Content-Type: application/json');

try {
    $horario_id = filter_var($_POST['horario_id'] ?? null, FILTER_VALIDATE_INT);
    
    if (!$horario_id) {
        echo json_encode(['success' => false, 'message' => 'ID de horario no válido']);
        exit;
    }
    
    $conn = conectarDB();
    
    // Obtener el nombre del horario
    $stmt = $conn->prepare("SELECT nombre FROM horarios WHERE id = ? AND activo = 1");
    $stmt->bind_param('i', $horario_id);
    $stmt->execute();
    $horario_result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$horario_result) {
        echo json_encode(['success' => false, 'message' => 'Horario no encontrado']);
        exit;
    }
    
    $nombre_horario = $horario_result['nombre'];
    
    // Obtener afiliados que corresponden al horario
    $stmt = $conn->prepare("
        SELECT DISTINCT a.id, a.nombre_completo, a.documento, a.horario
        FROM afiliados_siao a 
        WHERE a.rol != 'instructor' 
        AND a.horario LIKE CONCAT('%', ?, '%')
        AND a.activo = 1
        ORDER BY a.nombre_completo ASC
    ");
    $stmt->bind_param('s', $nombre_horario);
    $stmt->execute();
    $afiliados = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    
    echo json_encode([
        'success' => true, 
        'afiliados' => $afiliados,
        'total' => count($afiliados)
    ]);
    
} catch (Exception $e) {
    error_log("Error en cargar_afiliados.php: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Error interno del servidor'
    ]);
}
?>