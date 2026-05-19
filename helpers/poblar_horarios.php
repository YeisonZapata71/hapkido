<?php
require_once(__DIR__ . '/db.php');
$conn = conectarDB();

// Obtener todos los valores de horarios de los afiliados
$sql = "SELECT horario FROM afiliados_siao WHERE horario IS NOT NULL AND horario != ''";
$result = $conn->query($sql);

$horariosUnicos = [];

while ($row = $result->fetch_assoc()) {
    // Separar por punto y coma o coma (ajusta según tu formato)
    $horas = array_map('trim', preg_split('/[;,]/', $row['horario']));
    foreach ($horas as $h) {
        if ($h !== '' && !in_array($h, $horariosUnicos)) {
            $horariosUnicos[] = $h;
        }
    }
}

// Insertar los horarios únicos en la tabla horarios
foreach ($horariosUnicos as $horario) {
    // Evitar duplicados
    $stmt = $conn->prepare("INSERT IGNORE INTO horarios (nombre) VALUES (?)");
    $stmt->bind_param('s', $horario);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

echo "¡Horarios cargados exitosamente!";
?>
