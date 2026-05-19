<?php
// controllers/restablecer_password_controller.php
session_start();
require_once __DIR__ . '/../helpers/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$token     = trim($_POST['token'] ?? '');
$password1 = $_POST['password'] ?? '';
$password2 = $_POST['password_confirm'] ?? '';

// Validaciones básicas
if (empty($token)) {
    $_SESSION['reset_form_error'] = 'Token inválido.';
    header("Location: ../views/auth/restablecer_password.php?token=" . urlencode($token));
    exit;
}

if (strlen($password1) < 8) {
    $_SESSION['reset_form_error'] = 'La contraseña debe tener al menos 8 caracteres.';
    header("Location: ../views/auth/restablecer_password.php?token=" . urlencode($token));
    exit;
}

if ($password1 !== $password2) {
    $_SESSION['reset_form_error'] = 'Las contraseñas no coinciden.';
    header("Location: ../views/auth/restablecer_password.php?token=" . urlencode($token));
    exit;
}

$conn = conectarDB();

// Buscar el token y verificar que no haya expirado
$stmt = $conn->prepare("
    SELECT pr.id, pr.usuario_id, pr.expira_en
    FROM password_resets pr
    WHERE pr.token = ?
    LIMIT 1
");
$stmt->bind_param('s', $token);
$stmt->execute();
$reset = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$reset) {
    $conn->close();
    header("Location: ../views/auth/restablecer_password.php?token=" . urlencode($token) . "&error=token_invalido");
    exit;
}

if (strtotime($reset['expira_en']) < time()) {
    // Token expirado — eliminarlo
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE id = ?");
    $stmt->bind_param('i', $reset['id']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: ../views/auth/restablecer_password.php?token=" . urlencode($token) . "&error=token_expirado");
    exit;
}

// Actualizar contraseña con hash seguro
$nuevo_hash = password_hash($password1, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
$stmt->bind_param('si', $nuevo_hash, $reset['usuario_id']);
$stmt->execute();
$stmt->close();

// Eliminar token usado (de un solo uso)
$stmt = $conn->prepare("DELETE FROM password_resets WHERE id = ?");
$stmt->bind_param('i', $reset['id']);
$stmt->execute();
$stmt->close();

$conn->close();

// Redirigir al login con mensaje de éxito
$_SESSION['login_success'] = 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.';
header('Location: ../login.php?exito=1');
exit;
?>
