<?php
require_once '../../helpers/db.php';
$conn = conectarDB();

$q = trim($_GET['q'] ?? '');
if ($q) {
    $qLike = "%$q%";
    $stmt = $conn->prepare("SELECT * FROM afiliados_siao WHERE rol='instructor' AND (nombre_completo LIKE ? OR documento LIKE ? OR correo LIKE ? OR celular LIKE ?) ORDER BY nombre_completo ASC");
    $stmt->bind_param('ssss', $qLike, $qLike, $qLike, $qLike);
    $stmt->execute();
    $res = $stmt->get_result();
    $instructores = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    // Si no viene del controlador, intentamos cargar
    if (!isset($instructores)) {
        $res = $conn->query("SELECT * FROM afiliados_siao WHERE rol='instructor' ORDER BY nombre_completo ASC");
        $instructores = $res->fetch_all(MYSQLI_ASSOC);
    }
}
$conn->close();
?>
<?php
// Si de alguna forma sigue nulo y no se pudo cargar, redirigir al controlador (fallback)
if (!isset($instructores)) {
  header('Location: ../../controllers/instructores_controller.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instructores - SIAO</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Íconos Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #0d6efd;
      --secondary-color: #6c757d;
    }
    body {
      background: linear-gradient(135deg, #f0f4f8 0%, #d7e1ec 100%);
      min-height: 100vh;
      font-family: 'Outfit', sans-serif;
      padding-bottom: 2rem;
    }
    .main-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem 1rem;
    }
    .content-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
      padding: 2rem;
      border: none;
    }
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 1rem;
    }
    .page-title h2 {
      font-weight: 700;
      color: #2c3e50;
      margin: 0;
    }
    .page-title p {
      color: #7f8c8d;
      margin: 0;
      font-size: 0.95rem;
    }
    .search-box {
      background: #f8f9fa;
      border-radius: 50px;
      padding: 0.5rem 1rem;
      border: 1px solid #e9ecef;
      display: flex;
      align-items: center;
      width: 100%;
      max-width: 400px;
    }
    .search-box input {
      border: none;
      background: transparent;
      box-shadow: none;
      width: 100%;
    }
    .search-box input:focus {
      outline: none;
    }
    /* Table Styling */
    .table-container {
      border-radius: 15px;
      overflow: hidden;
      border: 1px solid #e9ecef;
    }
    .table thead {
      background: #f8f9fa;
    }
    .table th {
      font-weight: 600;
      color: #495057;
      text-transform: uppercase;
      font-size: 0.85rem;
      border-bottom: none;
      padding: 1rem;
    }
    .table td {
      vertical-align: middle;
      padding: 1rem;
      font-size: 0.95rem;
    }
    .avatar-img {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e9ecef;
    }
    .avatar-placeholder {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #adb5bd;
      font-size: 1.2rem;
    }
    .badge-custom {
      padding: 0.5em 0.8em;
      border-radius: 30px;
      font-weight: 500;
      font-size: 0.75rem;
    }
    .btn-action {
      width: 32px;
      height: 32px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      margin: 0 2px;
      transition: all 0.2s;
    }
    .btn-action:hover {
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

<div class="main-container">

  <!-- Header Section -->
  <div class="page-header animate-fade-in">
    <div class="page-title">
      <h2>Gestión de Instructores</h2>
      <p>Administra el equipo docente y sus accesos</p>
    </div>
    <div class="d-flex gap-2">
      <a href="../../dashboard.php" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-2"></i>Volver
      </a>
      <a href="asignar_rol.php" class="btn btn-primary rounded-pill">
        <i class="bi bi-person-plus-fill me-2"></i>Nuevo Instructor
      </a>
    </div>
  </div>

  <!-- Messages -->
  <?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 12px;">
      <i class="bi bi-check-circle-fill me-2"></i>
      <?php
        if($_GET['msg'] == 'usuario_creado') echo "Usuario creado y acceso otorgado exitosamente.";
        if($_GET['msg'] == 'usuario_vinculado') echo "Acceso activado. El usuario ya existía y se vinculó.";
        if($_GET['msg'] == 'password_reset') echo "Contraseña reseteada. Nueva temporal: <strong>" . htmlspecialchars($_GET['nueva']) . "</strong>";
        if($_GET['msg'] == 'usuario_suspendido') echo "Acceso suspendido correctamente.";
        if($_GET['msg'] == 'error') echo "Hubo un error en la operación.";
      ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Content Card -->
  <div class="content-card">
    
    <!-- Search Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <form class="search-box" method="get">
        <i class="bi bi-search text-muted me-2"></i>
        <input type="search" name="q" placeholder="Buscar instructor..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
      </form>
    </div>

    <!-- Instructor Table -->
    <div class="table-container">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Instructor</th>
              <th>Contacto</th>
              <th>Horarios</th>
              <th>Rol</th>
              <th>Estado</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php $n=1; foreach($instructores as $inst): ?>
            <tr>
              <td><?= $n++ ?></td>
              <td>
                <div class="d-flex align-items-center gap-3">
                  <?php
                    $foto = "../../assets/uploads/fotos/" . $inst['foto_nombre'];
                    if (!empty($inst['foto_nombre']) && file_exists($foto)) {
                      echo '<img src="' . $foto . '" alt="Foto" class="avatar-img">';
                    } else {
                      echo '<div class="avatar-placeholder"><i class="bi bi-person"></i></div>';
                    }
                  ?>
                  <div>
                    <div class="fw-bold text-dark"><?= htmlspecialchars($inst['nombre_completo']) ?></div>
                    <small class="text-muted"><?= htmlspecialchars($inst['documento']) ?></small>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <small><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($inst['correo']) ?></small>
                  <small><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($inst['celular']) ?></small>
                </div>
              </td>
              <td>
                <?php
                  // Horarios asignados (reutilizando lógica existente)
                  if(!isset($conn_h) || !$conn_h) { // simple check to reuse or open new connection
                      require_once '../../helpers/db.php';
                      $conn_h = conectarDB();
                  }
                  // Es mejor abrir una nueva conexión local si la previa se cerró, o manejar conexiones mejor. 
                  // Dado el script original, abriremos una nueva por simplicidad en el loop
                  $conn_local = conectarDB();
                  $sql_h = "SELECT h.nombre FROM instructor_horario ih 
                            JOIN horarios h ON ih.horario_id = h.id 
                            WHERE ih.instructor_id = ?";
                  $stmt_h = $conn_local->prepare($sql_h);
                  $stmt_h->bind_param("i", $inst['id']);
                  $stmt_h->execute();
                  $res_h = $stmt_h->get_result();
                  
                  if ($res_h->num_rows > 0) {
                    while ($row_h = $res_h->fetch_assoc()) {
                      echo '<span class="badge bg-light text-dark border me-1 mb-1">' . htmlspecialchars($row_h['nombre']) . '</span>';
                    }
                  } else {
                    echo '<span class="text-muted small">Sin horarios</span>';
                  }
                  $stmt_h->close();
                  $conn_local->close();
                ?>
              </td>
              <td>
                <span class="badge badge-custom <?= $inst['rol']=='admin' ? 'bg-danger' : 'bg-info' ?>">
                  <?= ucfirst($inst['rol']) ?>
                </span>
              </td>
              <td>
                <?php if (!$inst['usuario_id']): ?>
                  <span class="badge badge-custom bg-light text-secondary border">Sin Acceso</span>
                <?php else: ?>
                  <span class="badge badge-custom bg-success-subtle text-success border border-success">Activo</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <a href="horarios.php?id=<?= $inst['id'] ?>" class="btn btn-outline-primary btn-action" title="Asignar horarios">
                  <i class="bi bi-calendar-range"></i>
                </a>
                
                <!-- Acceso Sistema -->
                <?php if (!$inst['usuario_id']): ?>
                  <button class="btn btn-outline-success btn-action" data-bs-toggle="modal" data-bs-target="#modalAcceso<?= $inst['id'] ?>" title="Dar Acceso">
                    <i class="bi bi-key"></i>
                  </button>
                <?php else: ?>
                  <a href="../../controllers/instructores_controller.php?accion=reset_password&id=<?= $inst['usuario_id'] ?>" class="btn btn-outline-warning btn-action" title="Resetear Contraseña" onclick="return confirm('¿Resetear contraseña?')">
                    <i class="bi bi-arrow-counterclockwise"></i>
                  </a>
                  <a href="../../controllers/instructores_controller.php?accion=suspender_acceso&id=<?= $inst['usuario_id'] ?>" class="btn btn-outline-secondary btn-action" title="Suspender" onclick="return confirm('¿Suspender acceso?')">
                    <i class="bi bi-slash-circle"></i>
                  </a>
                <?php endif; ?>

                <a href="procesar_rol.php?id=<?= $inst['id'] ?>&accion=quitar" class="btn btn-outline-danger btn-action" title="Quitar Rol" onclick="return confirm('¿Quitar rol de instructor a <?= htmlspecialchars($inst['nombre_completo']) ?>?')">
                  <i class="bi bi-x-lg"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php if (empty($instructores)): ?>
        <div class="p-5 text-center">
          <i class="bi bi-people text-muted display-4 mb-3"></i>
          <p class="text-muted">No se encontraron instructores registrados.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>

</div>

<!-- Modals outside table -->
<?php foreach($instructores as $inst): ?>
  <?php if (!$inst['usuario_id']): ?>
    <div class="modal fade" id="modalAcceso<?= $inst['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="../../controllers/instructores_controller.php?accion=dar_acceso" autocomplete="off" class="modal-content border-0 shadow">
          <input type="hidden" name="afiliado_id" value="<?= $inst['id'] ?>">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title fs-6 fw-bold">Habilitar Acceso al Sistema</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label small text-muted text-uppercase fw-bold">Usuario</label>
              <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($inst['nombre_completo']) ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted text-uppercase fw-bold">Correo (Usuario)</label>
              <input type="email" class="form-control" name="correo" value="<?= htmlspecialchars($inst['correo']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label small text-muted text-uppercase fw-bold">Contraseña Temporal</label>
              <div class="input-group">
                <input type="text" class="form-control font-monospace" name="password" id="passwordTemp<?= $inst['id'] ?>" required readonly>
                <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('passwordTemp<?= $inst['id'] ?>').value = generarPasswordTemporal()"><i class="bi bi-shuffle"></i></button>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-link text-decoration-none text-muted" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary px-4 rounded-pill">Confirmar Acceso</button>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>
<?php endforeach; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function generarPasswordTemporal() {
    const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789";
    let pass = "";
    for (let i = 0; i < 10; i++) {
      pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return pass;
  }

  // Auto-generar password al abrir modal
  const modals = document.querySelectorAll('.modal');
  modals.forEach(modalEl => {
    modalEl.addEventListener('show.bs.modal', event => {
      const modalId = modalEl.id; // e.g., modalAcceso123
      const id = modalId.replace('modalAcceso', '');
      const input = document.getElementById('passwordTemp' + id);
      if(input && !input.value) {
        input.value = generarPasswordTemporal();
      }
    });
  });
</script>

</body>
</html>
