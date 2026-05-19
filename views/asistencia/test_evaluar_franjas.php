<?php
// Versión de prueba para detectar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "1. Iniciando script...<br>";

try {
    echo "2. Incluyendo archivos...<br>";
    require_once '../../helpers/db.php';
    echo "   - db.php incluido correctamente<br>";
    
    require_once '../../helpers/auth.php';
    echo "   - auth.php incluido correctamente<br>";
    
    echo "3. Verificando sesión...<br>";
    verificarSesion();
    echo "   - Sesión verificada<br>";
    
    echo "4. Verificando rol...<br>";
    verificarRol('admin');
    echo "   - Rol verificado<br>";
    
    echo "5. Conectando a BD...<br>";
    $conn = conectarDB();
    echo "   - Conexión exitosa<br>";
    
    echo "6. Obteniendo usuario...<br>";
    $usuario_id = $_SESSION['usuario_id'];
    echo "   - Usuario ID: " . $usuario_id . "<br>";
    
    echo "7. Buscando instructor...<br>";
    $stmt = $conn->prepare("SELECT id, nombre_completo FROM afiliados_siao WHERE usuario_id = ? AND rol = 'instructor'");
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result_instructor = $stmt->get_result()->fetch_assoc();
    
    if ($result_instructor) {
        echo "   - Instructor encontrado: " . $result_instructor['nombre_completo'] . "<br>";
        $instructor_id = $result_instructor['id'];
    } else {
        echo "   - ⚠️ No se encontró instructor para este usuario<br>";
        $instructor_id = null;
    }
    $stmt->close();
    
    echo "8. Obteniendo afiliados...<br>";
    $result = $conn->query("SELECT id, nombre_completo, documento FROM afiliados_siao WHERE rol != 'instructor' AND activo = 1 ORDER BY nombre_completo LIMIT 5");
    $afiliados = $result->fetch_all(MYSQLI_ASSOC);
    echo "   - Afiliados encontrados: " . count($afiliados) . "<br>";
    
    echo "9. Obteniendo grados...<br>";
    $result = $conn->query("SELECT DISTINCT grado FROM afiliados_siao WHERE grado IS NOT NULL AND grado != '' ORDER BY grado LIMIT 10");
    $grados = $result->fetch_all(MYSQLI_ASSOC);
    echo "   - Grados encontrados: " . count($grados) . "<br>";
    
    $conn->close();
    echo "10. ✅ Todas las validaciones pasaron correctamente<br>";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . "<br>";
    echo "Línea: " . $e->getLine() . "<br>";
    echo "Trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h3>Información de sesión:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>