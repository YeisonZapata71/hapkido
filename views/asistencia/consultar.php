<?php
require_once '../../helpers/auth.php';
require_once '../../helpers/db.php';
verificarSesion();

$conn = conectarDB();
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];
$es_admin = ($rol === 'admin');

// 1. Filtros Generales
$fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');
$horario_id = intval($_GET['horario_id'] ?? 0);
$afiliado_id = intval($_GET['afiliado_id'] ?? 0);
$instructor_id = intval($_GET['instructor_id'] ?? 0);

// Restricción para instructores
if ($rol === 'instructor') {
    $stmt = $conn->prepare("SELECT id FROM afiliados_siao WHERE usuario_id = ? AND rol = 'instructor'");
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $instructor_id = $res ? $res['id'] : 0;
    $stmt->close();
}

// Construcción del WHERE dinámico
$where_conds = ["a.fecha BETWEEN ? AND ?"];
$params = [$fecha_inicio, $fecha_fin];
$types = "ss";

if ($horario_id > 0) {
    $where_conds[] = "a.horario_id = ?";
    $params[] = $horario_id;
    $types .= "i";
}
if ($afiliado_id > 0) {
    $where_conds[] = "a.afiliado_id = ?";
    $params[] = $afiliado_id;
    $types .= "i";
}
if ($instructor_id > 0) {
    $where_conds[] = "a.instructor_id = ?";
    $params[] = $instructor_id;
    $types .= "i";
}

$where_clause = implode(" AND ", $where_conds);

// --- CONSULTAS ---

// A. Estadísticas Globales (Cards Superior)
$sql_stats = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estado='presente' THEN 1 ELSE 0 END) as presentes,
    SUM(CASE WHEN estado='ausente' THEN 1 ELSE 0 END) as ausentes,
    COUNT(DISTINCT afiliado_id) as unicos
    FROM asistencia a WHERE $where_clause";
$stmt = $conn->prepare($sql_stats);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
$stmt->close();

// B. Tarjetas: Asistencia por Horario (traemos asistentes y fechas agrupadas)
$sql_chart_horario = "SELECT h.id as horario_id, h.nombre, COUNT(*) as cantidad,
    GROUP_CONCAT(CONCAT(af.nombre_completo, ' (', DATE_FORMAT(a.fecha, '%d-%b'), ')') SEPARATOR '|') as detalles
    FROM asistencia a 
    JOIN horarios h ON a.horario_id = h.id 
    JOIN afiliados_siao af ON a.afiliado_id = af.id
    WHERE $where_clause 
    AND a.estado = 'presente'
    GROUP BY h.id 
    ORDER BY cantidad DESC";
$stmt = $conn->prepare($sql_chart_horario);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$chart_horario_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// C. Gráfico: Tendencia (Últimos 30 días)
// Nota: Usamos la fecha de filtro para la gráfica
$sql_chart_trend = "SELECT fecha, 
    SUM(CASE WHEN estado='presente' THEN 1 ELSE 0 END) as asistencia 
    FROM asistencia a 
    WHERE $where_clause 
    GROUP BY fecha 
    ORDER BY fecha ASC";
$stmt = $conn->prepare($sql_chart_trend);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$chart_trend_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// D. Gráfico: Tendencia y Tarjetas por Periodo de Tiempo (Agrupados por Mes)
$sql_periodos = "SELECT DATE_FORMAT(fecha, '%Y-%m') as periodo, 
    SUM(CASE WHEN estado='presente' THEN 1 ELSE 0 END) as presentes,
    SUM(CASE WHEN estado='ausente' THEN 1 ELSE 0 END) as ausentes,
    COUNT(*) as total
    FROM asistencia a 
    WHERE $where_clause 
    GROUP BY periodo 
    ORDER BY periodo DESC";
