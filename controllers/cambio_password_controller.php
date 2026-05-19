<?php
session_start();
require_once '../helpers/db.php';
require_once '../helpers/auth.php';

verificarSesion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current_password = $_POST['current_password'];
    $new_password     = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $usuario_id       = $_SESSION['usuario_id'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        header("Location: ../views/auth/cambio_password.php?error=Todos los campos son obligatorios");
        exit;
    }

    if ($new_password !== $confirm_password) {
        header("Location: ../views/auth/cambio_password.php?error=Las nuevas contraseñas no coinciden");
        exit;
    }

    if (strlen($new_password) < 6) {
        header("Location: ../views/auth/cambio_password.php?error=La nueva contraseña debe tener al menos 6 caracteres");
        exit;
    }

    $conn = conectarDB();
    
    // Obtener contraseña actual del usuario
    $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    if (!$usuario || !password_verify($current_password, $usuario['password'])) {
        header("Location: ../views/auth/cambio_password.php?error=La contraseña actual es incorrecta");
        exit;
    }

    // Hash de la nueva contraseña y actualización
    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $update_stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_password_hashed, $usuario_id);

    if ($update_stmt->execute()) {
        header("Location: ../views/auth/cambio_password.php?success=1");
    } else {
        header("Location: ../views/auth/cambio_password.php?error=Error al actualizar la contraseña");
    }
    
    $stmt->close();
    $update_stmt->close();
    $conn->close();
    exit;
} else {
    header("Location: ../dashboard.php");
    exit;
}
