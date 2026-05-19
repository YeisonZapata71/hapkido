<?php
require_once '../../helpers/db.php';
require_once '../../helpers/auth.php';
verificarSesion();
verificarRol('admin');

$conn = conectarDB();

// Consultar todos los afiliados para que el admin elija a quién volver instructor
// Traemos también 'foto_nombre' para el avatar
$resultado = $conn->query("SELECT id, nombre_completo, documento, correo, rol, foto_nombre FROM afiliados_siao ORDER BY nombre_completo ASC");
$afiliados = $resultado->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asignar Instructor - SIAO</title>
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
      max-width: 1000px;
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
    .search-input {
      border-radius: 50px;
      padding: 0.75rem 1.5rem;
      border: 1px solid #e9ecef;
      background-color: #f8f9fa;
      width: 100%;
      margin-bottom: 2rem;
      transition: all 0.3s;
    }
    .search-input:focus {
      background-color: white;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
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
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e9ecef;
    }
    .avatar-placeholder {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #adb5bd;
    }
    .badge-custom {
      padding: 0.5em 0.8em;
      border-radius: 30px;
      font-weight: 500;
      font-size: 0.75rem;
    }
    .btn-action {
      border-radius: 50px;
      padding: 0.375rem 1rem;
      font-size: 0.85rem;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
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
      <h2>Asignar Rol de Instructor</h2>
      <p>Busca un afiliado y conviértelo en instructor</p>
    </div>
    <a href="index.php" class="btn btn-outline-secondary rounded-pill">
      <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
  </div>

  <!-- Messages -->
  <?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 12px;">
      <i class="bi bi-check-circle-fill me-2"></i>
      <?php
        if($_GET['msg'] == 'rol_asignado') echo "Rol de INSTRUCTOR asignado correctamente.";
        if($_GET['msg'] == 'rol_quitado') echo "Rol removido. Ahora es un afiliado normal.";
        if($_GET['msg'] == 'rol_actualizado') echo "Rol actualizado correctamente.";
      ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Content Card -->
  <div class="content-card">
    
    <!-- Real-time Search -->
    <div class="position-relative">
      <i class="bi bi-search position-absolute text-muted" style="top: 15px; left: 15px; font-size: 1.1rem;"></i>
      <input type="text" id="searchInput" class="search-input" style="padding-left: 3rem;" placeholder="Escribe nombre o documento para filtrar...">
    </div>

    <!-- Affiliate Table -->
    <div class="table-container">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="affiliateTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Afiliado</th>
              <th>Documento</th>
              <th>Correo</th>
              <th>Rol Actual</th>
              <th class="text-center">Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php $n=1; foreach($afiliados as $row): ?>
            <tr>
              <td><?= $n++ ?></td>
              <td>
                <div class="d-flex align-items-center gap-3">
                  <?php
                    $foto = "../../assets/uploads/fotos/" . ($row['foto_nombre'] ?? '');
                    if (!empty($row['foto_nombre']) && file_exists($foto)) {
                      echo '<img src="' . $foto . '" alt="avatar" class="avatar-img">';
                    } else {
                      echo '<div class="avatar-placeholder"><i class="bi bi-person"></i></div>';
                    }
                  ?>
                  <span class="fw-bold text-dark"><?= htmlspecialchars($row['nombre_completo']) ?></span>
                </div>
              </td>
              <td><?= htmlspecialchars($row['documento']) ?></td>
              <td><?= htmlspecialchars($row['correo']) ?></td>
              <td>
                <?php if ($row['rol'] == 'instructor'): ?>
                  <span class="badge badge-custom bg-primary-subtle text-primary border border-primary">Instructor</span>
                <?php else: ?>
                  <span class="badge badge-custom bg-light text-secondary border">Afiliado</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if ($row['rol'] == 'instructor'): ?>
                  <form method="post" action="procesar_rol.php" class="d-inline">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="accion" value="quitar">
                    <button class="btn btn-outline-danger btn-action" onclick="return confirm('¿Quitar rol de instructor a <?= htmlspecialchars($row['nombre_completo']) ?>? Su acceso de usuario NO se eliminará, pero perderá permisos de rol.')">
                      <i class="bi bi-person-down"></i> Quitar Rol
                    </button>
                  </form>
                <?php else: ?>
                  <form method="post" action="procesar_rol.php" class="d-inline">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="accion" value="asignar">
                    <button class="btn btn-primary btn-action" onclick="return confirm('¿Asignar rol de instructor a <?= htmlspecialchars($row['nombre_completo']) ?>?')">
                      <i class="bi bi-person-up"></i> Ascender
                    </button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <!-- No Results Message (Hidden by default) -->
      <div id="noResults" class="p-5 text-center d-none">
        <i class="bi bi-search text-muted display-4 mb-3"></i>
        <p class="text-muted">No se encontraron coincidencias.</p>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Real-time search functionality
  document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#affiliateTable tbody tr');
    let hasVisible = false;

    rows.forEach(row => {
      let text = row.innerText.toLowerCase();
      if (text.includes(filter)) {
        row.style.display = '';
        hasVisible = true;
      } else {
        row.style.display = 'none';
      }
    });

    // Toggle "No results" message
    document.getElementById('noResults').classList.toggle('d-none', hasVisible);
  });
</script>

</body>
</html>
