<?php
require_once 'helpers/auth.php';
verificarSesion();
$nombre = $_SESSION['nombre'];
$rol    = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Principal - SIAO</title>
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
      --card-bg: rgba(255, 255, 255, 0.95);
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
    }
    /* Header Profile */
    .profile-card {
      background: white;
      border-radius: 20px;
      padding: 1.5rem 2rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
      margin-bottom: 2.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 4px solid var(--primary-color);
      flex-wrap: wrap;
      gap: 1rem;
    }
    .user-info {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }
    .user-avatar {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      object-fit: contain;
      border: 3px solid #e9ecef;
      padding: 2px;
      background: white;
    }
    .welcome-text h2 {
      margin: 0;
      font-weight: 700;
      color: #2c3e50;
      font-size: 1.5rem;
    }
    .role-badge {
      background: rgba(13, 110, 253, 0.1);
      color: var(--primary-color);
      padding: 0.25rem 0.8rem;
      border-radius: 30px;
      font-size: 0.85rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    /* Access Cards */
    .access-card {
      background: var(--card-bg);
      border-radius: 24px;
      border: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      height: 100%;
      text-decoration: none;
      color: inherit;
      position: relative;
      overflow: hidden;
      display: block;
      box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .access-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(13, 110, 253, 0.15);
    }
    .access-card .card-body {
      padding: 2.5rem 1.5rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 1;
      position: relative;
    }
    .icon-wrapper {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.5rem;
      font-size: 2.5rem;
      transition: all 0.3s ease;
    }
    /* Card Variants */
    .card-afiliados .icon-wrapper { background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); color: #6a11cb; }
    .card-instructores .icon-wrapper { background: linear-gradient(135deg, #cfd9df 0%, #e2ebf0 100%); color: #2c3e50; }
    .card-asistencia .icon-wrapper { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); color: #d35400; }
    .card-historial .icon-wrapper { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); color: #0083b0; }
    .card-contabilidad .icon-wrapper { background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%); color: #2096ff; }
    
    .access-card:hover .icon-wrapper {
      transform: scale(1.1) rotate(5deg);
    }
    
    .card-title {
      font-weight: 700;
      font-size: 1.25rem;
      color: #2c3e50;
      margin-bottom: 0.5rem;
    }
    .card-desc {
      color: #7f8c8d;
      font-size: 0.9rem;
      font-weight: 400;
    }
    
    .btn-logout {
      border-radius: 50px;
      padding: 0.5rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s;
    }
    .btn-logout:hover {
      background-color: #dc3545;
      color: white;
      border-color: #dc3545;
    }
  </style>
</head>
<body>
  <div class="container main-container py-4">
    
    <!-- Profile Header -->
    <div class="profile-card animate-fade-in">
      <div class="user-info">
        <img src="logo_final.png" alt="Logo" class="user-avatar">
        <div class="welcome-text">
          <h2>Hola, <?= htmlspecialchars($nombre) ?></h2>
          <span class="role-badge"><?= $rol ?></span>
        </div>
      </div>
      <div class="d-flex gap-2">
        <a href="views/auth/cambio_password.php" class="btn btn-outline-primary btn-logout">
          <i class="bi bi-key-fill me-2"></i>Cambiar Contraseña
        </a>
        <a href="logout.php" class="btn btn-outline-danger btn-logout">
          <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
        </a>
      </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="row g-4">
      
      <?php if ($rol === 'admin'): ?>
        <!-- Card: Afiliados -->
        <div class="col-12 col-md-6 col-lg-4">
          <a href="views/afiliados/index.php" class="access-card card-afiliados">
            <div class="card-body">
              <div class="icon-wrapper">
                <i class="bi bi-people-fill"></i>
              </div>
              <h3 class="card-title">Afiliados</h3>
              <p class="card-desc">Gestionar deportistas y matrículas</p>
            </div>
          </a>
        </div>

        <!-- Card: Instructores -->
        <div class="col-12 col-md-6 col-lg-4">
          <a href="views/instructores/index.php" class="access-card card-instructores">
            <div class="card-body">
              <div class="icon-wrapper">
                <i class="bi bi-person-badge-fill"></i>
              </div>
              <h3 class="card-title">Instructores</h3>
              <p class="card-desc">Administrar staff y permisos</p>
            </div>
          </a>
        </div>

        <!-- Card: Asistencia -->
        <div class="col-12 col-md-6 col-lg-4">
          <a href="views/asistencia/index.php" class="access-card card-asistencia">
            <div class="card-body">
              <div class="icon-wrapper">
                <i class="bi bi-calendar-check-fill"></i>
              </div>
              <h3 class="card-title">Asistencia</h3>
              <p class="card-desc">Control de clases y alumnos</p>
            </div>
          </a>
        </div>

        <!-- Card: Historial -->
        <div class="col-12 col-md-6 col-lg-4">
          <a href="views/asistencia/consultar.php" class="access-card card-historial">
            <div class="card-body">
              <div class="icon-wrapper">
                <i class="bi bi-clock-history"></i>
              </div>
              <h3 class="card-title">Historial</h3>
              <p class="card-desc">Consultas y registros históricos</p>
            </div>
          </a>
        </div>

        <!-- Card: Contabilidad -->
        <div class="col-12 col-md-6 col-lg-4">
          <a href="controllers/contabilidad_controller.php" class="access-card card-contabilidad">
            <div class="card-body">
              <div class="icon-wrapper">
                <i class="bi bi-wallet2"></i>
              </div>
              <h3 class="card-title">Contabilidad</h3>
              <p class="card-desc">Pagos, ingresos y reportes</p>
            </div>
          </a>
        </div>

      <?php elseif ($rol === 'instructor'): ?>
        
        <!-- Instructor View -->
        <div class="col-12 col-md-6 col-lg-4">
          <a href="views/asistencia/index.php" class="access-card card-asistencia">
            <div class="card-body">
              <div class="icon-wrapper">
                <i class="bi bi-check2-circle"></i>
              </div>
              <h3 class="card-title">Tomar Asistencia</h3>
              <p class="card-desc">Registrar asistencia de clases</p>
            </div>
          </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <a href="views/asistencia/consultar.php" class="access-card card-historial">
            <div class="card-body">
              <div class="icon-wrapper">
                <i class="bi bi-clock-history"></i>
              </div>
              <h3 class="card-title">Historial</h3>
              <p class="card-desc">Ver mis registros y clases</p>
            </div>
          </a>
        </div>

      <?php endif; ?>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
