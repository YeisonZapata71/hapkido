<?php
require_once '../../helpers/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../../helpers/db.php';
$conn = conectarDB();
require_once '../../controllers/instructores_controller.php';
$id = intval($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (editarInstructor($id, $_POST)) {
        header("Location: index.php?exito=1");
        exit;
    }
    $error = "No se pudo actualizar los horarios.";
}

// Validar ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) die('ID inválido');

// Obtener datos del instructor
$stmt = $conn->prepare("SELECT * FROM afiliados_siao WHERE id = ? AND rol = 'instructor'");
$stmt->bind_param('i', $id);
$stmt->execute();
$instructor = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$instructor) die('Instructor no encontrado');

// Horarios disponibles (ajusta según tu caso real)
$horariosDisponibles = [
    "Lunes 6:00 am",
    "Martes 5:00 pm",
    "Miércoles 7:30 pm",
    "Jueves 5:00 pm",
    "Viernes 6:00 am",
    "Sábado 7:00 am",
    "Sábado 12:00 pm"
];

// Si los horarios guardados son separados por coma:
$horariosActuales = array_map('trim', explode(',', $instructor['horario'] ?? ''));

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_completo'] ?? '';
    $documento = $_POST['documento'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $horario = isset($_POST['horario']) ? implode(', ', $_POST['horario']) : '';
    $foto_nombre = $instructor['foto_nombre'];

    // Si se carga nueva foto
    if (!empty($_FILES['foto']['name'])) {
        $foto_nombre = time() . '_' . basename($_FILES['foto']['name']);
        $destino = "../../assets/uploads/fotos/" . $foto_nombre;
        move_uploaded_file($_FILES['foto']['tmp_name'], $destino);
    }

    $stmt = $conn->prepare("UPDATE afiliados_siao SET nombre_completo=?, documento=?, celular=?, correo=?, ciudad=?, horario=?, foto_nombre=? WHERE id=?");
    $stmt->bind_param('sssssssi', $nombre, $documento, $celular, $correo, $ciudad, $horario, $foto_nombre, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?msg=Instructor+actualizado");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Instructor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <a href="index.php" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
      <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-fill"></i> Editar Instructor</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" name="nombre_completo" value="<?= htmlspecialchars($instructor['nombre_completo']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Documento</label>
                        <input type="text" class="form-control" name="documento" value="<?= htmlspecialchars($instructor['documento']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Celular</label>
                        <input type="text" class="form-control" name="celular" value="<?= htmlspecialchars($instructor['celular']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" class="form-control" name="correo" value="<?= htmlspecialchars($instructor['correo']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ciudad</label>
                        <input type="text" class="form-control" name="ciudad" value="<?= htmlspecialchars($instructor['ciudad']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Horario asignado</label>
                        <select name="horario[]" class="form-select selectpicker" multiple data-live-search="true" title="Selecciona uno o varios horarios" required>
                            <?php foreach ($horariosDisponibles as $h): ?>
                                <option value="<?= htmlspecialchars($h) ?>" <?= in_array($h, $horariosActuales) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($h) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Puedes elegir uno o varios horarios.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto actual</label><br>
                        <?php if (!empty($instructor['foto_nombre'])): ?>
                            <img src="../../assets/uploads/fotos/<?= htmlspecialchars($instructor['foto_nombre']) ?>" alt="Foto" style="width:80px;height:80px;object-fit:cover;border-radius:10px;">
                        <?php else: ?>
                            <span class="text-muted">Sin foto</span>
                        <?php endif; ?>
                        <input type="file" class="form-control mt-2" name="foto" accept="image/*">
                    </div>
                </div>
                <div class="mt-4">
                    <a href="index.php" class="btn btn-outline-secondary px-4 rounded-pill">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i>Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS y Bootstrap-select -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script>
  document.addEventListener('DOMContentLoaded', function() {
      $('.selectpicker').selectpicker();
  });
</script>
</body>
</html>
