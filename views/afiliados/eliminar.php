<?php
require_once '../../helpers/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../../helpers/db.php';

$conn = conectarDB();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Borra foto asociada (opcional)
    $foto = $conn->query("SELECT foto_nombre FROM afiliados_siao WHERE id=$id")->fetch_assoc();
    if ($foto && !empty($foto['foto_nombre'])) {
        @unlink("../../assets/uploads/fotos/" . $foto['foto_nombre']);
    }
    // Elimina el afiliado
    $conn->query("DELETE FROM afiliados_siao WHERE id=$id");
}

header("Location: index.php?msg=eliminado");
exit;
?>
