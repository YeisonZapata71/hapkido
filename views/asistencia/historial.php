include '../../views/partials/header.php';
?>
<div class="container py-4">
  <h2>Historial de Asistencia</h2>
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>Fecha</th>
        <th>Horario</th>
        <th>Observaciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($historial as $fila): ?>
        <tr>
          <td><?= $fila['fecha'] ?></td>
          <td><?= $fila['horario'] ?></td>
          <td><?= $fila['observaciones'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include '../../views/partials/footer.php'; ?>
