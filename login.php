<?php
session_start();
$login_success = $_SESSION['login_success'] ?? '';
if ($login_success) unset($_SESSION['login_success']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Meta Tags Open Graph para Facebook/LinkedIn/WhatsApp -->
    <meta property="og:title" content="Ingreso al sistema - SIAO">
    <meta property="og:description" content="Gestión Deportiva Marcial">
    <meta property="og:image" content="https://app.siaooficial.com/APP/assets/img/logo_color.png">
    <meta property="og:url" content="https://app.siaooficial.com/APP/login.php">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Club SIAO">
    
    <!-- Favicon -->
    <link rel="icon" href="https://app.siaooficial.com/APP/assets/img/logo_color.png" type="image/png">
    <title>SIAO | Ingreso al sistema</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Modern Login CSS -->
    <link href="assets/css/login.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <!-- Visual Side (Shown on Desktop) -->
        <div class="login-visual">
            <div class="visual-content">
                <h1>SIAO</h1>
                <p class="fs-4 fw-light opacity-75">Control Total del Dojang</p>
                <div class="mt-4">
                    <span class="badge rounded-pill bg-light bg-opacity-10 py-2 px-3 border border-light border-opacity-25">Módulo de Matrícula 2026</span>
                </div>
            </div>
        </div>

        <!-- Form Side -->
        <div class="login-form-side">
            <div class="glass-card">
                <div class="logo-container">
                    <img src="logo_final.png" alt="Logo SIAO">
                </div>
                
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1">Bienvenido</h2>
                    <p class="text-white text-opacity-50">Ingresa tus credenciales</p>
                </div>

                <?php if($login_success): ?>
                    <div class="alert alert-success bg-success bg-opacity-10 text-white border-0 py-2 text-center small mb-4">
                        ✅ <?= htmlspecialchars($login_success) ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger bg-danger bg-opacity-10 text-white border-0 py-2 text-center small mb-4">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <form action="controllers/auth_controller.php" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" placeholder="nombre@ejemplo.com" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-3 mt-2">
                        INICIAR SESIÓN
                    </button>

                    <div class="text-center mt-3">
                        <a href="views/auth/recuperar_password.php" class="text-white text-opacity-40" style="font-size:0.82rem;text-decoration:none;">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <small class="text-white text-opacity-25">
                            Club Deportivo SIAO &copy; 2026
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = this.querySelector('button');
            const card = document.querySelector('.glass-card');
            
            // Agregar clases de animación
            btn.classList.add('btn-loading');
            card.classList.add('submitting');
            
            // El formulario se enviará normalmente después de esto
        });
    </script>
</body>
</html>
