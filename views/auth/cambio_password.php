<?php
require_once '../../helpers/auth.php';
verificarSesion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña - SIAO</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Íconos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
        }
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #d7e1ec 100%);
            min-height: 100vh;
            font-family: 'Outfit', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .password-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
            border-top: 5px solid var(--primary-color);
        }
        .card-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-label {
            font-weight: 600;
            color: #4a5568;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }
        .btn-primary {
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-back {
            color: var(--secondary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        .btn-back:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="password-card">
        <a href="../../dashboard.php" class="btn-back">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
        <h2 class="card-title">Cambiar Contraseña</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ¡Contraseña actualizada correctamente!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="../../controllers/cambio_password_controller.php" method="POST">
            <div class="mb-3">
                <label for="current_password" class="form-label">Contraseña Actual</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock"></i></span>
                    <input type="password" class="form-control border-start-0" id="current_password" name="current_password" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Nueva Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-key"></i></span>
                    <input type="password" class="form-control border-start-0" id="new_password" name="new_password" required minlength="6">
                </div>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-key-fill"></i></span>
                    <input type="password" class="form-control border-start-0" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-save me-2"></i>Actualizar Contraseña
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side validation for password matching
        const form = document.querySelector('form');
        const newPass = document.getElementById('new_password');
        const confirmPass = document.getElementById('confirm_password');

        form.addEventListener('submit', function(e) {
            if (newPass.value !== confirmPass.value) {
                e.preventDefault();
                alert('Las contraseñas nuevas no coinciden.');
            }
        });
    </script>
</body>
</html>
