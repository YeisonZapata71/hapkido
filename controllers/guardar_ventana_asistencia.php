<?php
// controllers/guardar_ventana_asistencia.php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/db.php';

verificarSesion();
verificarRol('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/admin/ventana_asistencia.php');
    exit;
}

$accion  = $_POST['accion'] ?? '';
$conn    = conectarDB();

if ($accion === 'activar') {
    $desde  = trim($_POST['ventana_desde'] ?? '');
    $hasta  = trim($_POST['ventana_hasta'] ?? '');
    $motivo = trim($_POST['ventana_motivo'] ?? '');

    // Validaciones
    if (!$desde || !$hasta) {
        header('Location: ../views/admin/ventana_asistencia.php?error=fechas_requeridas');
        exit;
    }
    if ($desde > $hasta) {
        header('Location: ../views/admin/ventana_asistencia.php?error=rango_invalido');
        exit;
    }

    $updates = [
        'ventana_activa' => '1',
        'ventana_desde'  => $desde,
        'ventana_hasta'  => $hasta,
        'ventana_motivo' => $motivo,
    ];

    $stmt = $conn->prepare("INSERT INTO config_asistencia (clave, valor)
        VALUES (?, ?) ON DUPLICATE KEY UPDATE valor = VALUES(valor)");
    foreach ($updates as $clave => $valor) {
        $stmt->bind_param('ss', $clave, $valor);
        $stmt->execute();
    }
    $stmt->close();
    $conn->close();

    header('Location: ../views/admin/ventana_asistencia.php?ok=activada');
    exit;

} elseif ($accion === 'desactivar') {
    $stmt = $conn->prepare("INSERT INTO config_asistencia (clave, valor)
        VALUES ('ventana_activa', '0') ON DUPLICATE KEY UPDATE valor = '0'");
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header('Location: ../views/admin/ventana_asistencia.php?ok=desactivada');
    exit;
}

$conn->close();
header('Location: ../views/admin/ventana_asistencia.php');
exit;
?>
