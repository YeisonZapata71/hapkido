<?php
session_start();

function verificarSesion() {
  if (!isset($_SESSION['usuario_id'])) {
    header("Location: /app/login.php");
    exit;
  }
}

function verificarRol($rolRequerido) {
  // Normalizar a array
  $rolesPermitidos = is_array($rolRequerido) ? $rolRequerido : [$rolRequerido];

  if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    echo "<script>alert('Acceso denegado'); window.location.href = '../dashboard.php';</script>";
    exit;
  }
}