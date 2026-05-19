<?php
require_once '../../helpers/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../../helpers/db.php';

$conn = conectarDB();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM afiliados_siao WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
  echo "<div class='alert alert-danger'>Afiliado no encontrado.</div>";
  exit;
}
$afiliado = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ficha de Afiliado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="card shadow p-4 mx-auto" style="max-width: 600px;">
      <div class="text-center mb-3">
        <?php
$rutaFoto = "../../assets/uploads/fotos/" . $afiliado['foto_nombre'];
if (!empty($afiliado['foto_nombre']) && file_exists($rutaFoto)) {
    echo '<img src="' . $rutaFoto . '" alt="Foto afiliado" class="rounded-circle shadow" style="width:100px;height:100px;object-fit:cover;">';
} else {
    // Puedes mostrar una imagen por defecto o un texto
    echo '<span class="text-muted">No hay foto</span>';
    // O una imagen genérica:
    // echo '<img src="../../assets/uploads/fotos/default-user.png" alt="Sin foto" class="rounded-circle shadow" style="width:100px;height:100px;object-fit:cover;">';
}
?>
        <h4 class="mt-3"><?= htmlspecialchars($afiliado['nombre_completo']) ?></h4>
        <span class="badge bg-primary"><?= htmlspecialchars($afiliado['documento']) ?></span>
      </div>
      <table class="table table-bordered table-sm bg-white">
        <tr><th>Fecha nacimiento</th><td><?= htmlspecialchars($afiliado['fecha_nacimiento']) ?></td></tr>
        <tr><th>Sexo</th><td><?= htmlspecialchars($afiliado['sexo']) ?></td></tr>
        <tr><th>Celular</th><td><?= htmlspecialchars($afiliado['celular']) ?></td></tr>
        <tr><th>Correo</th><td><?= htmlspecialchars($afiliado['correo']) ?></td></tr>
        <tr><th>Dirección</th><td><?= htmlspecialchars($afiliado['direccion']) ?></td></tr>
        <tr><th>Ciudad</th><td><?= htmlspecialchars($afiliado['ciudad']) ?></td></tr>
        <tr><th>EPS</th><td><?= htmlspecialchars($afiliado['eps']) ?></td></tr>
        <tr><th>Tipo de sangre</th><td><?= htmlspecialchars($afiliado['tipo_sangre']) ?></td></tr>
        <tr><th>Acudiente</th><td><?= htmlspecialchars($afiliado['nombre_acudiente']) ?></td></tr>
        <tr><th>Grado cinturón</th><td><?= htmlspecialchars($afiliado['grado_cinturon']) ?></td></tr>
        <tr><th>Fecha inscripción</th><td><?= htmlspecialchars($afiliado['fecha_inscripcion']) ?></td></tr>
        <tr><th>Horario</th><td><?= htmlspecialchars($afiliado['horario']) ?></td></tr>
      </table>
      <a href="index.php" class="btn btn-outline-secondary mt-3">Volver al listado</a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