$stmt = $conn->prepare($sql_periodos);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$cards_periodo_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// E. Alerta de Deserción (Afiliados con > 3 inasistencias en el rango seleccionado)
// Solo mostramos si hay filtro de fecha reciente para que tenga sentido
$alertas_desercion = [];
if ($es_admin) {
    $sql_desercion = "SELECT af.nombre_completo, af.celular, af.foto_nombre, COUNT(*) as faltas 
        FROM asistencia a
        JOIN afiliados_siao af ON a.afiliado_id = af.id
        WHERE a.estado = 'ausente' 
        AND a.fecha BETWEEN ? AND ?
        GROUP BY a.afiliado_id
        HAVING faltas >= 3
        ORDER BY faltas DESC LIMIT 5";
    $stmt = $conn->prepare($sql_desercion);
    $stmt->bind_param('ss', $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $alertas_desercion = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// F. Listado Detallado (Tabla inferior)
$sql_lista = "SELECT a.fecha, a.estado, h.nombre as horario, af.nombre_completo as alumno, af.documento
    FROM asistencia a
    JOIN afiliados_siao af ON a.afiliado_id = af.id
    JOIN horarios h ON a.horario_id = h.id
    WHERE $where_clause
    ORDER BY a.fecha DESC LIMIT 200";
$stmt = $conn->prepare($sql_lista);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$registros = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Carga de Selects para Filtros
if ($es_admin) {
    $horarios_list = $conn->query("SELECT id, nombre FROM horarios WHERE activo=1 ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
    $instructores_list = $conn->query("SELECT id, nombre_completo FROM afiliados_siao WHERE rol='instructor' ORDER BY nombre_completo")->fetch_all(MYSQLI_ASSOC);
} else {
    // Si es instructor, mostrar TODOS sus horarios asignados (sin filtrar por el horario actual de la clase, sino todos los que puede ver)
    $sql_h_inst = "SELECT h.id, h.nombre 
                   FROM horarios h
                   JOIN instructor_horario ih ON h.id = ih.horario_id
                   WHERE ih.instructor_id = ? AND h.activo = 1
                   ORDER BY h.nombre";
    $stmt_h = $conn->prepare($sql_h_inst);
    $stmt_h->bind_param('i', $instructor_id);
    $stmt_h->execute();
    $horarios_list = $stmt_h->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_h->close();
}
$conn->close();

// Preparar JSON para JS
$json_horarios_label = json_encode(array_column($chart_horario_data, 'nombre'));
$json_horarios_data = json_encode(array_column($chart_horario_data, 'cantidad'));

$json_trend_label = json_encode(array_column($chart_trend_data, 'fecha'));
$json_trend_data = json_encode(array_column($chart_trend_data, 'asistencia'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Analítica de Asistencia - SIAO</title>
  <!-- CSS libs -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
  <style>
    body { background: #f4f6f9; font-family: 'Outfit', sans-serif; }
    /* Estilos para exportación */
    .export-mode { padding: 20px; border-radius: 10px; background: white !important; height: auto !important; max-height: none !important; overflow: visible !important; }
    .card-stat { border: none; border-radius: 15px; transition: transform 0.2s; color: white; overflow: hidden; }
    .card-stat:hover { transform: translateY(-5px); }
    .bg-gradient-primary { background: linear-gradient(45deg, #4e73df, #224abe); }
    .bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #13855c); }
    .bg-gradient-danger { background: linear-gradient(45deg, #e74a3b, #be2617); }
    .bg-gradient-info { background: linear-gradient(45deg, #36b9cc, #258391); }
    
    .chart-container { position: relative; height: 300px; width: 100%; }
    .avatar-sm { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
    
    .filter-bar { background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
  </style>
</head>
<body>

<div class="container-fluid py-4 px-lg-5">
  
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold text-dark mb-0">Analítica de Asistencia</h3>
      <p class="text-muted small">Reporte de rendimiento y cumplimiento</p>
    </div>
    <div class="d-flex gap-2">
      <a href="index.php" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-plus-lg me-2"></i>Nueva Asistencia</a>
      <a href="../../dashboard.php" class="btn btn-sm btn-outline-secondary rounded-pill px-4"><i class="bi bi-arrow-left me-2"></i>Volver</a>
    </div>
  </div>

  <!-- Filtros -->
  <div class="filter-bar mb-4">
    <form class="row g-3 align-items-end">
      <div class="col-md-2">
        <label class="small text-muted fw-bold">Desde</label>
        <input type="date" name="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?>">
      </div>
      <div class="col-md-2">
        <label class="small text-muted fw-bold">Hasta</label>
        <input type="date" name="fecha_fin" class="form-control" value="<?= $fecha_fin ?>">
      </div>
      <div class="col-md-3">
        <label class="small text-muted fw-bold">Horario</label>
        <select name="horario_id" class="form-select">
          <option value="0">Todos</option>
          <?php foreach($horarios_list as $h): ?>
            <option value="<?= $h['id'] ?>" <?= $horario_id == $h['id'] ? 'selected' : '' ?>><?= $h['nombre'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php if($es_admin): ?>
      <div class="col-md-3">
        <label class="small text-muted fw-bold">Instructor</label>
        <select name="instructor_id" class="form-select">
          <option value="0">Todos</option>
          <?php foreach($instructores_list as $i): ?>
            <option value="<?= $i['id'] ?>" <?= $instructor_id == $i['id'] ? 'selected' : '' ?>><?= $i['nombre_completo'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>
      <div class="col-md-2">
        <button type="submit" class="btn btn-dark w-100 rounded-pill"><i class="bi bi-funnel me-1"></i> Filtrar</button>
      </div>
    </form>
  </div>

  <!-- KPI Cards -->
  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card card-stat bg-gradient-primary p-3">
        <h2 class="fw-bold mb-0"><?= number_format($stats['total']) ?></h2>
        <small class="text-white-50 text-uppercase fw-bold">Registros Totales</small>
        <i class="bi bi-clipboard-data position-absolute" style="top: 15px; right: 15px; font-size: 2rem; opacity: 0.3;"></i>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-stat bg-gradient-success p-3">
        <h2 class="fw-bold mb-0"><?= number_format($stats['presentes']) ?></h2>
        <small class="text-white-50 text-uppercase fw-bold">Presentes</small>
        <i class="bi bi-check-circle position-absolute" style="top: 15px; right: 15px; font-size: 2rem; opacity: 0.3;"></i>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-stat bg-gradient-danger p-3">
        <h2 class="fw-bold mb-0"><?= number_format($stats['ausentes']) ?></h2>
        <small class="text-white-50 text-uppercase fw-bold">Ausencias</small>
        <i class="bi bi-x-circle position-absolute" style="top: 15px; right: 15px; font-size: 2rem; opacity: 0.3;"></i>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-stat bg-gradient-info p-3">
        <h2 class="fw-bold mb-0"><?= number_format($stats['unicos']) ?></h2>
        <small class="text-white-50 text-uppercase fw-bold">Alumnos Únicos</small>
        <i class="bi bi-people position-absolute" style="top: 15px; right: 15px; font-size: 2rem; opacity: 0.3;"></i>
      </div>
    </div>
  </div>

  <!-- Cards de Asistencia Detalladas (Nuevas) -->
  <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
    <h5 class="fw-bold mb-0">Resumen por Horario y Periodo</h5>
  </div>
  
  <div id="cards-export-area" class="p-3 bg-white rounded shadow-sm mb-4">
    <!-- Tarjetas por Horario -->
    <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2">Asistencias por Horario</h6>
    <div class="row g-3 mb-4">
      <?php if(empty($chart_horario_data)): ?>
        <div class="col-12"><p class="text-muted small">No hay datos para mostrar.</p></div>
      <?php else: ?>
        <?php foreach($chart_horario_data as $index => $ch): ?>
          <div class="col-md-4">
            <div class="card h-100 border-0 bg-light shadow-sm" id="card-horario-<?= $index ?>">
              <div class="card-body p-3 position-relative">
                <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 border" onclick="exportSingleCard('card-horario-<?= $index ?>', 'Horario_<?= preg_replace('/\s+/', '_', $ch['nombre']) ?>')" title="Exportar Tarjeta"><i class="bi bi-download text-primary"></i></button>
                <h6 class="card-title fw-bold text-dark pe-4" title="<?= $ch['nombre'] ?>"><?= $ch['nombre'] ?></h6>
                <div class="d-flex align-items-center mb-2">
                  <h3 class="text-primary fw-bold mb-0 me-2"><?= number_format($ch['cantidad']) ?></h3>
                  <small class="text-muted lh-1">Asistencias<br>Totales</small>
                </div>
                
                <!-- Botón Ver Más -->
                <?php if(!empty($ch['detalles'])): ?>
                  <button class="btn btn-sm btn-outline-secondary w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAsistentes<?= $index ?>" aria-expanded="false" aria-controls="collapseAsistentes<?= $index ?>">
                    <i class="bi bi-list-ul"></i> Ver Asistentes
                  </button>
                  <div class="collapse mt-2 text-start" id="collapseAsistentes<?= $index ?>">
                    <div class="card card-body p-2 bg-white border-0" style="max-height: 200px; overflow-y: auto;" id="listaAsistentes<?= $index ?>">
                      <ul class="list-unstyled mb-0 small">
                        <?php 
                          $asistentes = explode('|', $ch['detalles']);
                          foreach($asistentes as $ast) {
                              echo "<li class='border-bottom py-2 text-muted'><i class='bi bi-person-check text-success me-1'></i>" . htmlspecialchars($ast) . "</li>";
                          }
                        ?>
                      </ul>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Tarjetas por Periodo (Mes) -->
    <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2">Asistencias por Periodo (Mes)</h6>
    <div class="row g-3">
      <?php if(empty($cards_periodo_data)): ?>
        <div class="col-12"><p class="text-muted small">No hay datos para mostrar.</p></div>
      <?php else: ?>
        <?php foreach($cards_periodo_data as $idx => $cp): ?>
          <div class="col-md-3">
            <div class="card h-100 border-0 bg-light shadow-sm position-relative" id="card-periodo-<?= $idx ?>">
              <div class="card-body p-3">
                <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 border" onclick="exportSingleCard('card-periodo-<?= $idx ?>', 'Mes_<?= $cp['periodo'] ?>')" title="Exportar Tarjeta"><i class="bi bi-download text-success"></i></button>
                <h6 class="card-title fw-bold text-dark mb-3 pe-4 text-center"><?= date('F Y', strtotime($cp['periodo'].'-01')) ?></h6>
                <div class="d-flex justify-content-between mb-1">
                  <span class="small text-muted">Presentes:</span>
                  <span class="small fw-bold text-success"><?= number_format($cp['presentes']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                  <span class="small text-muted">Ausentes:</span>
                  <span class="small fw-bold text-danger"><?= number_format($cp['ausentes']) ?></span>
                </div>
                <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                  <span class="small fw-bold">Total:</span>
                  <span class="small fw-bold"><?= number_format($cp['total']) ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- Charts & Alerts System -->
  <div class="row g-4 mb-4">
    <!-- Chart: Trend -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="fw-bold mb-0">Evolución de Asistencia</h5>
        </div>
        <div class="card-body">
          <div class="chart-container">
            <canvas id="trendChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Alerts / Top Classes -->
    <div class="col-lg-4">
      <!-- Chart: Horarios -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="fw-bold mb-0">Top Horarios</h5>
        </div>
        <div class="card-body">
           <div class="chart-container" style="height: 200px;">
            <canvas id="horarioChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Alert: Dropouts -->
      <?php if(!empty($alertas_desercion)): ?>
      <div class="card border-0 shadow-sm border-start border-4 border-danger">
        <div class="card-header bg-white border-0 py-3">
          <h6 class="fw-bold text-danger mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> Riesgo de Deserción</h6>
          <small class="text-muted">> 3 faltas en periodo</small>
        </div>
        <div class="list-group list-group-flush">
          <?php foreach($alertas_desercion as $alerta): ?>
          <div class="list-group-item d-flex align-items-center justify-content-between px-3">
            <div class="d-flex align-items-center">
              <?php $foto = !empty($alerta['foto_nombre']) ? "../../assets/uploads/fotos/".$alerta['foto_nombre'] : "https://ui-avatars.com/api/?name=".$alerta['nombre_completo']."&background=random"; ?>
              <img src="<?= $foto ?>" class="avatar-sm me-2">
              <div>
                <small class="fw-bold d-block"><?= substr($alerta['nombre_completo'], 0, 18) ?>..</small>
                <small class="text-muted" style="font-size: 0.7rem;"><?= $alerta['celular'] ?></small>
              </div>
            </div>
            <span class="badge bg-danger rounded-pill"><?= $alerta['faltas'] ?> Faltas</span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Detailed Table -->
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between">
      <h5 class="fw-bold mb-0">Detalle de Registros</h5>
      <button class="btn btn-outline-success btn-sm rounded-pill px-3" onclick="exportTableToCSV('asistencia.csv')"><i class="bi bi-download"></i> Exportar CSV</button>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Fecha</th>
            <th>Alumno</th>
            <th>Horario</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($registros as $reg): ?>
          <tr>
            <td><?= date('d/M', strtotime($reg['fecha'])) ?></td>
            <td class="fw-bold"><?= $reg['alumno'] ?> <br> <small class="text-muted fw-normal"><?= $reg['documento'] ?></small></td>
            <td><span class="badge bg-light text-dark border"><?= $reg['horario'] ?></span></td>
            <td>
              <?php if($reg['estado']=='presente'): ?>
                <span class="badge bg-success-subtle text-success">Presente</span>
              <?php else: ?>
                <span class="badge bg-danger-subtle text-danger">Ausente</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- Chart Config -->
<script>
  // Utils
  const colors = { primary: '#4e73df', success: '#1cc88a', danger: '#e74a3b', info: '#36b9cc' };

  // Trend Chart
  new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
      labels: <?= $json_trend_label ?>,
      datasets: [{
        label: 'Asistencia Diaria',
        data: <?= $json_trend_data ?>,
        borderColor: colors.primary,
        backgroundColor: 'rgba(78, 115, 223, 0.05)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });

  // Schedule Chart
  new Chart(document.getElementById('horarioChart'), {
    type: 'bar',
    data: {
      labels: <?= $json_horarios_label ?>,
      datasets: [{
        label: 'Asistentes',
        data: <?= $json_horarios_data ?>,
        backgroundColor: [colors.info, colors.primary, colors.success, colors.danger],
        borderRadius: 5
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: 'y', // Horizontal bars
      plugins: { legend: { display: false } }
    }
  });

  // Export CSV
  function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table tr");
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        for (var j = 0; j < cols.length; j++) 
            row.push('"' + cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ") + '"');
        csv.push(row.join(","));
    }
    var csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
    var downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
  }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Export to PNG with html2canvas (Individual Cards) -->
  <script>
  function exportSingleCard(cardId, fileNamePrefix) {
    const targetElement = document.getElementById(cardId);
    if(!targetElement) return;

    // Expandir temporarmente si hay "Ver más"
    const collapseEl = targetElement.querySelector('.collapse');
    const scrollEl = targetElement.querySelector('.card-body.p-2.bg-white'); // El div con max-height
    let wasCollapsed = true;
    
    if (collapseEl) {
        wasCollapsed = !collapseEl.classList.contains('show');
        if (wasCollapsed) {
            collapseEl.classList.add('show');
        }
        if (scrollEl) {
            scrollEl.style.maxHeight = 'none'; // Quitar límite de altura
            scrollEl.style.overflowY = 'visible'; // Quitar el scrollbar
        }
    }

    // Configurar clase especial para estilos de exportación (opcional, p. ej. forzar bg blanco)
    targetElement.classList.add('export-mode');

    setTimeout(() => {
        html2canvas(targetElement, {
          scale: 2, 
          useCORS: true,
          backgroundColor: '#ffffff',
          windowHeight: targetElement.scrollHeight,
          scrollY: -window.scrollY // Previene cortes si la página está con scroll
        }).then(canvas => {
          const link = document.createElement('a');
          const dateStr = new Date().toISOString().split('T')[0];
          link.download = `${fileNamePrefix}_${dateStr}.png`;
          link.href = canvas.toDataURL('image/png');
          link.click();
          
          // Restaurar estilos y estado
          targetElement.classList.remove('export-mode');
          if (collapseEl && scrollEl) {
              scrollEl.style.maxHeight = '200px';
              scrollEl.style.overflowY = 'auto';
              if (wasCollapsed) {
                  collapseEl.classList.remove('show');
              }
          }
        }).catch(err => {
          console.error('Error al generar la imagen:', err);
          alert('Hubo un error al exportar la tarjeta.');
          targetElement.classList.remove('export-mode');
        });
    }, 300);
  }
</script>

</body>
</html>