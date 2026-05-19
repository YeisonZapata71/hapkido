<?php
require_once '../../helpers/db.php';
require_once __DIR__ . '/../../vendor/autoload.php';

// --- 1. Obtener datos del afiliado ---
$conn = conectarDB();
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM afiliados_siao WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$afiliado = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if (!$afiliado) {
    die('Afiliado no encontrado.');
}

// --- 2. Preparar rutas absolutas de imagenes ---
$logo_path = $_SERVER['DOCUMENT_ROOT'] . '/APP/assets/img/logo_siao.png';
$foto_path = $_SERVER['DOCUMENT_ROOT'] . '/APP/assets/uploads/fotos/' . $afiliado['foto_nombre'];
$logo_base64 = file_exists($logo_path) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo_path)) : '';
$foto_base64 = (file_exists($foto_path) && $afiliado['foto_nombre']) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($foto_path)) : '';

// --- 3. Construir HTML profesional ---
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carné Digital - Club Deportivo SIAO</title>
<style>
body {
  margin: 0; padding: 0;
}
.carnet {
  width: 520px;
  height: 310px;
  background: #f7f7fa;
  border-radius: 24px;
  font-family: "Segoe UI", Arial, sans-serif;
  border: 2.5px solid #2b3653;
  box-shadow: 0 2px 16px #0003;
  display: flex;
  overflow: hidden;
}
.carnet-lateral {
  background: #223054;
  width: 165px;
  padding: 26px 0 0 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
}
.carnet-lateral img.logo {
  width: 66px;
  margin-bottom: 22px;
  background: none;
}
.carnet-lateral .foto {
  width: 95px; height: 110px;
  border-radius: 14px;
  object-fit: cover;
  border: 4px solid #fff;
  box-shadow: 0 1.5px 14px #0002;
  background: #ececec;
}
.carnet-main {
  flex: 1;
  padding: 30px 28px 20px 20px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.carnet-main h3 {
  margin: 0 0 8px 0;
  color: #1c2540;
  font-size: 1.23rem;
  font-weight: 700;
  letter-spacing: 0.5px;
}
.datos {
  margin: 0 0 18px 0;
  font-size: 15px;
}
.datos b {
  color: #e60026;
  font-weight: 500;
  min-width: 95px;
  display: inline-block;
}
.carnet-footer {
  position: absolute;
  left: 0; bottom: 0;
  width: 100%;
  background: #223054;
  color: #fff;
  font-size: 13px;
  letter-spacing: 1px;
  text-align: right;
  padding: 7px 18px 7px 170px;
  border-bottom-left-radius: 20px;
  border-bottom-right-radius: 20px;
}
</style>
</head>
<body>
<div class="carnet">
  <div class="carnet-lateral">
    <img src="' . $logo_base64 . '" class="logo" alt="Logo Club SIAO">
    <img src="' . $foto_base64 . '" class="foto" alt="Foto Afiliado">
  </div>
  <div class="carnet-main">
    <h3>' . htmlspecialchars($afiliado['nombre_completo']) . '</h3>
    <div class="datos"><b>Documento:</b> ' . htmlspecialchars($afiliado['documento']) . '</div>
    <div class="datos"><b>Celular:</b> ' . htmlspecialchars($afiliado['celular']) . '</div>
    <div class="datos"><b>Ciudad:</b> ' . htmlspecialchars($afiliado['ciudad']) . '</div>
    <div class="datos"><b>Grado:</b> ' . htmlspecialchars($afiliado['grado_cinturon']) . '</div>
    <div class="datos"><b>Fecha inscripción:</b> ' . htmlspecialchars($afiliado['fecha_inscripcion']) . '</div>
  </div>
</div>
<div class="carnet-footer">
  &copy; ' . date('Y') . ' Club SIAO | Medellín, Colombia
</div>
</body>
</html>
';

// --- 4. Generar PDF con DomPDF ---
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'landscape');
$dompdf->render();
$dompdf->stream('carnet_' . $afiliado['nombre_completo'] . '.pdf', ["Attachment" => false]);
exit;
?>