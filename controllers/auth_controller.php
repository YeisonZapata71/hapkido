<?php
session_start();
require_once '../helpers/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $correo   = trim($_POST['correo']);
  $clave    = trim($_POST['password']);

  $conn = conectarDB();
  $stmt = $conn->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE correo = ?");
  $stmt->bind_param("s", $correo);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($clave, $usuario['password'])) {
      $_SESSION['usuario_id'] = $usuario['id'];
      $_SESSION['nombre']     = $usuario['nombre'];
      $_SESSION['rol']        = $usuario['rol'];

      header("Location: ../dashboard.php");
      exit;
    }
  }

  header("Location: ../login.php?error=Credenciales inválidas");
}
