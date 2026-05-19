<?php
require_once '../../helpers/auth.php';
require_once '../../helpers/db.php';

verificarSesion();
verificarRol('admin');

$conn = conectarDB();

// Validar ID del instructor
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['error'] = "ID de instructor no válido";
    header("Location: index.php");
    exit;
}

// Obtener información del instructor
$stmt = $conn->prepare("SELECT id, nombre_completo FROM afiliados_siao WHERE id = ? AND rol = 'instructor'");
$stmt->bind_param("i", $id);
$stmt->execute();
$inst = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$inst) {
    $_SESSION['error'] = "Instructor no encontrado";
    header("Location: index.php");
    exit;
}

// Obtener todos los horarios activos
$horarios = $conn->query("SELECT id, nombre FROM horarios WHERE activo = 1 ORDER BY nombre ASC")->fetch_all(MYSQLI_ASSOC);

// Obtener horarios asignados
$asignados = [];
$stmt = $conn->prepare("SELECT horario_id FROM instructor_horario WHERE instructor_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $asignados[] = $row['horario_id'];
}
$stmt->close();

include '../../partials/header.php';
?>

<div class="container my-4">
  <h2 class="mb-3">Asignar horarios a: <?= htmlspecialchars($inst['nombre_completo']) ?></h2>
  
  <?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  
  <form method="post" action="guardar_horarios.php">
    <input type="hidden" name="instructor_id" value="<?= $id ?>">
    <div class="mb-3">
      <label class="form-label">Horarios Disponibles:</label>
      <div class="row">
        <?php foreach($horarios as $h): ?>
        <div class="col-md-4 mb-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" 
                   name="horarios[]" 
                   value="<?= $h['id'] ?>"
                   id="horario<?= $h['id'] ?>"
                   <?= in_array($h['id'], $asignados) ? 'checked' : '' ?>>
            <label class="form-check-label" for="horario<?= $h['id'] ?>">
              <?= htmlspecialchars($h['nombre']) ?>
            </label>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <a href="index.php" class="btn btn-outline-secondary px-4 rounded-pill">Cancelar</a>
    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm">Guardar Asignaciones</button>
  </form>
</div>

<?php include '../../partials/footer.php'; ?>