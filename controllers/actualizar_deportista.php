<?php
// /APP/controllers/actualizar_deportista.php

// Configuración de la base de datos
require_once '../config/database.php'; // Ajusta la ruta según tu estructura

// Verificar que se recibieron los datos
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// Validar datos requeridos
$id_deportista = $_POST['id_deportista'] ?? null;
$documento_original = $_POST['documento_original'] ?? null;

if (!$id_deportista || !$documento_original) {
    die("Error: Datos incompletos");
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Recoger datos del formulario
    $nombre = trim($_POST['nombre']);
    $sexo = $_POST['sexo'];
    $celular = trim($_POST['celular']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);
    $ciudad = $_POST['ciudad'];
    $eps = $_POST['eps'];
    $nombre_acudiente = trim($_POST['nombre_acudiente'] ?? '');
    $grado_cinturon = $_POST['grado_cinturon'];
    
    // Procesar horarios
    $horarios = isset($_POST['horarios']) ? implode(', ', $_POST['horarios']) : '';

    // Validaciones básicas
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Correo electrónico inválido");
    }
    
    if (!preg_match('/^\d{10}$/', $celular)) {
        throw new Exception("Celular debe tener 10 dígitos");
    }

    // Procesar foto si se subió una nueva
    $foto_nombre = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['foto']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            throw new Exception("Formato de imagen no permitido");
        }
        
        // Generar nombre único para la foto
        $foto_nombre = 'foto_' . $documento_original . '_' . time() . '.' . $ext;
        $upload_path = '../uploads/fotos/' . $foto_nombre;
        
        // Crear directorio si no existe
        if (!is_dir('../uploads/fotos/')) {
            mkdir('../uploads/fotos/', 0777, true);
        }
        
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
            throw new Exception("Error al subir la foto");
        }
    }

    // Preparar la consulta de actualización
    if ($foto_nombre) {
        $sql = "UPDATE afiliados_siao SET 
                nombre_completo = ?,
                sexo = ?,
                celular = ?,
                correo = ?,
                direccion = ?,
                ciudad = ?,
                eps = ?,
                nombre_acudiente = ?,
                horario = ?,
                grado_cinturon = ?,
                foto_nombre = ?,
                fecha_actualizacion = CURRENT_TIMESTAMP,
                estado_pago = 'pendiente'
                WHERE id = ? AND documento = ?";
        
        $params = [
            $nombre, $sexo, $celular, $correo, $direccion, $ciudad, $eps,
            $nombre_acudiente, $horarios, $grado_cinturon, $foto_nombre,
            $id_deportista, $documento_original
        ];
    } else {
        // Sin actualizar foto
        $sql = "UPDATE afiliados_siao SET 
                nombre_completo = ?,
                sexo = ?,
                celular = ?,
                correo = ?,
                direccion = ?,
                ciudad = ?,
                eps = ?,
                nombre_acudiente = ?,
                horario = ?,
                grado_cinturon = ?,
                fecha_actualizacion = CURRENT_TIMESTAMP,
                estado_pago = 'pendiente'
                WHERE id = ? AND documento = ?";
        
        $params = [
            $nombre, $sexo, $celular, $correo, $direccion, $ciudad, $eps,
            $nombre_acudiente, $horarios, $grado_cinturon,
            $id_deportista, $documento_original
        ];
    }

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);

    if (!$result) {
        throw new Exception("Error al actualizar los datos");
    }

    // Confirmar transacción
    $pdo->commit();

    // Redirigir con mensaje de éxito
    header('Location: ../exito_renovacion.php?nombre=' . urlencode($nombre));
    exit;

} catch (Exception $e) {
    // Revertir transacción en caso de error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("Error en actualizar_deportista.php: " . $e->getMessage());
    die("Error al actualizar: " . $e->getMessage());
}
?>