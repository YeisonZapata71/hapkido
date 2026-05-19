<?php
require_once '../../helpers/auth.php';
verificarSesion();
verificarRol('admin');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Afiliados - Panel Admin SIAO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .stats-card {
      border-radius: 15px;
      border: none;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }
    
    .stats-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
      pointer-events: none;
    }
    
    .stats-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }
    
    .stats-card.card-total {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }
    
    .stats-card.card-nuevos {
      background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
      color: white;
    }
    
    .stats-card.card-actualizados {
      background: linear-gradient(135deg, #198754 0%, #146c43 100%);
      color: white;
    }
    
    .stats-card.card-sin-actualizar {
      background: linear-gradient(135deg, #fd7e14 0%, #dc6502 100%);
      color: white;
    }
    
    .stats-number {
      font-size: 2.5rem;
      font-weight: bold;
      margin: 10px 0;
    }
    
    .stats-icon {
      font-size: 3rem;
      opacity: 0.3;
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
    }
    
    .nav-tabs .nav-link {
      border: none;
      color: #6c757d;
      font-weight: 500;
      padding: 12px 24px;
      transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover {
      color: #0d6efd;
      background: rgba(13, 110, 253, 0.1);
    }
    
    .nav-tabs .nav-link.active {
      color: #0d6efd;
      background: white;
      border-bottom: 3px solid #0d6efd;
    }
    
    .badge-nuevo {
      background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.7rem;
      font-weight: bold;
    }
    
    .badge-alerta {
      background: linear-gradient(135deg, #fd7e14 0%, #dc6502 100%);
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.7rem;
      font-weight: bold;
    }
    
    .table-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    
    .table thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }
    
    .table tbody tr {
      transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
      background: rgba(102, 126, 234, 0.05);
      transform: scale(1.01);
    }
    
    .foto-mini {
      width: 48px;
      height: 48px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #e4e8ef;
      background: #fff;
      transition: all 0.3s ease;
    }
    
    .foto-mini:hover {
      transform: scale(1.2);
      border-color: #667eea;
    }
    
    .btn-action {
      margin: 2px;
      transition: all 0.2s ease;
    }
    
    .btn-action:hover {
      transform: scale(1.1);
    }
    
    .filter-section {
      background: white;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    
    .loading-spinner {
      display: none;
      text-align: center;
      padding: 40px;
    }
    
    .header-section {
      background: white;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }
  </style>
</head>
<body>
  <div class="container-fluid py-4">
    
    <!-- Header -->
    <div class="header-section">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h3 class="mb-1"><i class="bi bi-people-fill text-primary"></i> Gestión de Afiliados</h3>
          <p class="text-muted mb-0">Administra y visualiza todos los deportistas registrados</p>
        </div>
        <div>
          <a href="../../dashboard.php" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i> Volver
          </a>
          <a href="../../index.php" class="btn btn-success">
            <i class="bi bi-person-plus-fill"></i> Nuevo afiliado
          </a>
        </div>
      </div>
    </div>

    <!-- Mensajes de éxito/error -->
    <?php if(isset($_GET['msg'])): 
    $msg = $_GET['msg'];
    $texto = $msg=="eliminado" ? "Afiliado eliminado correctamente." :
          ($msg=="actualizado" ? "Afiliado actualizado correctamente." :
          ($msg=="creado" ? "Afiliado registrado correctamente." : ""));
    $tipo = $msg=="eliminado" ? "danger" : "success";
    ?>
    <div class="alert alert-<?= $tipo ?> alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle-fill"></i> <?= $texto ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Tarjetas de Estadísticas -->
    <div class="row g-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card card-total" onclick="filtrarCategoria('todos')">
          <div class="card-body position-relative">
            <h6 class="text-uppercase mb-0" style="font-size: 0.85rem;">Total Afiliados</h6>
            <div class="stats-number" id="stat-total">0</div>
            <small>Deportistas activos</small>
            <i class="bi bi-people-fill stats-icon"></i>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card card-nuevos" onclick="filtrarCategoria('nuevos')">
          <div class="card-body position-relative">
            <h6 class="text-uppercase mb-0" style="font-size: 0.85rem;">Nuevas Inscripciones</h6>
            <div class="stats-number" id="stat-nuevos">0</div>
            <small>Año <?= date('Y') ?></small>
            <i class="bi bi-star-fill stats-icon"></i>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card card-actualizados" onclick="filtrarCategoria('actualizados')">
          <div class="card-body position-relative">
            <h6 class="text-uppercase mb-0" style="font-size: 0.85rem;">Actualizaciones Recientes</h6>
            <div class="stats-number" id="stat-actualizados">0</div>
            <small>Últimos 30 días</small>
            <i class="bi bi-arrow-repeat stats-icon"></i>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card card-sin-actualizar" onclick="filtrarCategoria('sin_actualizar')">
          <div class="card-body position-relative">
            <h6 class="text-uppercase mb-0" style="font-size: 0.85rem;">Sin Actualizar</h6>
            <div class="stats-number" id="stat-sin-actualizar">0</div>
            <small>Más de 2 meses</small>
            <i class="bi bi-exclamation-triangle-fill stats-icon"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtros Avanzados -->
    <div class="filter-section">
      <h5 class="mb-3"><i class="bi bi-funnel-fill text-primary"></i> Filtros Avanzados</h5>
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label small fw-bold">Buscar</label>
          <input type="text" class="form-control" id="busqueda" placeholder="Nombre, documento, correo...">
        </div>
        <div class="col-md-2">
          <label class="form-label small fw-bold">Estado de Pago</label>
          <select class="form-select" id="estado_pago">
            <option value="">Todos</option>
            <option value="pendiente">Pendiente</option>
            <option value="pagado">Pagado</option>
            <option value="vencido">Vencido</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-bold">Horario</label>
          <input type="text" class="form-control" id="horario" placeholder="Ej: Lunes">
        </div>
        <div class="col-md-2">
          <label class="form-label small fw-bold">Grado Cinturón</label>
          <input type="text" class="form-control" id="grado_cinturon" placeholder="Ej: Blanco">
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button class="btn btn-outline-danger w-100" onclick="limpiarFiltros()">
            <i class="bi bi-x-circle"></i> Limpiar
          </button>
        </div>
      </div>
    </div>

    <!-- Pestañas -->
    <ul class="nav nav-tabs mb-3" id="categoriaTabs">
      <li class="nav-item">
        <a class="nav-link active" data-categoria="todos" href="#" onclick="cambiarTab(event, 'todos')">
          <i class="bi bi-grid-fill"></i> Todos <span class="badge bg-secondary ms-1" id="count-todos">0</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-categoria="nuevos" href="#" onclick="cambiarTab(event, 'nuevos')">
          <i class="bi bi-star-fill"></i> Nuevas Inscripciones <span class="badge bg-primary ms-1" id="count-nuevos">0</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-categoria="actualizados" href="#" onclick="cambiarTab(event, 'actualizados')">
          <i class="bi bi-arrow-repeat"></i> Actualizados <span class="badge bg-success ms-1" id="count-actualizados">0</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-categoria="sin_actualizar" href="#" onclick="cambiarTab(event, 'sin_actualizar')">
          <i class="bi bi-exclamation-triangle-fill"></i> Sin Actualizar <span class="badge bg-warning ms-1" id="count-sin-actualizar">0</span>
        </a>
      </li>
    </ul>

    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loading">
      <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Cargando...</span>
      </div>
      <p class="mt-3 text-muted">Cargando datos...</p>
    </div>

    <!-- Tabla de Afiliados -->
    <div class="table-container" id="tabla-container">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Foto</th>
              <th>Nombre</th>
              <th>Documento</th>
              <th>Horario</th>
              <th>Teléfono</th>
              <th>Correo</th>
              <th>Estado Pago</th>
              <th>Estado</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody id="tabla-body">
            <!-- Los datos se cargarán dinámicamente con JavaScript -->
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let categoriaActual = 'todos';
    
    // Cargar datos al iniciar
    document.addEventListener('DOMContentLoaded', function() {
      cargarDatos();
      
      // Event listeners para filtros
      document.getElementById('busqueda').addEventListener('input', debounce(cargarDatos, 500));
      document.getElementById('estado_pago').addEventListener('change', cargarDatos);
      document.getElementById('horario').addEventListener('input', debounce(cargarDatos, 500));
      document.getElementById('grado_cinturon').addEventListener('input', debounce(cargarDatos, 500));
    });
    
    function cargarDatos() {
      const loading = document.getElementById('loading');
      const tablaContainer = document.getElementById('tabla-container');
      
      loading.style.display = 'block';
      tablaContainer.style.display = 'none';
      
      const params = new URLSearchParams({
        categoria: categoriaActual,
        estado_pago: document.getElementById('estado_pago').value,
        horario: document.getElementById('horario').value,
        grado_cinturon: document.getElementById('grado_cinturon').value,
        busqueda: document.getElementById('busqueda').value
      });
      
      fetch(`../../controllers/get_afiliados_stats.php?${params}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            actualizarEstadisticas(data.estadisticas);
            actualizarTabla(data.afiliados);
          } else {
            console.error('Error:', data.error);
            alert('Error al cargar los datos: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error de conexión al cargar los datos');
        })
        .finally(() => {
          loading.style.display = 'none';
          tablaContainer.style.display = 'block';
        });
    }
    
    function actualizarEstadisticas(stats) {
      document.getElementById('stat-total').textContent = stats.total;
      document.getElementById('stat-nuevos').textContent = stats.nuevos;
      document.getElementById('stat-actualizados').textContent = stats.actualizados;
      document.getElementById('stat-sin-actualizar').textContent = stats.sin_actualizar;
      
      document.getElementById('count-todos').textContent = stats.total;
      document.getElementById('count-nuevos').textContent = stats.nuevos;
      document.getElementById('count-actualizados').textContent = stats.actualizados;
      document.getElementById('count-sin-actualizar').textContent = stats.sin_actualizar;
    }
    
    function actualizarTabla(afiliados) {
      const tbody = document.getElementById('tabla-body');
      
      if (afiliados.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="10" class="text-center py-5">
              <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
              <p class="text-muted mt-3">No se encontraron afiliados con los filtros seleccionados</p>
            </td>
          </tr>
        `;
        return;
      }
      
      tbody.innerHTML = afiliados.map((afiliado, index) => {
        const fotoHtml = afiliado.foto_nombre 
          ? `<img src="../../assets/uploads/fotos/${afiliado.foto_nombre}" alt="Foto" class="foto-mini">`
          : '<span class="text-muted small">Sin foto</span>';
        
        const estadoPagoClass = {
          'pendiente': 'warning',
          'pagado': 'success',
          'vencido': 'danger'
        }[afiliado.estado_pago] || 'secondary';
        
        const estadoPagoText = afiliado.estado_pago ? afiliado.estado_pago.charAt(0).toUpperCase() + afiliado.estado_pago.slice(1) : 'N/A';
        
        const badgeNuevo = afiliado.es_nuevo 
          ? '<span class="badge badge-nuevo ms-1">NUEVO</span>' 
          : '';
        
        const badgeAlerta = afiliado.necesita_actualizacion 
          ? '<span class="badge badge-alerta ms-1"><i class="bi bi-exclamation-triangle-fill"></i> Actualizar</span>' 
          : '';
        
        return `
          <tr>
            <td>${index + 1}</td>
            <td>${fotoHtml}</td>
            <td>
              ${escapeHtml(afiliado.nombre_completo)}
              ${badgeNuevo}
            </td>
            <td>${escapeHtml(afiliado.documento)}</td>
            <td><small>${escapeHtml(afiliado.horario || 'N/A')}</small></td>
            <td>${escapeHtml(afiliado.celular)}</td>
            <td><small>${escapeHtml(afiliado.correo)}</small></td>
            <td>
              <span class="badge bg-${estadoPagoClass}">${estadoPagoText}</span>
            </td>
            <td>${badgeAlerta || '<span class="text-success small">✓ Al día</span>'}</td>
            <td class="text-center">
              <a href="ver.php?id=${afiliado.id}" class="btn btn-outline-info btn-sm btn-action" title="Ver detalle">
                <i class="bi bi-eye"></i>
              </a>
              <a href="editar.php?id=${afiliado.id}" class="btn btn-outline-primary btn-sm btn-action" title="Editar">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="eliminar.php?id=${afiliado.id}" class="btn btn-outline-danger btn-sm btn-action" 
                 onclick="return confirm('¿Seguro de eliminar a ${escapeHtml(afiliado.nombre_completo)}?')" title="Eliminar">
                <i class="bi bi-trash"></i>
              </a>
              <a href="carnet.php?id=${afiliado.id}" class="btn btn-outline-secondary btn-sm btn-action" title="Generar carné">
                <i class="bi bi-person-vcard"></i>
              </a>
            </td>
          </tr>
        `;
      }).join('');
    }
    
    function cambiarTab(event, categoria) {
      event.preventDefault();
      
      // Actualizar tabs activos
      document.querySelectorAll('#categoriaTabs .nav-link').forEach(link => {
        link.classList.remove('active');
      });
      event.target.classList.add('active');
      
      categoriaActual = categoria;
      cargarDatos();
    }
    
    function filtrarCategoria(categoria) {
      categoriaActual = categoria;
      
      // Actualizar tab activo
      document.querySelectorAll('#categoriaTabs .nav-link').forEach(link => {
        link.classList.remove('active');
        if (link.dataset.categoria === categoria) {
          link.classList.add('active');
        }
      });
      
      cargarDatos();
    }
    
    function limpiarFiltros() {
      document.getElementById('busqueda').value = '';
      document.getElementById('estado_pago').value = '';
      document.getElementById('horario').value = '';
      document.getElementById('grado_cinturon').value = '';
      cargarDatos();
    }
    
    function escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return text ? text.replace(/[&<>"']/g, m => map[m]) : '';
    }
    
    function debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }
  </script>
</body>
</html>
