include '../../views/partials/header.php';
?>
<div class="container py-4">
  <h2>Registrar Asistencia</h2>
  <form method="POST" action="asistencia_controller.php?accion=guardar">
    <div class="mb-3">
      <label for="afiliado_id" class="form-label">ID del Afiliado</label>
      <input type="number" name="afiliado_id" id="afiliado_id" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="fecha" class="form-label">Fecha</label>
      <input type="date" name="fecha" id="fecha" class="form-control" value="<?= date('Y-m-d') ?>">
    </div>
    <div class="mb-3">
      <label for="horario" class="form-label">Horario</label>
      <input type="text" name="horario" id="horario" class="form-control">
    </div>
    <div class="mb-3">
      <label for="observaciones" class="form-label">Observaciones</label>
      <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
    </div>

    <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formFranjas">
      Evaluar Franjas
    </button>

    <div class="collapse mt-3" id="formFranjas">
      <div class="card card-body">
        <h5 class="mb-3">Evaluación de Franjas</h5>
        <div class="row">
          <?php
            $franjas = [
              'negra' => 'Básicos con armas y mano vacía',
              'verde' => 'Bloqueos, posturas y golpes',
              'azul' => 'Figuras mano vacía y armas',
              'roja' => 'Patadas, flexibilidad y combate',
              'amarilla' => 'Caídas y lanzamientos',
              'blanca' => 'Teoría y comportamiento'
            ];
            foreach ($franjas as $color => $desc): ?>
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold text-capitalize">Franja <?= $color ?>:</label>
                <select class="form-select" name="franja_<?= $color ?>">
                  <option value="">-- Seleccionar --</option>
                  <option value="aprobado">Aprobó</option>
                  <option value="reprobado">Reprobó</option>
                </select>
                <small class="text-muted"><?= $desc ?></small>
              </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-success">Guardar asistencia</button>
    </div>
  </form>
</div>
<?php include '../../views/partials/footer.php'; ?>
