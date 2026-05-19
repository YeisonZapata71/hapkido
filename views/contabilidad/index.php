<?php
// Este archivo actúa como una redirección de seguridad.
// Si el enlace en el dashboard apunta aquí por error (caché o archivo no actualizado),
// lo redirigimos al controlador correcto.
header("Location: ../../controllers/contabilidad_controller.php");
exit;
?>
