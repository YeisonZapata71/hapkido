<?php
require_once '../../helpers/auth.php';
require_once '../../helpers/db.php';
verificarSesion();

// Verificar que se solicitó exportación
if (!isset($_GET['export']) || $_GET['export'] !== 'excel') {
    http_response_code(400);
    exit('Solicitud no válida');
}

$conn = conectarDB();
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

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

// Parámetros de filtros (los mismos que en historial_evaluaciones.php)
$filtro_afiliado = $_GET['afiliado'] ?? '';
$filtro_estado = $_GET['estado'] ?? '';
$filtro_color = $_GET['color'] ?? '';
$filtro_grado = $_GET['grado'] ?? '';
$filtro_fecha_desde = $_GET['fecha_desde'] ?? '';
$filtro_fecha_hasta = $_GET['fecha_hasta'] ?? '';

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

// Obtener todas las evaluaciones que coincidan con los filtros
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
";

$stmt = $conn->prepare($query);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$evaluaciones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

// Configurar headers para descarga de Excel
$filename = 'evaluaciones_franjas_' . date('Y-m-d_H-i-s') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Crear el archivo CSV
$output = fopen('php://output', 'w');

// Escribir BOM para UTF-8 (para Excel)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Encabezados de columnas
$headers = [
    'Fecha',
    'Afiliado',
    'Documento',
    'Grado Evaluado',
    'Franja',
    'Estado',
    'Evaluador',
    'Observaciones',
    'Fecha Creación'
];

fputcsv($output, $headers, ';');

// Escribir datos
foreach ($evaluaciones as $eval) {
    $row = [
        date('d/m/Y', strtotime($eval['fecha'])),
        $eval['afiliado_nombre'],
        $eval['afiliado_documento'],
        $eval['grado'] ?? '',
        ucfirst($eval['color']),
        ucfirst($eval['estado']),
        $eval['evaluador_nombre'] ?? '',
        $eval['observaciones'] ?? '',
        $eval['fecha_creacion'] ? date('d/m/Y H:i:s', strtotime($eval['fecha_creacion'])) : ''
    ];
    
    fputcsv($output, $row, ';');
}

fclose($output);
exit;
?>