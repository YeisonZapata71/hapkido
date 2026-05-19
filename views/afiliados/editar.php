<?php
require_once '../../helpers/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../../helpers/db.php';

$conn = conectarDB();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar afiliado
$stmt = $conn->prepare("SELECT * FROM afiliados_siao WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
  echo "<div class='alert alert-danger'>Afiliado no encontrado.</div>";
  exit;
}
$afiliado = $resultado->fetch_assoc();

// Procesar horarios seleccionados
$horariosSeleccionados = [];
if (!empty($afiliado['horario'])) {
    $horariosSeleccionados = array_map('trim', explode(';', $afiliado['horario']));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Edición de Afiliado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .shadow-card {
      box-shadow: 0 4px 16px 0 rgba(20,60,100,0.08);
      border-radius: 1.2rem;
    }
    @media (max-width: 575px) {
      .shadow-card { padding: 1rem !important;}
    }
    .foto-previa {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: .5rem;
      border: 1px solid #e3e3e3;
      margin-top: 5px;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="mx-auto" style="max-width: 900px;">
      <a href="index.php" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
        <i class="bi bi-arrow-left me-2"></i>Volver al listado
      </a>
      <div class="card shadow-card p-4 bg-white">
        <div class="mb-4 text-center">
          <h2 class="fw-bold mb-1">Edición de Afiliado</h2>
          <div class="text-secondary">Modifica solo los datos necesarios y guarda los cambios.</div>
        </div>
        <form action="../../controllers/actualizar_afiliado.php" method="POST" enctype="multipart/form-data" autocomplete="off">
          <input type="hidden" name="id" value="<?= $afiliado['id'] ?>">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Nombre completo del deportista</label>
              <input type="text" name="nombre_completo" class="form-control" value="<?= htmlspecialchars($afiliado['nombre_completo']) ?>" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Documento de identidad</label>
              <input type="text" name="documento" class="form-control" value="<?= htmlspecialchars($afiliado['documento']) ?>" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Fecha de nacimiento</label>
              <input type="date" name="fecha_nacimiento" class="form-control" value="<?= htmlspecialchars($afiliado['fecha_nacimiento']) ?>" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Sexo</label>
              <select name="sexo" class="form-select" required>
                <option value="">Selecciona</option>
                <option value="Masculino" <?= ($afiliado['sexo'] == 'Masculino' ? 'selected' : '') ?>>Masculino</option>
                <option value="Femenino" <?= ($afiliado['sexo'] == 'Femenino' ? 'selected' : '') ?>>Femenino</option>
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Celular de contacto</label>
              <input type="text" name="celular" class="form-control" value="<?= htmlspecialchars($afiliado['celular']) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Correo electrónico</label>
              <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($afiliado['correo']) ?>" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Dirección de residencia</label>
              <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($afiliado['direccion']) ?>">
            </div>
            <div class="col-md-4 mb-3">
            <label class="form-label">Ciudad</label>
              <select name="ciudad" class="form-select" required>
              <option value="">Selecciona</option>
              <option value="Barbosa" <?= ($afiliado['ciudad'] == 'Barbosa' ? 'selected' : '') ?>>Barbosa</option>
              <option value="Bello" <?= ($afiliado['ciudad'] == 'Bello' ? 'selected' : '') ?>>Bello</option>
              <option value="Caldas" <?= ($afiliado['ciudad'] == 'Caldas' ? 'selected' : '') ?>>Caldas</option>
              <option value="Copacabana" <?= ($afiliado['ciudad'] == 'Copacabana' ? 'selected' : '') ?>>Copacabana</option>
              <option value="Envigado" <?= ($afiliado['ciudad'] == 'Envigado' ? 'selected' : '') ?>>Envigado</option>
              <option value="Girardota" <?= ($afiliado['ciudad'] == 'Girardota' ? 'selected' : '') ?>>Girardota</option>
              <option value="Itagüí" <?= ($afiliado['ciudad'] == 'Itagüí' ? 'selected' : '') ?>>Itagüí</option>
              <option value="La Estrella" <?= ($afiliado['ciudad'] == 'La Estrella' ? 'selected' : '') ?>>La Estrella</option>
              <option value="Medellín" <?= ($afiliado['ciudad'] == 'Medellín' ? 'selected' : '') ?>>Medellín</option>
              <option value="Sabaneta" <?= ($afiliado['ciudad'] == 'Sabaneta' ? 'selected' : '') ?>>Sabaneta</option>
            </select>
            </div>
            <div class="col-md-4 mb-3">
            <label class="form-label">EPS</label>
            <select name="eps" class="form-select" required>
              <option value="">Selecciona</option>
              <option value="SURA" <?= ($afiliado['eps'] == 'SURA' ? 'selected' : '') ?>>SURA</option>
              <option value="Sanitas" <?= ($afiliado['eps'] == 'Sanitas' ? 'selected' : '') ?>>Sanitas</option>
              <option value="Coomeva" <?= ($afiliado['eps'] == 'Coomeva' ? 'selected' : '') ?>>Coomeva</option>
              <option value="Compensar" <?= ($afiliado['eps'] == 'Compensar' ? 'selected' : '') ?>>Compensar</option>
              <option value="Salud Total" <?= ($afiliado['eps'] == 'Salud Total' ? 'selected' : '') ?>>Salud Total</option>
              <option value="Nueva EPS" <?= ($afiliado['eps'] == 'Nueva EPS' ? 'selected' : '') ?>>Nueva EPS</option>
              <option value="Aliansalud" <?= ($afiliado['eps'] == 'Aliansalud' ? 'selected' : '') ?>>Aliansalud</option>
              <option value="Cafesalud" <?= ($afiliado['eps'] == 'Cafesalud' ? 'selected' : '') ?>>Cafesalud</option>
              <option value="Sanidad Militar" <?= ($afiliado['eps'] == 'Sanidad Militar' ? 'selected' : '') ?>>Sanidad Militar</option>
              <option value="Magisterio" <?= ($afiliado['eps'] == 'Magisterio' ? 'selected' : '') ?>>Magisterio</option>
              <option value="Savia Salud" <?= ($afiliado['eps'] == 'Savia Salud' ? 'selected' : '') ?>>Savia Salud</option>
            </select>
            </div>

            <div class="col-md-3 mb-3">
            <label class="form-label">Tipo de sangre</label>
            <select name="tipo_sangre" class="form-select" required>
              <option value="">Selecciona</option>
              <option value="O+" <?= ($afiliado['tipo_sangre'] == 'O+' ? 'selected' : '') ?>>O+</option>
              <option value="O-" <?= ($afiliado['tipo_sangre'] == 'O-' ? 'selected' : '') ?>>O-</option>
              <option value="A+" <?= ($afiliado['tipo_sangre'] == 'A+' ? 'selected' : '') ?>>A+</option>
              <option value="A-" <?= ($afiliado['tipo_sangre'] == 'A-' ? 'selected' : '') ?>>A-</option>
              <option value="B+" <?= ($afiliado['tipo_sangre'] == 'B+' ? 'selected' : '') ?>>B+</option>
              <option value="B-" <?= ($afiliado['tipo_sangre'] == 'B-' ? 'selected' : '') ?>>B-</option>
              <option value="AB+" <?= ($afiliado['tipo_sangre'] == 'AB+' ? 'selected' : '') ?>>AB+</option>
              <option value="AB-" <?= ($afiliado['tipo_sangre'] == 'AB-' ? 'selected' : '') ?>>AB-</option>
            </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Nombre completo del acudiente <span class="form-hint">(si aplica)</span></label>
              <input type="text" name="nombre_acudiente" class="form-control" value="<?= htmlspecialchars($afiliado['nombre_acudiente']) ?>">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Grado de cinturón</label>
              <select name="grado_cinturon" class="form-select">
                <option value="">Selecciona</option>
                <option value="Blanco" <?= ($afiliado['grado_cinturon'] == 'Blanco' ? 'selected' : '') ?>>Blanco</option>
                <option value="Amarillo" <?= ($afiliado['grado_cinturon'] == 'Amarillo' ? 'selected' : '') ?>>Amarillo</option>
                <option value="Naranja" <?= ($afiliado['grado_cinturon'] == 'Naranja' ? 'selected' : '') ?>>Naranja</option>
                <option value="Verde" <?= ($afiliado['grado_cinturon'] == 'Verde' ? 'selected' : '') ?>>Verde</option>
                <option value="Azul" <?= ($afiliado['grado_cinturon'] == 'Azul' ? 'selected' : '') ?>>Azul</option>
                <option value="Púrpura" <?= ($afiliado['grado_cinturon'] == 'Púrpura' ? 'selected' : '') ?>>Púrpura</option>
                <option value="Rojo" <?= ($afiliado['grado_cinturon'] == 'Rojo' ? 'selected' : '') ?>>Rojo</option>
                <option value="Rojo-Marrón" <?= ($afiliado['grado_cinturon'] == 'Rojo-Marrón' ? 'selected' : '') ?>>Rojo-Marrón</option>
                <option value="Marrón" <?= ($afiliado['grado_cinturon'] == 'Marrón' ? 'selected' : '') ?>>Marrón</option>
                <option value="Marrón-Negro" <?= ($afiliado['grado_cinturon'] == 'Marrón-Negro' ? 'selected' : '') ?>>Marrón-Negro</option>
                <option value="1th Dan" <?= ($afiliado['grado_cinturon'] == '1th Dan' ? 'selected' : '') ?>>1th Dan</option>
                <option value="2th Dan" <?= ($afiliado['grado_cinturon'] == '2th Dan' ? 'selected' : '') ?>>2th Dan</option>
                <option value="3th Dan" <?= ($afiliado['grado_cinturon'] == '3th Dan' ? 'selected' : '') ?>>3th Dan</option>
                <option value="4th Dan" <?= ($afiliado['grado_cinturon'] == '4th Dan' ? 'selected' : '') ?>>4th Dan</option>
                <option value="5th Dan" <?= ($afiliado['grado_cinturon'] == '5th Dan' ? 'selected' : '') ?>>5th Dan</option>
                <option value="6th Dan" <?= ($afiliado['grado_cinturon'] == '6th Dan' ? 'selected' : '') ?>>6th Dan</option>
                <option value="7th Dan" <?= ($afiliado['grado_cinturon'] == '7th Dan' ? 'selected' : '') ?>>7th Dan</option>
                <option value="8th Dan" <?= ($afiliado['grado_cinturon'] == '8th Dan' ? 'selected' : '') ?>>8th Dan</option>

              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Fecha de inscripción</label>
              <input type="date" name="fecha_inscripcion" class="form-control" value="<?= htmlspecialchars($afiliado['fecha_inscripcion']) ?>" required>
            </div>
            <!-- HORARIOS MULTI-CHECKBOX -->
            <div class="col-md-9 mb-3">
              <label class="form-label">Horarios</label>
              <div class="row g-2">
                <div class="col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="horarios[]" value="Lunes, Miércoles y Viernes 06:00 a.m." id="h1"
                      <?= in_array("Lunes, Miércoles y Viernes 06:00 a.m.", $horariosSeleccionados) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="h1">Lunes, Miércoles y Viernes 06:00 a.m.</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="horarios[]" value="Martes y Jueves 05:00 p.m." id="h2"
                      <?= in_array("Martes y Jueves 05:00 p.m.", $horariosSeleccionados) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="h2">Martes y Jueves 05:00 p.m.</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="horarios[]" value="Miércoles y Viernes 07:30 p.m." id="h3"
                      <?= in_array("Miércoles y Viernes 07:30 p.m.", $horariosSeleccionados) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="h3">Miércoles y Viernes 07:30 p.m.</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="horarios[]" value="Avanzados y Negros" id="h4"
                      <?= in_array("Avanzados y Negros", $horariosSeleccionados) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="h4">Avanzados y Negros</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="horarios[]" value="Sábados 07:00 a.m." id="h5"
                      <?= in_array("Sábados 07:00 a.m.", $horariosSeleccionados) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="h5">Sábados 07:00 a.m.</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="horarios[]" value="Sábados 12:00 m" id="h6"
                      <?= in_array("Sábados 12:00 m", $horariosSeleccionados) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="h6">Sábados 12:00 m</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="horarios[]" value="Sede Robledo" id="h7"
                      <?= in_array("Sede Robledo", $horariosSeleccionados) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="h7">Sede Robledo</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="horarios[]" value="Taekwondo" id="h8"
                      <?= in_array("Taekwondo", $horariosSeleccionados) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="h8">Taekwondo</label>
                  </div>
                </div>
              </div>
            </div>
            <!-- FOTO -->
            <div class="col-md-3 mb-3">
            <label class="form-label">Foto actual</label><br>
              <?php if (!empty($afiliado['foto_nombre']) && file_exists("../../assets/uploads/fotos/" . $afiliado['foto_nombre'])): ?>
              <img src="../../assets/uploads/fotos/<?= htmlspecialchars($afiliado['foto_nombre']) ?>" class="foto-previa" alt="Foto actual">
              <?php else: ?>
            <span class="text-muted">No hay foto</span>
              <?php endif; ?>
            <label class="form-label mt-2">Nueva foto (opcional)</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
          </div>
          <div class="mt-4">
            <a href="index.php" class="btn btn-outline-secondary px-4 rounded-pill">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm">
              <i class="bi bi-save me-2"></i>Actualizar afiliado
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
