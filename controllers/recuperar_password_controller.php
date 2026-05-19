<?php
// controllers/recuperar_password_controller.php
session_start();
require_once __DIR__ . '/../helpers/db.php';
require_once __DIR__ . '/../libraries/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libraries/PHPMailer/SMTP.php';
require_once __DIR__ . '/../libraries/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/auth/recuperar_password.php');
    exit;
}

$correo = trim($_POST['correo'] ?? '');

if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['reset_error'] = 'Por favor ingresa un correo válido.';
    header('Location: ../views/auth/recuperar_password.php');
    exit;
}

$conn = conectarDB();

// Buscar el usuario por correo
$stmt = $conn->prepare("SELECT id, nombre FROM usuarios WHERE correo = ? LIMIT 1");
$stmt->bind_param('s', $correo);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Siempre mostramos el mismo mensaje por seguridad (no revelar si el correo existe o no)
if (!$usuario) {
    $conn->close();
    $_SESSION['reset_success'] = $correo;
    header('Location: ../views/auth/recuperar_password.php?enviado=1');
    exit;
}

// Generar token seguro único de 64 caracteres
$token     = bin2hex(random_bytes(32));
$expira_en = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Eliminar tokens previos del mismo usuario
$stmt = $conn->prepare("DELETE FROM password_resets WHERE usuario_id = ?");
$stmt->bind_param('i', $usuario['id']);
$stmt->execute();
$stmt->close();

// Guardar nuevo token
$stmt = $conn->prepare("INSERT INTO password_resets (usuario_id, token, expira_en) VALUES (?, ?, ?)");
$stmt->bind_param('iss', $usuario['id'], $token, $expira_en);
$stmt->execute();
$stmt->close();
$conn->close();

// Construir el enlace de restablecimiento
$link = "https://app.siaooficial.com/APP/views/auth/restablecer_password.php?token=" . $token;

// Enmascarar correo para el mensaje: j***@gmail.com
$partes     = explode('@', $correo);
$local      = $partes[0];
$dominio    = $partes[1];
$local_mask = substr($local, 0, 1) . str_repeat('*', max(2, strlen($local) - 2)) . substr($local, -1);
$correo_masked = $local_mask . '@' . $dominio;

// Enviar email con PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'joongdoryucolombia@gmail.com';
    $mail->Password   = 'ugrj htnb egav uebl';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('joongdoryucolombia@gmail.com', 'Club SIAO');
    $mail->addAddress($correo, $usuario['nombre']);
    $mail->Subject = 'Restablecer contraseña — Club SIAO';
    $mail->isHTML(true);

    $nombre = htmlspecialchars($usuario['nombre']);
    $mail->Body = "
    <!DOCTYPE html>
    <html lang='es'>
    <head><meta charset='UTF-8'></head>
    <body style='margin:0;padding:0;background:#f0f4f8;font-family:Arial,sans-serif;'>
      <table width='100%' cellpadding='0' cellspacing='0' style='background:#f0f4f8;padding:40px 0;'>
        <tr><td align='center'>
          <table width='520' cellpadding='0' cellspacing='0' style='background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);'>
            <!-- Header -->
            <tr>
              <td style='background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);padding:36px 40px;text-align:center;'>
                <h1 style='color:#00d4ff;margin:0;font-size:28px;letter-spacing:3px;'>SIAO</h1>
                <p style='color:rgba(255,255,255,0.7);margin:6px 0 0;font-size:13px;'>Club Deportivo Marcial</p>
              </td>
            </tr>
            <!-- Body -->
            <tr>
              <td style='padding:40px;'>
                <h2 style='color:#2c3e50;margin:0 0 16px;font-size:20px;'>Hola, {$nombre}</h2>
                <p style='color:#555;line-height:1.7;margin:0 0 24px;'>
                  Recibimos una solicitud para restablecer la contraseña de tu cuenta en el sistema SIAO.<br>
                  Haz clic en el botón a continuación para crear una nueva contraseña.<br>
                  <strong>Este enlace expirará en 1 hora.</strong>
                </p>
                <!-- Button -->
                <table cellpadding='0' cellspacing='0' style='margin:0 auto 32px;'>
                  <tr>
                    <td style='background:linear-gradient(135deg,#00d4ff,#0083b0);border-radius:50px;'>
                      <a href='{$link}' style='display:inline-block;padding:16px 40px;color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;letter-spacing:0.5px;'>
                        RESTABLECER MI CONTRASEÑA
                      </a>
                    </td>
                  </tr>
                </table>
                <p style='color:#999;font-size:12px;line-height:1.6;border-top:1px solid #eee;padding-top:20px;margin:0;'>
                  Si no solicitaste esto, puedes ignorar este mensaje con seguridad.<br>
                  Tu contraseña <strong>no cambiará</strong> hasta que uses el enlace anterior.<br><br>
                  ¿Problemas con el botón? Copia y pega este enlace en tu navegador:<br>
                  <a href='{$link}' style='color:#0083b0;word-break:break-all;'>{$link}</a>
                </p>
              </td>
            </tr>
            <!-- Footer -->
            <tr>
              <td style='background:#f8f9fa;padding:20px 40px;text-align:center;border-top:1px solid #eee;'>
                <p style='color:#aaa;font-size:11px;margin:0;'>© " . date('Y') . " Club Deportivo SIAO · Medellín, Colombia</p>
              </td>
            </tr>
          </table>
        </td></tr>
      </table>
    </body>
    </html>
    ";

    $mail->AltBody = "Hola {$nombre},\n\nHaz clic en el siguiente enlace para restablecer tu contraseña (válido 1 hora):\n\n{$link}\n\nSi no solicitaste esto, ignora este mensaje.\n\n— Club SIAO";

    $mail->send();

} catch (Exception $e) {
    // Silenciar error para no revelar info — el token quedó guardado de todas formas
    error_log("Error PHPMailer recuperar_password: " . $mail->ErrorInfo);
}

// Siempre redirigir con mensaje de éxito (aunque el correo falle)
$_SESSION['reset_success'] = $correo_masked;
header('Location: ../views/auth/recuperar_password.php?enviado=1');
exit;
?>
