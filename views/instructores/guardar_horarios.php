<?php
require_once '../../helpers/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../../helpers/db.php';
$conn = conectarDB();

$id = intval($_POST['instructor_id']);
$horarios = $_POST['horarios'] ?? [];

if ($id <= 0) die("ID inválido.");
// Validar que instructor_id existe
if (!isset($_POST['instructor_id'])) {
    die("Error: No se recibió el ID del instructor");
}

$id = intval($_POST['instructor_id']);

// Elimina horarios actuales del instructor
$conn->query("DELETE FROM instructor_horario WHERE instructor_id = $id");

// Asigna los nuevos
if (!empty($horarios)) {
    $stmt = $conn->prepare("INSERT INTO instructor_horario (instructor_id, horario_id) VALUES (?, ?)");
    foreach ($horarios as $hid) {
        $stmt->bind_param("ii", $id, $hid);
        $stmt->execute();
    }
    $stmt->close();
}
header("Location: index.php?msg=Horarios actualizados");
exit;
?>
