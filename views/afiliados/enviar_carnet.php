<?php
require_once '../../helpers/db.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../libraries/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../libraries/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../../libraries/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;

$conn = conectarDB();
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM afiliados_siao WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$afiliado = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if (!$afiliado) {
    die("Afiliado no encontrado");
}

// Ruta ABSOLUTA para imágenes al usar DomPDF en modo remoto (usa tu dominio real)
$logo_url = "https://app.siaooficial.com/APP/assets/img/logo_siao.png";
$foto_url = "https://app.siaooficial.com/APP/assets/uploads/fotos/" . htmlspecialchars($afiliado['foto_nombre']);
$fondo_url = "https://app.siaooficial.com/APP/assets/img/fondo_carnet.jpg";

// HTML del carnet para DomPDF (ajustado para usar rutas absolutas)
$html = '
<html>
<head>
  <style>
    body { margin: 0; padding: 0; background: #eee; }
    .carnet { width: 520px; height: 330px; border-radius: 18px; background: #fff url('.$fondo_url.') center/cover no-repeat; display: flex; box-shadow: 0 2px 16px #aaa; font-family: Arial, sans-serif;}
    .lateral { background: #243148; width: 165px; border-radius: 16px 0 0 16px; padding: 12px 4px 0 4px; text-align: center;}
    .lateral img.logo { width: 80px; height: 80px; object-fit: contain; margin-bottom: 12px;}
    .lateral img.foto { width: 90px; height: 90px; border-radius: 12px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 1px 6px #0003;}
    .main { flex: 1; padding: 28px 30px 14px 18px;}
    .main h4 { font-size: 21px; margin: 0 0 7px 0;}
    .dato { font-size: 15px; margin-bottom: 5px;}
    .dato b { color: #d80035; width: 86px; display: inline-block;}
    .footer { position: absolute; left: 30px; bottom: 16px; font-size: 13px; color: #555;}
  </style>
</head>
<body>
<div class="carnet">
  <div class="lateral">
    <img src="'.$logo_url.'" class="logo">
    <img src="'.$foto_url.'" class="foto">
  </div>
  <div class="main">
    <h4>'.htmlspecialchars($afiliado['nombre_completo']).'</h4>
    <div class="dato"><b>Documento:</b> '.htmlspecialchars($afiliado['documento']).'</div>
    <div class="dato"><b>Celular:</b> '.htmlspecialchars($afiliado['celular']).'</div>
    <div class="dato"><b>Ciudad:</b> '.htmlspecialchars($afiliado['ciudad']).'</div>
    <div class="dato"><b>Grado:</b> '.htmlspecialchars($afiliado['grado_cinturon']).'</div>
    <div class="dato"><b>Fecha inscripción:</b> '.htmlspecialchars($afiliado['fecha_inscripcion']).'</div>
    <div class="footer">&copy; '.date('Y').' Club SIAO | Medellín, Colombia</div>
  </div>
</div>
</body>
</html>
';

    // Crear PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A6', 'landscape');
    $dompdf->render();
    $pdfOutput = $dompdf->output();

    // Configurar PHPMailer
    $mail = new PHPMailer(true);
    try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'joongdoryucolombia@gmail.com';
    $mail->Password = 'ugrj htnb egav uebl'; // No uses la clave normal si tienes 2FA, usa contraseña de aplicación
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('TU_CORREO@gmail.com', 'Club SIAO');
    $mail->addAddress($afiliado['correo'], $afiliado['nombre_completo']);
    $mail->Subject = 'Carné Digital Club SIAO';
    $mail->Body = "¡Hola {$afiliado['nombre_completo']}! Adjunto encuentras tu carné digital de afiliado al Club SIAO. \n\nSaludos cordiales.";
    $mail->addStringAttachment($pdfOutput, 'Carnet_SIAO.pdf');
    $mail->send();
    echo "<script>alert('Carné enviado por email.');window.location='carnet.php?id={$afiliado['id']}';</script>";
} catch (Exception $e) {
    echo "<script>alert('Error al enviar correo: ".$mail->ErrorInfo."');window.location='carnet.php?id={$afiliado['id']}';</script>";
}
?>
