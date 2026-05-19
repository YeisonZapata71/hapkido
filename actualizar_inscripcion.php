<?php
// Verificar que viene el documento
if (!isset($_GET['doc']) || empty($_GET['doc'])) {
    header('Location: index.php');
    exit;
}

$documento = trim($_GET['doc']);

// ====== CONFIGURACIÓN DE BASE DE DATOS ======
$host = 'localhost';
$dbname = 'siao_formulario';
$username = 'siao_siao';  
$password = 'Sicau2025**';  

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

try {
    $stmt = $pdo->prepare("SELECT * FROM afiliados_siao WHERE documento = ? LIMIT 1");
    $stmt->execute([$documento]);
    $deportista = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$deportista) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error al cargar datos: " . $e->getMessage());
}

// Procesar horarios si existen
$horariosSeleccionados = [];
if (!empty($deportista['horario'])) {
    $horariosSeleccionados = explode(',', $deportista['horario']);
    $horariosSeleccionados = array_map('trim', $horariosSeleccionados);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .shadow-card {
      box-shadow: 0 4px 16px 0 rgba(20,60,100,0.08);
      border-radius: 1.2rem;
    }
    .badge-renovacion {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 8px 20px;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 600;
    }
    .campo-bloqueado {
      background-color: #f5f5f5 !important;
      cursor: not-allowed;
    }
    .form-label {
      color: #033884;
      font-weight: 600;
    }
    .form-control, .form-select {
      border-radius: 8px;
      border-color: #bdbdbe;
    }
    .btn-primary {
      background-color: #FF007F;
      border: none;
      padding: 12px 30px;
      font-size: 1.1rem;
      border-radius: 30px;
    }
    .btn-primary:hover {
      background-color: #e00070;
    }
    .alert-info-custom {
      background: linear-gradient(135deg, #e8f4f8 0%, #d4f1f9 100%);
      border-left: 4px solid #0dcaf0;
      border-radius: 8px;
    }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Renovación Matrícula 2026 - Club Deportivo SIAO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function validarFormulario(e) {
      const celular = document.getElementById("celular");
      const correo = document.getElementById("correo");
      const nombreAcudiente = document.getElementById("nombreAcudiente");
      const acepto = document.getElementById("acepto");

      const soloLetras = /^[a-zA-ZÀ-ÿ\s]+$/;
      const soloNumeros = /^\d+$/;

      // Validar celular
      if (!soloNumeros.test(celular.value) || celular.value.length !== 10) {
        alert("El celular debe tener exactamente 10 dígitos numéricos.");
        celular.focus();
        e.preventDefault();
        return false;
      }

      // Validar email
      if (!correo.validity.valid) {
        alert("Por favor ingresa un correo electrónico válido.");
        correo.focus();
        e.preventDefault();
        return false;
      }

      // Validar acudiente si es requerido
      if (nombreAcudiente.required && !soloLetras.test(nombreAcudiente.value)) {
        alert("El nombre del acudiente solo debe contener letras y espacios.");
        nombreAcudiente.focus();
        e.preventDefault();
        return false;
      }

      // Términos y condiciones
      if (!acepto.checked) {
        alert("Debe aceptar los términos y condiciones para continuar.");
        acepto.focus();
        e.preventDefault();
        return false;
      }

      return true;
    }

    function evaluarEdad() {
      const fecha = document.getElementById("fechaNacimiento").value;
      const campoAcudiente = document.getElementById("campoAcudiente");
      if (!fecha) return;
      
      const nacimiento = new Date(fecha);
      const hoy = new Date();
      let edad = hoy.getFullYear() - nacimiento.getFullYear();
      const m = hoy.getMonth() - nacimiento.getMonth();
      if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
      }
      
      if (edad < 18) {
        campoAcudiente.style.display = "block";
        document.getElementById("nombreAcudiente").required = true;
      } else {
        campoAcudiente.style.display = "none";
        document.getElementById("nombreAcudiente").required = false;
      }
    }

    document.addEventListener("DOMContentLoaded", function() {
      evaluarEdad();
    });
  </script>
</head>
<body style="background-color: #E7F9FD; font-family: 'Segoe UI', sans-serif;">
  <div class="container py-5 d-flex justify-content-center">
    <div class="w-100" style="max-width: 900px; background-color: #ffffff; padding: 40px; border-radius: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
      
      <!-- Logo -->
      <div class="text-center mb-3">
        <img src="logo_final.png" alt="Logo Club SIAO" style="max-width: 180px;">
      </div>

      <!-- Badge de Renovación -->
      <div class="text-center mb-3">
        <span class="badge badge-renovacion">
          <i class="bi bi-arrow-repeat me-2"></i>RENOVACIÓN MATRÍCULA 2026
        </span>
      </div>

      <h2 class="mb-4 text-center">Actualización de Datos</h2>

      <!-- Alerta informativa -->
      <div class="alert alert-info-custom border-0 mb-4">
        <div class="d-flex align-items-start">
          <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
          <div>
            <h6 class="fw-bold mb-2">¡Bienvenido de nuevo, <?php echo htmlspecialchars($deportista['nombre_completo']); ?>!</h6>
            <p class="mb-1 small">Revisa y actualiza tus datos para renovar tu matrícula 2026.</p>
            <p class="mb-0 small"><strong>Nota:</strong> Los campos de documento, fecha de nacimiento y tipo de sangre no se pueden modificar.</p>
          </div>
        </div>
      </div>

      <!-- FORMULARIO -->
      <div class="card shadow-card p-4 bg-white">
        <form action="controllers/actualizar_deportista.php" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario(event)">
          
          <input type="hidden" name="id_deportista" value="<?php echo $deportista['id']; ?>">
          <input type="hidden" name="documento_original" value="<?php echo htmlspecialchars($deportista['documento']); ?>">
          
          <div class="row g-3">
            
            <!-- Nombre completo -->
            <div class="col-md-6">
              <label class="form-label">Nombre completo</label>
              <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($deportista['nombre_completo']); ?>" required>
            </div>

            <!-- Documento (BLOQUEADO) -->
            <div class="col-md-6">
              <label class="form-label">
                Documento <i class="bi bi-lock-fill text-muted"></i>
              </label>
              <input type="text" class="form-control campo-bloqueado" value="<?php echo htmlspecialchars($deportista['documento']); ?>" readonly>
            </div>

            <!-- Fecha de nacimiento (BLOQUEADA) -->
            <div class="col-md-6">
              <label class="form-label">
                Fecha de nacimiento <i class="bi bi-lock-fill text-muted"></i>
              </label>
              <input type="date" class="form-control campo-bloqueado" id="fechaNacimiento" value="<?php echo htmlspecialchars($deportista['fecha_nacimiento']); ?>" readonly>
            </div>

            <!-- Sexo -->
            <div class="col-md-6">
              <label class="form-label">Sexo</label>
              <select class="form-select" name="sexo" required>
                <option value="Masculino" <?php echo $deportista['sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                <option value="Femenino" <?php echo $deportista['sexo'] == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
              </select>
            </div>

            <!-- Celular -->
            <div class="col-md-6">
              <label class="form-label">Celular</label>
              <input type="text" class="form-control" name="celular" id="celular" value="<?php echo htmlspecialchars($deportista['celular']); ?>" placeholder="10 dígitos" required>
            </div>

            <!-- Correo electrónico -->
            <div class="col-md-6">
              <label class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" name="correo" id="correo" value="<?php echo htmlspecialchars($deportista['correo']); ?>" required>
            </div>

            <!-- Dirección -->
            <div class="col-md-6">
              <label class="form-label">Dirección</label>
              <input type="text" class="form-control" name="direccion" value="<?php echo htmlspecialchars($deportista['direccion']); ?>" required>
            </div>

            <!-- Ciudad -->
            <div class="col-md-6">
              <label class="form-label">Ciudad</label>
              <select class="form-select" name="ciudad" required>
                <option value="">Seleccione</option>
                <?php
                $ciudades = ['Barbosa', 'Bello', 'Caldas', 'Copacabana', 'Envigado', 'Girardota', 'Itagüí', 'La Estrella', 'Medellín', 'Sabaneta'];
                foreach ($ciudades as $ciudad) {
                    $selected = ($deportista['ciudad'] == $ciudad) ? 'selected' : '';
                    echo "<option $selected>$ciudad</option>";
                }
                ?>
              </select>
            </div>

            <!-- EPS -->
            <div class="col-md-6">
              <label class="form-label">EPS</label>
              <select class="form-select" name="eps" required>
                <option value="">Seleccione</option>
                <?php
                $eps_list = ['SURA', 'Sanitas', 'Coomeva', 'Compensar', 'Salud Total', 'Nueva EPS', 'Aliansalud', 'Cafesalud', 'Sanidad Militar', 'Magisterio', 'Savia Salud'];
                foreach ($eps_list as $eps) {
                    $selected = ($deportista['eps'] == $eps) ? 'selected' : '';
                    echo "<option $selected>$eps</option>";
                }
                ?>
              </select>
            </div>

            <!-- Tipo de sangre (BLOQUEADO) -->
            <div class="col-md-6">
              <label class="form-label">
                Tipo de sangre <i class="bi bi-lock-fill text-muted"></i>
              </label>
              <input type="text" class="form-control campo-bloqueado" value="<?php echo htmlspecialchars($deportista['tipo_sangre']); ?>" readonly>
            </div>

            <!-- Campo Acudiente -->
            <div class="col-md-6" id="campoAcudiente" style="<?php echo (empty($deportista['nombre_acudiente']) ? 'display:none;' : ''); ?>">
              <label class="form-label">Nombre completo del acudiente</label>
              <input type="text" class="form-control" name="nombre_acudiente" id="nombreAcudiente" value="<?php echo htmlspecialchars($deportista['nombre_acudiente']); ?>">
            </div>

            <!-- Horarios -->
            <div class="col-md-12">
              <label class="form-label">Horarios</label>
              <div class="row g-2">
                <div class="col-md-6">
                  <?php
                  $horarios = [
                    'Lunes, Miércoles y Viernes 06:00 a.m.',
                    'Martes y Jueves 05:00 p.m.',
                    'Miércoles y Viernes 07:30 p.m.'
                  ];
                  foreach ($horarios as $i => $horario) {
                    $checked = in_array($horario, $horariosSeleccionados) ? 'checked' : '';
                    echo "<div class='form-check'>
                      <input class='form-check-input' type='checkbox' name='horarios[]' value='$horario' id='h$i' $checked>
                      <label class='form-check-label' for='h$i'>$horario</label>
                    </div>";
                  }
                  ?>
                </div>
                <div class="col-md-6">
                  <?php
                  $horarios2 = [
                    'Avanzados y Negros',
                    'Sábados 07:00 a.m.',
                    'Sábados 12:00 m',
                    'Sede Robledo',
                    'Taekwondo'
                  ];
                  foreach ($horarios2 as $i => $horario) {
                    $checked = in_array($horario, $horariosSeleccionados) ? 'checked' : '';
                    $idx = $i + 3;
                    echo "<div class='form-check'>
                      <input class='form-check-input' type='checkbox' name='horarios[]' value='$horario' id='h$idx' $checked>
                      <label class='form-check-label' for='h$idx'>$horario</label>
                    </div>";
                  }
                  ?>
                </div>
              </div>
            </div>

            <!-- Grado de cinturón -->
            <div class="col-md-6">
              <label class="form-label">Grado de cinturón</label>
              <select class="form-select" name="grado_cinturon" required>
                <option value="">Seleccione</option>
                <?php
                $grados = ['Blanco', 'Amarillo', 'Naranja', 'Verde', 'Azul', 'Púrpura', 'Rojo', 'Rojo-Marrón', 'Marrón', 'Marrón-Negro', '1th Dan', '2th Dan', '3th Dan', '4th Dan', '5th Dan', '6th Dan', '7th Dan', '8th Dan'];
                foreach ($grados as $grado) {
                    $selected = ($deportista['grado_cinturon'] == $grado) ? 'selected' : '';
                    echo "<option $selected>$grado</option>";
                }
                ?>
              </select>
            </div>

            <!-- Fecha de renovación -->
            <div class="col-md-6">
              <label class="form-label">Fecha de renovación</label>
              <input type="date" class="form-control campo-bloqueado" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>

            <!-- Foto (opcional para renovación) -->
            <div class="col-md-12">
              <label class="form-label">Actualizar foto (opcional)</label>
              <input type="file" class="form-control" name="foto" accept="image/*">
              <small class="text-muted">Deja en blanco si no deseas cambiar tu foto actual.</small>
            </div>

            <!-- Términos y condiciones -->
            <div class="col-12">
              <div class="form-check p-3 border rounded bg-light">
                <input class="form-check-input" type="checkbox" id="acepto" required>
                <label class="form-check-label fw-semibold" for="acepto">
                  Confirmo que los datos son correctos y acepto renovar mi matrícula 2026 bajo los términos y condiciones del Club SIAO.
                </label>
              </div>
            </div>

            <!-- Botones -->
            <div class="col-12 text-center mt-4">
              <a href="index.php" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>Volver
              </a>
              <button class="btn btn-primary" type="submit">
                <i class="bi bi-check-circle me-2"></i>Renovar Matrícula 2026
              </button>
            </div>

          </div>
        </form>
      </div>

    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>