<?php
function conectarDB() {
  $host     = 'localhost';
  $usuario  = 'siao_siao';         
  $clave    = 'Sicau2025**';    
  $bd       = 'siao_formulario';   

  $conexion = new mysqli($host, $usuario, $clave, $bd);

  if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
  }

  return $conexion;
}
