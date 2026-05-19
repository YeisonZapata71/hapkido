<?php
require_once '../helpers/auth.php';
require_once '../helpers/db.php';

verificarSesion();
verificarRol('admin');

header('Content-Type: application/json');

try {
    $conn = conectarDB();
    
    // Obtener parámetros de filtro
    $categoria = $_GET['categoria'] ?? 'todos';
    $estado_pago = $_GET['estado_pago'] ?? '';
    $horario = $_GET['horario'] ?? '';
    $grado_cinturon = $_GET['grado_cinturon'] ?? '';
    $busqueda = $_GET['busqueda'] ?? '';
    
    // Año actual para nuevas inscripciones
    $anio_actual = date('Y');
    
    // Fecha hace 30 días para actualizaciones recientes
    $fecha_30_dias = date('Y-m-d', strtotime('-30 days'));
    
    // Fecha hace 2 meses para sin actualizar
    $fecha_2_meses = date('Y-m-d', strtotime('-2 months'));
    
    // ===== ESTADÍSTICAS GENERALES =====
    
    // Total de afiliados activos
    $sql_total = "SELECT COUNT(*) as total FROM afiliados_siao WHERE activo = 1";
    $result_total = $conn->query($sql_total);
    $total_afiliados = $result_total->fetch_assoc()['total'];
    
    // Nuevas inscripciones (año actual)
    $sql_nuevos = "SELECT COUNT(*) as total FROM afiliados_siao 
                   WHERE activo = 1 AND YEAR(fecha_inscripcion) = $anio_actual";
    $result_nuevos = $conn->query($sql_nuevos);
    $total_nuevos = $result_nuevos->fetch_assoc()['total'];
    
    // Actualizaciones recientes (últimos 30 días)
    $sql_actualizados = "SELECT COUNT(*) as total FROM afiliados_siao 
                         WHERE activo = 1 AND fecha_actualizacion >= '$fecha_30_dias'";
    $result_actualizados = $conn->query($sql_actualizados);
    $total_actualizados = $result_actualizados->fetch_assoc()['total'];
    
    // Sin actualizar (más de 2 meses)
    $sql_sin_actualizar = "SELECT COUNT(*) as total FROM afiliados_siao 
                           WHERE activo = 1 AND fecha_actualizacion < '$fecha_2_meses'";
    $result_sin_actualizar = $conn->query($sql_sin_actualizar);
    $total_sin_actualizar = $result_sin_actualizar->fetch_assoc()['total'];
    
    // ===== CONSULTA DE DATOS FILTRADOS =====
    
    $where_conditions = ["activo = 1"];
    $params = [];
    $types = "";
    
    // Filtro por categoría
    switch ($categoria) {
        case 'nuevos':
            $where_conditions[] = "YEAR(fecha_inscripcion) = ?";
            $params[] = $anio_actual;
            $types .= "i";
            break;
        case 'actualizados':
            $where_conditions[] = "fecha_actualizacion >= ?";
            $params[] = $fecha_30_dias;
            $types .= "s";
            break;
        case 'sin_actualizar':
            $where_conditions[] = "fecha_actualizacion < ?";
            $params[] = $fecha_2_meses;
            $types .= "s";
            break;
    }
    
    // Filtro por estado de pago
    if (!empty($estado_pago)) {
        $where_conditions[] = "estado_pago = ?";
        $params[] = $estado_pago;
        $types .= "s";
    }
    
    // Filtro por horario
    if (!empty($horario)) {
        $where_conditions[] = "horario LIKE ?";
        $params[] = "%$horario%";
        $types .= "s";
    }
    
    // Filtro por grado de cinturón
    if (!empty($grado_cinturon)) {
        $where_conditions[] = "grado_cinturon = ?";
        $params[] = $grado_cinturon;
        $types .= "s";
    }
    
    // Búsqueda general
    if (!empty($busqueda)) {
        $where_conditions[] = "(nombre_completo LIKE ? OR documento LIKE ? OR correo LIKE ? OR celular LIKE ?)";
        $busqueda_param = "%$busqueda%";
        $params[] = $busqueda_param;
        $params[] = $busqueda_param;
        $params[] = $busqueda_param;
        $params[] = $busqueda_param;
        $types .= "ssss";
    }
    
    $where_clause = implode(" AND ", $where_conditions);
    
    $sql = "SELECT id, nombre_completo, documento, horario, celular, correo, 
            foto_nombre, estado_pago, grado_cinturon, fecha_inscripcion, 
            fecha_actualizacion, fecha_creacion
            FROM afiliados_siao 
            WHERE $where_clause 
            ORDER BY id DESC";
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $afiliados = [];
    while ($row = $result->fetch_assoc()) {
        // Determinar si es nuevo (inscrito este año)
        $es_nuevo = (date('Y', strtotime($row['fecha_inscripcion'])) == $anio_actual);
        
        // Determinar si necesita actualización (más de 2 meses sin actualizar)
        $necesita_actualizacion = (strtotime($row['fecha_actualizacion']) < strtotime($fecha_2_meses));
        
        $row['es_nuevo'] = $es_nuevo;
        $row['necesita_actualizacion'] = $necesita_actualizacion;
        
        $afiliados[] = $row;
    }
    
    // Respuesta JSON
    echo json_encode([
        'success' => true,
        'estadisticas' => [
            'total' => $total_afiliados,
            'nuevos' => $total_nuevos,
            'actualizados' => $total_actualizados,
            'sin_actualizar' => $total_sin_actualizar
        ],
        'afiliados' => $afiliados,
        'total_resultados' => count($afiliados)
    ]);
    
    $conn->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
