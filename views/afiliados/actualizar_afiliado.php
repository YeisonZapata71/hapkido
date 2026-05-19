<?php
require_once '../helpers/db.php';
require_once '../helpers/auth.php';
verificarSesion();
verificarRol('admin');

$conn = conectarDB();

$id = $_POST['id'];
$nombre_completo = $_POST['nombre_completo'];
$documento = $_POST['documento'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$sexo = $_POST['sexo'];
$celular = $_POST['celular'];
$correo = $_POST['correo'];
$direccion = $_POST['direccion'];
$ciudad = $_POST['ciudad'];
$eps = $_POST['eps'];
$tipo_sangre = $_POST['tipo_sangre'];
$nombre_acudiente = $_POST['nombre_acudiente'];
$grado_cinturon = $_POST['grado_cinturon'];
$fecha_inscripcion = $_POST['fecha_inscripcion'];
$horario = $_POST['horario'];

// ¿Se cargó una nueva foto?
if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
    $foto_nombre = time() . '_' . basename($_FILES['foto']['name']);
    $destino = "../assets/uploads/fotos/" . $foto_nombre;
    move_uploaded_file($_FILES["foto"]["tmp_name"], $destino);

    $stmt = $conn->prepare("UPDATE afiliados_siao SET nombre_completo=?, documento=?, fecha_nacimiento=?, sexo=?, celular=?, correo=?, direccion=?, ciudad=?, eps=?, tipo_sangre=?, nombre_acudiente=?, grado_cinturon=?, fecha_inscripcion=?, horario=?, foto_nombre=? WHERE id=?");
    $stmt->bind_param("sssssssssssssssi", $nombre_completo, $documento, $fecha_nacimiento, $sexo, $celular, $correo, $direccion, $ciudad, $eps, $tipo_sangre, $nombre_acudiente, $grado_cinturon, $fecha_inscripcion, $horario, $foto_nombre, $id);
} else {
    $stmt = $conn->prepare("UPDATE afiliados_siao SET nombre_completo=?, documento=?, fecha_nacimiento=?, sexo=?, celular=?, correo=?, direccion=?, ciudad=?, eps=?, tipo_sangre=?, nombre_acudiente=?, grado_cinturon=?, fecha_inscripcion=?, horario=? WHERE id=?");
    $stmt->bind_param("ssssssssssssssi", $nombre_completo, $documento, $fecha_nacimiento, $sexo, $celular, $correo, $direccion, $ciudad, $eps, $tipo_sangre, $nombre_acudiente, $grado_cinturon, $fecha_inscripcion, $horario, $id);
}

if ($stmt->execute()) {
    header("Location: ../views/afiliados/index.php?msg=actualizado");
} else {
    echo "Error al actualizar: " . $stmt->error;
}
?>
