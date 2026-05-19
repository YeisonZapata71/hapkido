<?php
require_once '../../helpers/auth.php';
verificarSesion();
verificarRol('admin');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Íconos de Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .shadow-card {
    box-shadow: 0 4px 16px 0 rgba(20,60,100,0.08);
    border-radius: 1.2rem;
  }
  .form-section-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-top: 1.5rem;
    color: #3366bb;
    border-left: 4px solid #dde6fa;
    padding-left: .7rem;
    margin-bottom: .8rem;
  }
  @media (max-width: 575px) {
    .shadow-card { padding: 1rem !important;}
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
    .icono-campo {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #033884;
    }
    .input-group-icon {
      position: relative;
    }
    .input-group-icon input,
    .input-group-icon select {
      padding-left: 2.2rem;
    }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Inscripción - Club Deportivo SIAO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function validarFormulario(e) {
      const nombre = document.getElementById("nombre");
      const documento = document.getElementById("documento");
      const celular = document.getElementById("celular");
      const nombreAcudiente = document.getElementById("nombreAcudiente");
      const acepto = document.getElementById("acepto");

      const soloLetras = /^[a-zA-ZÀ-ÿ\s]+$/;
      const soloNumeros = /^\d+$/;

      // (A) Nombre válido
      if (!soloLetras.test(nombre.value)) {
        alert("El nombre solo debe contener letras y espacios.");
        nombre.focus();
        e.preventDefault();
        return false;
      }
      // (B) Documento válido
      if (!soloNumeros.test(documento.value) || documento.value.length < 6) {
        alert("El documento debe contener solo números y al menos 6 caracteres.");
        documento.focus();
        e.preventDefault();
        return false;
      }
      // (C) Celular válido
      if (!soloNumeros.test(celular.value) || celular.value.length !== 10) {
        alert("El celular debe tener exactamente 10 dígitos numéricos.");
        celular.focus();
        e.preventDefault();
        return false;
      }
      // (D) Si es menor, validar nombreAcudiente
      if (nombreAcudiente.required && !soloLetras.test(nombreAcudiente.value)) {
        alert("El nombre del acudiente solo debe contener letras y espacios.");
        nombreAcudiente.focus();
        e.preventDefault();
        return false;
      }
      // (E) Términos y condiciones
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
      document.getElementById("campoAcudiente").style.display = "none";
    });
  </script>
