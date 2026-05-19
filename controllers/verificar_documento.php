<?php
// /APP/controllers/verificar_documento.php

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en JSON

// ====== CONFIGURACIÓN DE BASE DE DATOS ======
$host = 'localhost';
$dbname = 'siao_formulario';$username = 'siao_siao';  
$password = 'Sicau2025**';  

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Error de conexión en verificar_documento.php: " . $e->getMessage());
    echo json_encode([
        'error' => 'Error de conexión a la base de datos',
        'existe' => false,
        'debug' => $e->getMessage()
    ]);
    exit;
}

// Validar que se recibió el documento
if (!isset($_POST['documento']) || empty(trim($_POST['documento']))) {
    echo json_encode([
        'error' => 'Documento no proporcionado',
        'existe' => false
    ]);
    exit;
}

$documento = trim($_POST['documento']);

// Validar formato del documento (solo números, mínimo 6 dígitos)
if (!preg_match('/^\d{6,}$/', $documento)) {
    echo json_encode([
        'error' => 'Documento inválido',
        'existe' => false
    ]);
    exit;
}

try {
    // Consultar si el documento existe en la base de datos
    // Primero verificamos qué columnas existen
    $stmt = $pdo->prepare("SELECT * FROM afiliados_siao WHERE documento = ? LIMIT 1");
    $stmt->execute([$documento]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        // El documento existe
        $response = [
            'existe' => true,
            'mensaje' => 'Deportista encontrado',
            'nombre' => $resultado['nombre_completo'] ?? 'Deportista'
        ];
        
        // Agregar campo activo si existe en la tabla
        if (isset($resultado['activo'])) {
            $response['activo'] = (bool)$resultado['activo'];
        }
        
        echo json_encode($response);
    } else {
        // El documento NO existe
        echo json_encode([
            'existe' => false,
            'mensaje' => 'Documento no registrado'
        ]);
    }

} catch (PDOException $e) {
    error_log("Error en consulta verificar_documento.php: " . $e->getMessage());
    echo json_encode([
        'error' => 'Error al consultar la base de datos',
        'existe' => false,
        'debug' => $e->getMessage()
    ]);
}
?>