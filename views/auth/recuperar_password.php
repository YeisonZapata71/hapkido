<?php
session_start();
$enviado       = isset($_GET['enviado']);
$correo_masked = $_SESSION['reset_success'] ?? '';
if ($enviado) unset($_SESSION['reset_success']);
$error = $_SESSION['reset_error'] ?? '';
if ($error) unset($_SESSION['reset_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña — SIAO</title>
    <link rel="icon" href="https://app.siaooficial.com/APP/assets/img/logo_color.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/login.css" rel="stylesheet">
    <style>
        .recover-card {
            max-width: 420px;
            width: 100%;
        }
        .back-link {
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 1.5rem;
        }
        .back-link:hover { color: rgba(255,255,255,0.9); }
        .success-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(0, 212, 255, 0.15);
            border: 2px solid rgba(0, 212, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
        }
        .masked-email {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 10px 16px;
            font-family: monospace;
            font-size: 0.95rem;
            color: #00d4ff;
            text-align: center;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
<div class="login-container" style="justify-content:center;">
    <div class="login-form-side" style="width:100%;max-width:520px;">
        <div class="glass-card recover-card">

            <a href="../../login.php" class="back-link">
                &#8592; Volver al inicio de sesión
            </a>

            <?php if ($enviado): ?>
                <!-- Estado: Email enviado -->
                <div class="text-center">
                    <div class="success-icon">📧</div>
                    <h2 class="fw-bold mb-2">Revisa tu correo</h2>
                    <p class="text-white text-opacity-50 mb-3">
                        Si el correo está registrado, recibirás el enlace en:
                    </p>
                    <?php if ($correo_masked): ?>
                        <div class="masked-email"><?= htmlspecialchars($correo_masked) ?></div>
                    <?php endif; ?>
                    <p class="text-white text-opacity-40 small mt-3">
                        El enlace expirará en <strong style="color:rgba(255,255,255,0.7)">1 hora</strong>.<br>
                        Si no lo ves, revisa tu carpeta de spam.
                    </p>
                    <a href="../../login.php" class="btn btn-primary w-100 py-3 mt-4 rounded-pill fw-bold shadow-sm">
                        Volver al Login
                    </a>
                </div>

            <?php else: ?>
                <!-- Estado: Formulario -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1">¿Olvidaste tu contraseña?</h2>
                    <p class="text-white text-opacity-50">
                        Ingresa tu correo y te enviaremos un enlace para restablecerla.
                    </p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger bg-danger bg-opacity-10 text-white border-0 py-2 text-center small mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="../../controllers/recuperar_password_controller.php" method="POST">
                    <div class="mb-4">
                        <label class="form-label">Correo Electrónico</label>
                        <input
                            type="email"
                            name="correo"
                            id="correo"
                            class="form-control"
                            placeholder="nombre@ejemplo.com"
                            required
                            autofocus
                        >
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" id="btnRecuperar">
                        ENVIAR ENLACE DE RECUPERACIÓN
                    </button>
                    <div class="mt-4 text-center">
                        <small class="text-white text-opacity-25">Club Deportivo SIAO &copy; <?= date('Y') ?></small>
                    </div>
                </form>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('btnRecuperar');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Enviando...';
            }
        });
    }
</script>
</body>
</html>
