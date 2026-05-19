<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Matrícula 2026 - Club Deportivo SIAO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #E7F9FD 0%, #d4f1f9 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .card-validacion {
      background: white;
      border-radius: 24px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
      max-width: 550px;
      margin: 0 auto;
    }
    .btn-primary {
      background-color: #FF007F;
      border: none;
      padding: 14px 40px;
      font-size: 1.1rem;
      border-radius: 30px;
      transition: all 0.3s;
    }
    .btn-primary:hover {
      background-color: #e00070;
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(255,0,127,0.3);
    }
    .logo-container {
      max-width: 180px;
      margin: 0 auto 2rem;
    }
    .input-documento {
      padding: 15px 20px;
      font-size: 1.2rem;
      border: 2px solid #ddd;
      border-radius: 12px;
      text-align: center;
      letter-spacing: 1px;
    }
    .input-documento:focus {
      border-color: #FF007F;
      box-shadow: 0 0 0 0.2rem rgba(255,0,127,0.15);
    }
    .info-box {
      background: #f8f9fa;
      border-left: 4px solid #FF007F;
      padding: 1rem;
      border-radius: 8px;
      margin-top: 1.5rem;
    }
    .spinner-border-custom {
      width: 1.5rem;
      height: 1.5rem;
      border-width: 0.2em;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="card-validacion p-5 mt-4">
      
      <!-- Logo -->
      <div class="logo-container">
        <img src="logo_final.png" alt="Logo Club SIAO" class="img-fluid">
      </div>

      <!-- Título -->
      <h2 class="text-center mb-2 fw-bold" style="color: #033884;">Matrícula 2026</h2>
      <p class="text-center text-muted mb-4">Club Deportivo SIAO</p>

      <!-- Instrucciones -->
      <div class="alert alert-info border-0" style="background-color: #e8f4f8;">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Ingresa tu documento de identidad</strong> para verificar si ya estás registrado o iniciar tu inscripción.
      </div>

      <!-- Formulario -->
      <form id="formValidacion">
        <div class="mb-4">
          <label class="form-label fw-semibold" style="color: #033884;">
            <i class="bi bi-card-text me-2"></i>Documento de Identidad
          </label>
          <input 
            type="text" 
            class="form-control input-documento" 
            id="documento" 
            placeholder="Ej: 1234567890"
            required
            pattern="\d{6,}"
            maxlength="15"
            autocomplete="off"
          >
          <div class="invalid-feedback">
            Por favor ingresa un documento válido (mínimo 6 dígitos).
          </div>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary" id="btnContinuar">
            <span id="btnTexto">
              <i class="bi bi-arrow-right-circle me-2"></i>Continuar
            </span>
            <span id="btnLoading" class="d-none">
              <span class="spinner-border spinner-border-custom me-2" role="status"></span>
              Verificando...
            </span>
          </button>
        </div>
      </form>

      <!-- Información adicional -->
      <div class="info-box">
        <h6 class="fw-bold mb-2" style="color: #033884;">
          <i class="bi bi-calendar-check me-2"></i>Renovación 2026
        </h6>
        <ul class="mb-0 small">
          <li><strong>Si ya estás registrado:</strong> Podrás actualizar tus datos y renovar tu matrícula.</li>
          <li><strong>Si eres nuevo:</strong> Completa el formulario de inscripción completo.</li>
        </ul>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('formValidacion').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const form = e.target;
      const documento = document.getElementById('documento').value.trim();
      const btnContinuar = document.getElementById('btnContinuar');
      const btnTexto = document.getElementById('btnTexto');
      const btnLoading = document.getElementById('btnLoading');
      const inputDocumento = document.getElementById('documento');

      // Validación básica
      if (!/^\d{6,}$/.test(documento)) {
        inputDocumento.classList.add('is-invalid');
        return;
      }
      
      inputDocumento.classList.remove('is-invalid');
      
      // Mostrar loading
      btnContinuar.disabled = true;
      btnTexto.classList.add('d-none');
      btnLoading.classList.remove('d-none');

      try {
        // Llamada AJAX al servidor
        const response = await fetch('/APP/controllers/verificar_documento.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'documento=' + encodeURIComponent(documento)
        });

        const data = await response.json();

        if (data.existe) {
          // Redirigir a formulario de actualización
          window.location.href = 'actualizar_inscripcion.php?doc=' + encodeURIComponent(documento);
        } else {
          // Redirigir a formulario de nueva inscripción
          window.location.href = 'matricula_public.php?nuevo=1&doc=' + encodeURIComponent(documento);
        }

      } catch (error) {
        console.error('Error:', error);
        alert('Ocurrió un error al verificar el documento. Por favor intenta nuevamente.');
        
        // Restaurar botón
        btnContinuar.disabled = false;
        btnTexto.classList.remove('d-none');
        btnLoading.classList.add('d-none');
      }
    });

    // Solo permitir números en el input
    document.getElementById('documento').addEventListener('input', function(e) {
      this.value = this.value.replace(/\D/g, '');
    });
  </script>
</body>
</html>