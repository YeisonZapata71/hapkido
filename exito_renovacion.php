<?php
$nombre = $_GET['nombre'] ?? 'Deportista';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Renovación Exitosa - Club SIAO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #E7F9FD 0%, #d4f1f9 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .success-card {
      background: white;
      border-radius: 24px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
      max-width: 600px;
      margin: 0 auto;
      padding: 3rem;
    }
    .check-icon {
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 2rem;
      animation: scaleIn 0.5s ease-out;
    }
    @keyframes scaleIn {
      from {
        transform: scale(0);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }
    .btn-primary {
      background-color: #FF007F;
      border: none;
      padding: 12px 30px;
      border-radius: 30px;
      transition: all 0.3s;
    }
    .btn-primary:hover {
      background-color: #e00070;
      transform: translateY(-2px);
    }
    .info-box {
      background: #f0fdf4;
      border-left: 4px solid #10b981;
      padding: 1.5rem;
      border-radius: 8px;
      margin-top: 2rem;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="success-card">
      
      <!-- Ícono de éxito -->
      <div class="check-icon">
        <i class="bi bi-check-lg text-white" style="font-size: 3rem;"></i>
      </div>

      <!-- Título -->
      <h2 class="text-center fw-bold mb-3" style="color: #033884;">
        ¡Renovación Exitosa!
      </h2>
      
      <p class="text-center text-muted fs-5 mb-4">
        <?= htmlspecialchars($nombre) ?>, tus datos han sido actualizados correctamente.
      </p>

      <!-- Información de pago -->
      <div class="info-box">
        <h5 class="fw-bold mb-3" style="color: #10b981;">
          <i class="bi bi-credit-card me-2"></i>Siguiente Paso: Realizar el Pago
        </h5>
        <p class="mb-2">Para completar tu renovación 2026, debes realizar la consignación correspondiente:</p>
        <ul class="mb-3">
          <li><strong>Concepto:</strong> Matrícula Anual 2026</li>
          <li><strong>Valor:</strong> Consulta con el club</li>
          <li><strong>Cuenta:</strong> llave BreB 300 400 3277</li>
        </ul>
        <p class="mb-0 small text-muted">
          <i class="bi bi-info-circle me-1"></i>
          Una vez realizado el pago, envía el comprobante al WhatsApp 3232897785 para activar tu matrícula.
        </p>
      </div>

      <!-- Botones de acción -->
      <div class="text-center mt-4">
        <a href="index.php" class="btn btn-primary">
          <i class="bi bi-house-door me-2"></i>Volver al Inicio
        </a>
      </div>

      <!-- Información de contacto -->
      <div class="text-center mt-4 pt-3 border-top">
        <p class="small text-muted mb-1">
          <i class="bi bi-whatsapp me-1"></i>
          ¿Dudas? Contáctanos por WhatsApp
        </p>
        <p class="small text-muted mb-0">
          <i class="bi bi-envelope me-1"></i>
          o envíanos un correo
        </p>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>