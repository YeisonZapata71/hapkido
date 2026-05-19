<?php
require_once '../../helpers/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../../helpers/db.php';
require_once '../../controllers/instructores_controller.php';

$conn = conectarDB();

$id = intval($_POST['id'] ?? 0);
$accion = $_POST['accion'] ?? '';

if ($id <= 0) {
    echo "ID inválido: $id";
    exit;
}

if ($accion == 'quitar') {
    // 1. Eliminar horarios asignados al instructor
    $conn->query("DELETE FROM instructor_horario WHERE instructor_id = $id");

    // 2. Actualizar rol en afiliados_siao
    $stmt = $conn->prepare("UPDATE afiliados_siao SET rol = 'afiliado' WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    // 3. SINCRONIZAR: Actualizar rol en tabla usuarios (si el afiliado tiene usuario vinculado)
    $stmt2 = $conn->prepare("SELECT usuario_id FROM afiliados_siao WHERE id = ?");
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $res2 = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();

    if (!empty($res2['usuario_id'])) {
        $uid = $res2['usuario_id'];
        $stmt3 = $conn->prepare("UPDATE usuarios SET rol = 'afiliado' WHERE id = ?");
        $stmt3->bind_param('i', $uid);
        $stmt3->execute();
        $stmt3->close();
    }

    header("Location: asignar_rol.php?msg=rol_quitado");
    exit;

} elseif ($accion == 'asignar') {
    // 1. Actualizar rol en afiliados_siao
    $stmt = $conn->prepare("UPDATE afiliados_siao SET rol = 'instructor' WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    // 2. SINCRONIZAR: Actualizar rol en tabla usuarios (si el afiliado tiene usuario vinculado)
    $stmt2 = $conn->prepare("SELECT usuario_id FROM afiliados_siao WHERE id = ?");
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $res2 = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();

    if (!empty($res2['usuario_id'])) {
        $uid = $res2['usuario_id'];
        $stmt3 = $conn->prepare("UPDATE usuarios SET rol = 'instructor' WHERE id = ?");
        $stmt3->bind_param('i', $uid);
        $stmt3->execute();
        $stmt3->close();
    }

    header("Location: asignar_rol.php?msg=rol_asignado");
    exit;

} else {
    echo "Acción inválida";
}
$conn->close();
?>