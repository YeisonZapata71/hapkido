<?php
session_start();
require_once '../../helpers/db.php';

$token = trim($_GET['token'] ?? '');
$error_get = $_GET['error'] ?? '';
$error_session = $_SESSION['reset_form_error'] ?? '';
if ($error_session) unset($_SESSION['reset_form_error']);

$token_valido = false;
$nombre_usuario = '';

if ($token) {
    $conn = conectarDB();
    $stmt = $conn->prepare("
        SELECT pr.token, pr.expira_en, u.nombre
        FROM password_resets pr
        JOIN usuarios u ON u.id = pr.usuario_id
        WHERE pr.token = ?
        LIMIT 1
    ");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $reset = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();

    if ($reset && strtotime($reset['expira_en']) >= time()) {
        $token_valido = true;
        $nombre_usuario = $reset['nombre'];
    }
}

$error_msg = '';
if ($error_get === 'token_invalido') $error_msg = 'El enlace de recuperación no es válido.';
if ($error_get === 'token_expirado') $error_msg = 'El enlace ha expirado. Solicita uno nuevo.';
if ($error_session)                  $error_msg = $error_session;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña — SIAO</title>
    <link rel="icon" href="https://app.siaooficial.com/APP/assets/img/logo_color.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/login.css" rel="stylesheet">
    <style>
        .recover-card { max-width: 420px; width: 100%; }
        .back-link {
            color: rgba(255,255,255,0.5); font-size: 0.85rem;
            text-decoration: none; transition: color 0.2s;
            display: inline-flex; align-items: center; gap: 6px; margin-bottom: 1.5rem;
        }
        .back-link:hover { color: rgba(255,255,255,0.9); }

        /* Indicador de fortaleza */
        .strength-bar {
            height: 4px; border-radius: 4px;
            background: rgba(255,255,255,0.1);
            margin-top: 8px; overflow: hidden;
        }
        .strength-fill {
            height: 100%; width: 0; border-radius: 4px;
            transition: width 0.3s, background 0.3s;
        }
        .strength-label { font-size: 0.75rem; margin-top: 4px; }

        /* Ojo de contraseña */
        .pass-toggle { cursor: pointer; color: rgba(255,255,255,0.4); }
        .pass-toggle:hover { color: rgba(255,255,255,0.8); }

        .match-indicator { font-size: 0.75rem; margin-top: 4px; }

        .error-card {
            text-align: center; padding: 1rem 0;
        }
        .error-icon {
            width: 72px; height: 72px; border-radius: 50%;
            background: rgba(220,53,69,0.15);
            border: 2px solid rgba(220,53,69,0.4);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem; font-size: 2rem;
        }
    </style>
</head>
<body>
<div class="login-container" style="justify-content:center;">
    <div class="login-form-side" style="width:100%;max-width:520px;">
        <div class="glass-card recover-card">

            <a href="../../login.php" class="back-link">&#8592; Volver al inicio de sesión</a>

            <?php if (!$token || !$token_valido): ?>
                <!-- Token inválido o expirado -->
                <div class="error-card">
                    <div class="error-icon">⚠️</div>
                    <h2 class="fw-bold mb-2">Enlace no válido</h2>
                    <p class="text-white text-opacity-50 mb-4">
                        <?= $error_msg ?: 'Este enlace de recuperación no es válido o ha expirado.' ?>
                    </p>
                    <a href="recuperar_password.php" class="btn btn-primary w-100 py-3">
                        Solicitar nuevo enlace
                    </a>
                </div>

            <?php else: ?>
                <!-- Formulario de nueva contraseña -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1">Nueva contraseña</h2>
                    <p class="text-white text-opacity-50">
                        Hola, <strong style="color:rgba(255,255,255,0.85)"><?= htmlspecialchars(explode(' ', $nombre_usuario)[0]) ?></strong>.
                        Elige una contraseña segura.
                    </p>
                </div>

                <?php if ($error_msg): ?>
                    <div class="alert alert-danger bg-danger bg-opacity-10 text-white border-0 py-2 text-center small mb-4">
                        <?= htmlspecialchars($error_msg) ?>
                    </div>
                <?php endif; ?>

                <form action="../../controllers/restablecer_password_controller.php" method="POST" id="formReset">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                    <!-- Nueva contraseña -->
                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control" placeholder="Mínimo 8 caracteres"
                                required minlength="8" autocomplete="new-password">
                            <button type="button" class="btn btn-outline-secondary pass-toggle"
                                onclick="togglePass('password', this)">👁</button>
                        </div>
                        <!-- Barra de fortaleza -->
                        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                        <div class="strength-label text-white text-opacity-40" id="strengthLabel"></div>
                    </div>

                    <!-- Confirmar contraseña -->
                    <div class="mb-4">
                        <label class="form-label">Confirmar contraseña</label>
                        <div class="input-group">
                            <input type="password" name="password_confirm" id="password_confirm"
                                class="form-control" placeholder="Repite la contraseña"
                                required autocomplete="new-password">
                            <button type="button" class="btn btn-outline-secondary pass-toggle"
                                onclick="togglePass('password_confirm', this)">👁</button>
                        </div>
                        <div class="match-indicator" id="matchIndicator"></div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3" id="btnReset">
                        GUARDAR NUEVA CONTRASEÑA
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
    // Toggle visibilidad contraseña
    function togglePass(id, btn) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁';
        }
    }

    // Indicador de fortaleza
    const passInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirm');
    const fill = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    const matchEl = document.getElementById('matchIndicator');

    if (passInput) {
        passInput.addEventListener('input', function() {
            const val = this.value;
            let score = 0;
            if (val.length >= 8)  score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const configs = [
                { w: '0%',   bg: 'transparent',  txt: '' },
                { w: '25%',  bg: '#dc3545',       txt: '🔴 Muy débil' },
                { w: '50%',  bg: '#fd7e14',       txt: '🟠 Débil' },
                { w: '75%',  bg: '#ffc107',       txt: '🟡 Media' },
                { w: '100%', bg: '#28a745',       txt: '🟢 Fuerte' },
            ];
            fill.style.width      = configs[score].w;
            fill.style.background = configs[score].bg;
            label.textContent     = configs[score].txt;
            checkMatch();
        });
    }

    if (confirmInput) {
        confirmInput.addEventListener('input', checkMatch);
    }

    function checkMatch() {
        if (!passInput || !confirmInput || !matchEl) return;
        if (!confirmInput.value) { matchEl.textContent = ''; return; }
        if (passInput.value === confirmInput.value) {
            matchEl.textContent = '✅ Las contraseñas coinciden';
            matchEl.style.color = '#28a745';
        } else {
            matchEl.textContent = '❌ Las contraseñas no coinciden';
            matchEl.style.color = '#dc3545';
        }
    }

    // Validar antes de enviar
    const form = document.getElementById('formReset');
    if (form) {
        form.addEventListener('submit', function(e) {
            const p1 = document.getElementById('password').value;
            const p2 = document.getElementById('password_confirm').value;
            if (p1 !== p2) {
                e.preventDefault();
                matchEl.textContent = '❌ Las contraseñas no coinciden';
                matchEl.style.color = '#dc3545';
                return;
            }
            const btn = document.getElementById('btnReset');
            if (btn) { btn.disabled = true; btn.textContent = 'Guardando...'; }
        });
    }
</script>
</body>
</html>