</head>
<body style="background-color: #E7F9FD; font-family: 'Segoe UI', sans-serif;">
  <div class="container py-5 d-flex justify-content-center">
    <div class="w-100" style="max-width: 900px; background-color: #ffffff; padding: 40px; border-radius: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
      
      <!-- ======== (1) IMAGEN CENTRADA ANTES DEL TÍTULO ======== -->
      <div class="text-center mb-4">
        <img src="logo_final.png" alt="Logo Club SIAO" style="max-width: 200px;">
      </div>

      <h2 class="mb-4 text-center">Formulario de Inscripción - Club Deportivo SIAO</h2>
      
      <!-- (5) Descripción y términos legales -->
      <p class="text-justify">
        Este es el formulario de matrícula al club SIAO para el año actual <strong>2025</strong>. Su diligenciamiento es requisito obligatorio para hacer parte de todo tipo de clase, evento o examen. No existen, ni se admitirán a futuro excepciones.
      </p>
      <p class="text-justify">
        Los datos suministrados en el siguiente formulario serán compilados, procesados y gestionados según lo establecido en la Ley 1581 de 2012 vigente en Colombia. Su uso será de carácter informativo y, eventualmente, promocional, y al diligenciarlo el deportista o acudiente autoriza, además, el envío de mensajes a los medios de contacto ahí descritos.
      </p>
      <p class="text-justify">
        De igual manera, al completar el presente formulario se manifiesta estar plena y voluntariamente de acuerdo con los siguientes componentes:
      </p>
      <ul>
        <li><strong>Responsabilidad de riesgo:</strong> entiendo que la participación del deportista en las diferentes actividades del club se hace bajo su propia voluntad, que él mismo reconoce sus riesgos y asume completa responsabilidad de estos, incluido el riesgo de hurto, pérdida o daños a su propiedad, lesiones físicas, accidente, muerte, entre otros.</li>
        <li><strong>Exoneración:</strong> por la presente libero y eximo de responsabilidad, indemnización y me comprometo a no establecer demandas en contra del Club SIAO y sus colaboradores. El presente acuerdo será vinculante para el club, el deportista, sus representantes y mis bienes.</li>
        <li>Con el diligenciamiento de la presente matrícula se compromete a asistir a las clases en los tiempos y horarios acordados y declara que en caso de inasistencia solo podrá reponer las mismas si estas cuentan con excusa válida debidamente radicada ante el secretario del club.</li>
        <li>Que en ningún caso el dejar de asistir a mis clases me exhonera de cancelar a tiempo mis mensualidades, y que reconozco que la inasistencia por tiempo prolongado a las mismas sin que medie excusa válida puede generar una sanción.</li>
      </ul>

      <!-- INICIO BLOQUE ENVOLVENTE -->
      <div class="mx-auto" style="max-width:900px;">
      <div class="card shadow-card p-4 bg-white">
          <div class="mb-4 text-center">
          <h2 class="fw-bold mb-1">Registro de Nuevo Afiliado</h2>
      <div class="text-secondary">Por favor diligencia todos los campos para inscribir correctamente al deportista.</div>
      </div>

      <!-- (6) FORMULARIO PRINCIPAL -->
      <form action="guardar_inscripcion.php" method="POST" enctype="multipart/form-data" onsubmit="validarFormulario(event)">
        <div class="row g-3">
          <!-- Nombre completo -->
          <div class="col-md-6">
            <label class="form-label">Nombre completo</label>
            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ej: Juan Pérez" required>
          </div>

          <!-- Documento -->
          <div class="col-md-6">
            <label class="form-label">Documento</label>
            <input type="text" class="form-control" name="documento" id="documento" placeholder="Solo números" required>
          </div>

          <!-- Fecha de nacimiento -->
          <div class="col-md-6">
            <label class="form-label">Fecha de nacimiento</label>
            <input type="date" class="form-control" id="fechaNacimiento" name="fecha_nacimiento" onchange="evaluarEdad()" required>
          </div>

          <!-- Sexo -->
          <div class="col-md-6">
            <label class="form-label">Sexo</label>
            <select class="form-select" name="sexo" required>
              <option value="">Seleccione</option>
              <option>Masculino</option>
              <option>Femenino</option>
            </select>
          </div>

          <!-- Celular -->
          <div class="col-md-6">
            <label class="form-label">Celular</label>
            <div class="input-group input-group-icon">
              <i class="bi bi-phone icono-campo"></i>
              <input type="text" class="form-control" name="celular" id="celular" placeholder="10 dígitos" required>
            </div>
          </div>

          <!-- Correo electrónico -->
          <div class="col-md-6">
            <label class="form-label">Correo electrónico</label>
            <div class="input-group input-group-icon">
              <i class="bi bi-envelope icono-campo"></i>
              <input type="email" class="form-control" name="correo" placeholder="example@correo.com" required>
            </div>
          </div>

          <!-- Dirección -->
          <div class="col-md-6">
            <label class="form-label">Dirección</label>
            <input type="text" class="form-control" name="direccion" placeholder="Ej: Calle 123 #45-67" required>
          </div>

          <!-- Ciudad -->
          <div class="col-md-6">
            <label class="form-label">Ciudad</label>
            <select class="form-select" name="ciudad" required>
              <option value="">Seleccione</option>
              <option>Barbosa</option>
                <option>Bello</option>
                <option>Caldas</option>
                <option>Copacabana</option>
                <option>Envigado</option>
                <option>Girardota</option>
                <option>Itagüí</option>
                <option>La Estrella</option>
                <option>Medellín</option>
                <option>Sabaneta</option>
                
            </select>
          </div>

          <!-- EPS -->
          <div class="col-md-6">
            <label class="form-label">EPS</label>
            <select class="form-select" name="eps" required>
              <option value="">Seleccione</option>
              <option>SURA</option>
              <option>Sanitas</option>
              <option>Coomeva</option>
              <option>Compensar</option>
              <option>Salud Total</option>
              <option>Nueva EPS</option>
              <option>Aliansalud</option>
              <option>Cafesalud</option>
              <option>Sanidad Militar</option>
              <option>Magisterio</option>
              <option>Savia Salud</option>
            </select>
          </div>

          <!-- Tipo de sangre -->
          <div class="col-md-6">
            <label class="form-label">Tipo de sangre</label>
            <select class="form-select" name="tipo_sangre" required>
              <option value="">Seleccione</option>
              <option>O+</option>
              <option>O−</option>
              <option>A+</option>
              <option>A−</option>
              <option>B+</option>
              <option>B−</option>
              <option>AB+</option>
              <option>AB−</option>
            </select>
          </div>

          <!-- Campo Acudiente (oculto hasta que JS detecta menor de edad) -->
          <div class="col-md-6" id="campoAcudiente">
            <label class="form-label">Nombre completo del acudiente</label>
            <input type="text" class="form-control" name="nombre_acudiente" id="nombreAcudiente" placeholder="Obligatorio si es menor">
          </div>

          <!-- Horarios (checkboxes para múltiples) -->
          <div class="col-md-12">
            <label class="form-label">Horarios</label>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="horarios[]" value="Lunes, Miércoles y Viernes 06:00 a.m." id="h1">
                  <label class="form-check-label" for="h1">Lunes, Miércoles y Viernes 06:00 a.m.</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="horarios[]" value="Martes y Jueves 05:00 p.m." id="h2">
                  <label class="form-check-label" for="h2">Martes y Jueves 05:00 p.m.</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="horarios[]" value="Miércoles y Viernes 07:30 p.m." id="h3">
                  <label class="form-check-label" for="h3">Miércoles y Viernes 07:30 p.m.</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="horarios[]" value="Avanzados y Negros" id="h4">
                  <label class="form-check-label" for="h4">Avanzados y Negros</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="horarios[]" value="Sábados 07:00 a.m." id="h5">
                  <label class="form-check-label" for="h5">Sábados 07:00 a.m.</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="horarios[]" value="Sábados 12:00 m" id="h6">
                  <label class="form-check-label" for="h6">Sábados 12:00 m</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="horarios[]" value="Sede Robledo" id="h7">
                  <label class="form-check-label" for="h7">Sede Robledo</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="horarios[]" value="Taekwondo" id="h8">
                  <label class="form-check-label" for="h8">Taekwondo</label>
                </div>
              </div>
            </div>
          </div>

          <!-- Grado de cinturón -->
          <div class="col-md-6">
            <label class="form-label">Grado de cinturón</label>
            <select class="form-select" name="grado_cinturon" required>
              <option value="">Seleccione</option>
              <option>Blanco</option>
              <option>Amarillo</option>
              <option>Naranja</option>
              <option>Verde</option>
              <option>Azul</option>
              <option>Púrpura</option>
              <option>Rojo</option>
              <option>Rojo-Marrón</option>
              <option>Marrón</option>
              <option>Marrón-Negro</option>
              <option>1th Dan</option>
              <option>2th Dan</option>
              <option>3th Dan</option>
              <option>4th Dan</option>
              <option>5th Dan</option>
              <option>6th Dan</option>
              <option>7th Dan</option>
              <option>8th Dan</option>
            </select>
          </div>

          <!-- Fecha de inscripción (valor por defecto: hoy) -->
          <div class="col-md-6">
            <label class="form-label">Fecha de inscripción</label>
            <input 
              type="date" 
              class="form-control" 
              name="fecha_inscripcion" 
              max="<?= date('Y-m-d') ?>" 
              value="<?= date('Y-m-d') ?>"
              required
            >
          </div>

          <!-- Foto -->
          <div class="col-md-6">
            <label class="form-label">Foto</label>
            <input type="file" class="form-control" name="foto" accept="image/*" required>
          </div>

          <!-- Términos y condiciones -->
          <div class="col-12">
            <div class="form-check p-3 border rounded bg-light">
              <input class="form-check-input" type="checkbox" id="acepto" required>
              <label class="form-check-label fw-semibold" for="acepto">
                Declaro que he leído y acepto los términos y condiciones descritos anteriormente.
              </label>
            </div>
          </div>

          <!-- Botón de envío -->
          <div class="col-12 text-center">
            <button class="btn btn-primary" type="submit">Enviar Inscripción</button>
          </div>
        </div>
      </form>
        </div>
      </div>
<!-- FIN BLOQUE ENVOLVENTE -->

    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
