<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si viene de un registro exitoso
if (!isset($_GET['success'])) {  // Paréntesis corregido
    header("Location: matricula_public.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - Club Deportivo SIAO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #E7F9FD;
            font-family: 'Segoe UI', sans-serif;
        }
        .thank-you-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .checkmark {
            color: #28a745;
            font-size: 5rem;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #FF007F;
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 30px;
            margin-top: 20px;
        }
        .contact-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="thank-you-container shadow-lg">
            <div class="checkmark">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h1 class="mb-4">¡Gracias por registrarte!</h1>
            <p class="lead">Tu registro como deportista en el Club Deportivo SIAO ha sido exitoso.</p>
            
            <div class="next-steps mt-4">
                <h3 class="mb-3">Ahora debes:</h3>
                <ol class="text-start mx-auto" style="max-width: 500px;">
                    <li class="mb-2">Completar el proceso de pago de derechos de matrícula</li>
                    <li class="mb-2">Presentar los documentos requeridos</li>
                    <li>Asistir a tu primera clase según el horario seleccionado</li>
                </ol>
            </div>
            
            <div class="contact-info mt-5">
                <h4 class="mb-3">Contacta al Secretario del Club:</h4>
                <p><i class="bi bi-telephone"></i> <strong>Teléfono:</strong> +57 323 289 7785</p>
                <p><i class="bi bi-envelope"></i> <strong>Email:</strong> joongdoryucolombia@gmail.com</p>
                <p><i class="bi bi-clock"></i> <strong>Horario de atención:</strong> Lunes a Sábado, 8:00 A.M - 8:00 P.M</p>
            </div>
            
            <a href="https://app.siaooficial.com" class="btn btn-primary mt-4">
                <i class="bi bi-house-door"></i> Volver al Inicio
            </a>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>