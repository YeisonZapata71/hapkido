<?php
// views/asistencia/tomar.php
// Esta vista ahora es renderizada casi exclusivamente por el controlador
// Se asume que $horarios, $deportistas, $fecha, $horario_id llegan desde el controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Control de Asistencia - SIAO</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Íconos Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #0d6efd;
      --card-bg: white;
    }
    body {
      background: linear-gradient(135deg, #f0f4f8 0%, #d7e1ec 100%);
      min-height: 100vh;
      font-family: 'Outfit', sans-serif;
      padding-bottom: 4rem;
    }
    .main-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 1.5rem 1rem;
    }
    
    /* Header */
    .page-header {
      text-align: center;
      margin-bottom: 2rem;
    }
    .page-header h2 {
      font-weight: 700;
      color: #2c3e50;
    }

    /* Filters Card */
    .filters-card {
      background: rgba(255,255,255,0.9);
      border-radius: 20px;
      padding: 1.5rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      margin-bottom: 2rem;
    }

    /* Student Card */
    .student-card {
      background: white;
      border-radius: 16px;
      padding: 1rem;
      margin-bottom: 1rem;
      border: 2px solid transparent;
      box-shadow: 0 2px 8px rgba(0,0,0,0.03);
      cursor: pointer;
      transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .student-card:active {
      transform: scale(0.98);
    }
    
    /* States */
    .student-card.present {
      border-color: #198754;
      background-color: #f0fdf4;
      box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15);
    }
    .student-card.absent {
      border-color: #e9ecef; /* Neutral look for unchecked, or red if we want explicit absent */
      opacity: 0.85;
    }
    
    .student-avatar {
      width: 55px;
      height: 55px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .status-indicator {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      border: 2px solid #dee2e6;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-left: auto;
      transition: all 0.2s;
    }
    
    .student-card.present .status-indicator {
      background: #198754;
      border-color: #198754;
      color: white;
    }
    
    .student-info h5 {
      margin: 0;
      font-size: 1rem;
      font-weight: 600;
      color: #343a40;
    }
    .student-info p {
      margin: 0;
      font-size: 0.8rem;
      color: #6c757d;
    }

    /* Floating Action Button */
    .fab-container {
      position: fixed;
      bottom: 2rem;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1000;
      width: 90%;
      max-width: 600px;
      text-align: center;
    }
    .btn-save {
      width: 100%;
      border-radius: 50px;
      padding: 1rem;
      font-weight: 700;
      font-size: 1.1rem;
      box-shadow: 0 10px 25px rgba(13, 110, 253, 0.3);
      transition: transform 0.2s;
    }
    .btn-save:active {
      transform: scale(0.98);
    }

    /* Date Restrictions */
    input[type="date"]::-webkit-calendar-picker-indicator {
      cursor: pointer;
    }

  </style>
</head>
<body>

<div class="main-container animate-fade-in">
  
  <!-- Header -->
  <div class="page-header d-flex justify-content-between align-items-center">
    <a href="../dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-arrow-left"></i> Volver</a>
    <h4 class="mb-0">Control de Asistencia</h4>
    <a href="consultar.php" class="btn btn-outline-info btn-sm rounded-pill" title="Ver Estadísticas"><i class="bi bi-graph-up"></i></a>
  </div>

  <!-- Messages -->
  <?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($_GET['msg']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  
  <?php if(isset($error) && $error): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Window Alert Banner -->
  <?php if(isset($ventana) && $ventana['activa']): ?>
    <div class="alert alert-warning border-warning shadow-sm rounded-4 mb-4">
      <div class="d-flex align-items-center mb-2">
        <i class="bi bi-calendar-check fs-4 me-3"></i>
        <div>
          <h5 class="alert-heading fw-bold mb-0">Ventana Especial de Asistencia</h5>
          <p class="mb-0 small">Se ha concedido un plazo excepcional para registrar asistencias.</p>
        </div>
      </div>
      <hr>
      <ul class="mb-0 small">
        <li><strong>Desde:</strong> <?= date('d/m/Y', strtotime($ventana['desde'])) ?></li>
        <li><strong>Hasta:</strong> <?= date('d/m/Y', strtotime($ventana['hasta'])) ?></li>
        <?php if($ventana['motivo']): ?>
          <li><strong>Motivo:</strong> <?= htmlspecialchars($ventana['motivo']) ?></li>
        <?php endif; ?>
      </ul>
      <p class="mt-2 mb-0 small opacity-75"><i>Luego de este plazo, el sistema solo permitirá registrar asistencia de hasta 2 días atrás.</i></p>
    </div>
  <?php endif; ?>

  <!-- Filters -->
  <div class="filters-card">
    <form action="" method="GET" id="filterForm">
      <div class="row g-3">
        <!-- Date Selector -->
        <div class="col-6">
          <label class="form-label small text-muted fw-bold">Fecha</label>
          <?php
            $hoy = date('Y-m-d');
            $min_normal = date('Y-m-d', strtotime('-2 days'));
            $max_date = $hoy;
            $min_date = $min_normal;
            $info_text = "Solo se permite tomar asistencia de hoy, ayer o anteayer.";

            // Si hay ventana activa, ampliamos el min_date al inicio de la ventana
            if (isset($ventana) && $ventana['activa']) {
                if ($ventana['desde'] < $min_date) {
                    $min_date = $ventana['desde'];
                }
                $info_text = "Puedes seleccionar fechas dentro de la ventana especial habilitada.";
            }

            $attr_min = ($rol_usuario === 'admin') ? '' : "min=\"$min_date\"";
          ?>
          <input type="date" class="form-control rounded-3" name="fecha" value="<?= htmlspecialchars($fecha) ?>" 
                 max="<?= $max_date ?>" <?= $attr_min ?> onchange="this.form.submit()">
          <div class="form-text text-muted" style="font-size: 0.75rem;">
            <i class="bi bi-info-circle me-1"></i><?= $info_text ?>
          </div>
        </div>
        
        <!-- Schedule Selector -->
        <div class="col-6">
          <label class="form-label small text-muted fw-bold">Horario</label>
          <select class="form-select rounded-3" name="horario_id" onchange="this.form.submit()">
            <option value="" disabled <?= !$horario_id ? 'selected' : '' ?>>Seleccionar...</option>
            <?php foreach($horarios as $h): ?>
              <option value="<?= $h['id'] ?>" <?= ($horario_id == $h['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($h['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </form>
  </div>

  <!-- Student List -->
  <?php if($horario_id && !empty($deportistas)): ?>
    <form action="asistencia_controller.php" method="POST" id="attendanceForm">
      <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
      <input type="hidden" name="horario_id" value="<?= htmlspecialchars($horario_id) ?>">

      <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <small class="text-muted fw-bold"><?= count($deportistas) ?> deportistas encontrados</small>
        <button type="button" class="btn btn-sm btn-link text-decoration-none" onclick="markAll()">Marcar Todos</button>
      </div>

      <div class="students-grid">
        <?php foreach($deportistas as $dep): ?>
          <?php 
            $isPresent = isset($dep['presente']) && $dep['presente']; 
            $statusClass = $isPresent ? 'present' : 'absent';
            $checked = $isPresent ? 'checked' : '';
          ?>
          <div class="student-card <?= $statusClass ?>" onclick="toggleCard(this)">
            <!-- Hidden Checkbox -->
            <input type="checkbox" name="asistencia[]" value="<?= $dep['id'] ?>" class="d-none" <?= $checked ?>>
            
            <!-- Avatar -->
            <?php
              $foto_nombre = $dep['foto_nombre'] ?? '';
              $ruta_fisica = __DIR__ . '/../../assets/uploads/fotos/' . $foto_nombre;
              
              if (!empty($foto_nombre) && file_exists($ruta_fisica)) {
                  $foto = "../assets/uploads/fotos/" . $foto_nombre;
              } else {
                  $foto = "https://ui-avatars.com/api/?name=".urlencode($dep['nombre_completo'])."&background=random";
              }
            ?>
            <img src="<?= $foto ?>" alt="Avatar" class="student-avatar">

            <!-- Name -->
            <div class="student-info">
              <h5><?= htmlspecialchars($dep['nombre_completo']) ?></h5>
              <p><i class="bi bi-award me-1"></i><?= htmlspecialchars($dep['grado_cinturon'] ?? 'Sin cinturón') ?></p>
            </div>

            <!-- Indicator -->
            <div class="status-indicator">
              <i class="bi bi-check-lg" style="font-size: 1.2rem;"></i>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Floating Save Button -->
      <div class="fab-container animate-slide-up">
        <button type="submit" class="btn btn-primary btn-save text-white">
          <i class="bi bi-save me-2"></i> GUARDAR ASISTENCIA
        </button>
      </div>
      
    </form>

  <?php elseif($horario_id): ?>
    <div class="text-center py-5 text-muted">
      <i class="bi bi-people display-4 mb-3 d-block"></i>
      <p>No hay deportistas inscritos en este horario.</p>
    </div>
  <?php else: ?>
    <div class="text-center py-5 text-muted">
      <i class="bi bi-arrow-up-circle display-4 mb-3 d-block"></i>
      <p>Selecciona un horario para comenzar.</p>
    </div>
  <?php endif; ?>

</div>

<script>
  function toggleCard(card) {
    const checkbox = card.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
    
    if(checkbox.checked) {
      card.classList.add('present');
      card.classList.remove('absent');
    } else {
      card.classList.remove('present');
      card.classList.add('absent');
    }
  }

  function markAll() {
    const cards = document.querySelectorAll('.student-card');
    let allChecked = true;
    
    // Check if currently all are checked to decide toggle direction
    cards.forEach(c => {
      if(!c.querySelector('input').checked) allChecked = false;
    });

    cards.forEach(c => {
      const checkbox = c.querySelector('input');
      if(!allChecked) {
        if(!checkbox.checked) toggleCard(c);
      } else {
        if(checkbox.checked) toggleCard(c);
      }
    });
  }
</script>

</body>
</html>
