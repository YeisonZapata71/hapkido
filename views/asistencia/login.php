<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ingreso al sistema - SIAO</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="min-width: 340px; max-width: 370px;">
      <div class="text-center mb-3">
        <img src="https://app.siaooficial.com/APP/logo_final.png" alt="Logo SIAO" style="height: 64px;">
      </div>
      <h5 class="text-center mb-3">Ingreso al Sistema</h5>
      <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger text-center py-2"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>
      <form action="controllers/auth_controller.php" method="POST" autocomplete="off">
        <div class="mb-3">
          <label class="form-label">Correo</label>
          <input type="email" name="correo" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100 mb-2">Ingresar</button>
      </form>
      <small class="text-muted text-center d-block">Club Deportivo SIAO &copy; 2025</small>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
