<?php
require_once '../helpers/db.php';

// Configuración para desarrollo (quitar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Validar si es admin para redireccionar diferente
session_start();
$es_admin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Validar y capturar datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $celular = trim($_POST['celular'] ?? '');
    $correo = filter_var($_POST['correo'] ?? '', FILTER_SANITIZE_EMAIL);
    $direccion = trim($_POST['direccion'] ?? '');
    $ciudad = $_POST['ciudad'] ?? '';
    $eps = $_POST['eps'] ?? '';
    $tipo_sangre = $_POST['tipo_sangre'] ?? '';
    $nombre_acudiente = trim($_POST['nombre_acudiente'] ?? '');
    $grado_cinturon = $_POST['grado_cinturon'] ?? '';
    $fecha_inscripcion = $_POST['fecha_inscripcion'] ?? '';
    $horarios = $_POST['horarios'] ?? [];
    
    // Convertir array de horarios a string separado por punto y coma (como en actualizar_afiliado.php)
    $horarios_str = implode(';', $horarios);

    // 2. Validaciones básicas
    $errores = [];
    if (empty($nombre)) $errores[] = "El nombre es requerido";
    if (empty($documento)) $errores[] = "El documento es requerido";
    if (empty($fecha_nacimiento)) $errores[] = "La fecha de nacimiento es requerida";
    if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido";
    }

    // Si hay errores, redireccionar
    if (!empty($errores)) {
        $url_redireccion = $es_admin ? '../views/afiliados/crear.php' : '../matricula_public.php';
        header("Location: $url_redireccion?error=" . urlencode(implode(', ', $errores)));
        exit;
    }

    // 3. Procesar la foto
    $foto_nombre = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Validar tipo de archivo
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        $tipo_archivo = $_FILES['foto']['type'];
        
        if (in_array($tipo_archivo, $permitidos)) {
            $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto_nombre = time() . '_' . uniqid() . '.' . $extension;
            $destino = "../assets/uploads/fotos/" . $foto_nombre;
            
            // Crear directorio si no existe
            if (!is_dir("../assets/uploads/fotos/")) {
                mkdir("../assets/uploads/fotos/", 0755, true);
            }
            
            if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $destino)) {
                $foto_nombre = ""; // Si falla la subida, continuar sin foto
            }
        }
    }

    // 4. Conectar a la base de datos
    $conn = conectarDB();

    // 5. Verificar duplicados
    $verificar = $conn->prepare("SELECT id FROM afiliados_siao WHERE documento=?");
    $verificar->bind_param("s", $documento);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        $url_redireccion = $es_admin ? '../views/afiliados/crear.php' : '../matricula_public.php';
        header("Location: $url_redireccion?error=" . urlencode("Ya existe un afiliado con este documento"));
        exit;
    }
    $verificar->close();

    // 6. Insertar el nuevo registro
    $query = "INSERT INTO afiliados_siao 
        (nombre_completo, documento, fecha_nacimiento, sexo, celular, correo, 
         direccion, ciudad, eps, tipo_sangre, nombre_acudiente, grado_cinturon, 
         fecha_inscripcion, horario, foto_nombre, rol, activo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'afiliado', 1)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssssssss",
        $nombre,
        $documento,
        $fecha_nacimiento,
        $sexo,
        $celular,
        $correo,
        $direccion,
        $ciudad,
        $eps,
        $tipo_sangre,
        $nombre_acudiente,
        $grado_cinturon,
        $fecha_inscripcion,
        $horarios_str,
        $foto_nombre
    );

    // 7. Ejecutar y redireccionar según el tipo de usuario
    if ($stmt->execute()) {
        if ($es_admin) {
            header("Location: ../views/afiliados/index.php?msg=creado");
        } else {
            header("Location: ../views/afiliados/gracias_registro.php?success=1");
        }
        exit;
    } else {
        $mensaje_error = "Error al guardar: " . $conn->error;
        $url_redireccion = $es_admin ? '../views/afiliados/crear.php' : '../matricula_public.php';
        header("Location: $url_redireccion?error=" . urlencode($mensaje_error));
        exit;
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Si no es POST, redireccionar según el tipo de usuario
$url_redireccion = $es_admin ? '../views/afiliados/crear.php' : '../matricula_public.php';
header("Location: $url_redireccion");
exit;
?>