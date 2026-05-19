<?php
require_once '../../helpers/auth.php';
verificarSesion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Asistencia - SIAO</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Íconos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #d7e1ec 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            padding: 1rem;
        }
        .hub-container {
            max-width: 900px;
            width: 100%;
        }
        .action-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            text-decoration: none;
            height: 100%;
            display: block;
            overflow: hidden;
            position: relative;
        }
        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .card-1 {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
        }
        .card-2 {
            background: white;
            color: #2c3e50;
        }
        .card-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body>

    <a href="../../dashboard.php" class="btn btn-sm btn-outline-secondary rounded-pill px-4 btn-back">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>

    <div class="hub-container text-center">
        
        <h1 class="mb-2 fw-bold text-dark">Módulo de Asistencia</h1>
        <p class="text-muted mb-5">Selecciona una acción para continuar</p>

        <div class="row g-4 justify-content-center">
            
            <!-- Opción 1: Tomar Lista -->
            <div class="col-md-5">
                <a href="../../controllers/asistencia_controller.php" class="card shadow action-card card-1 p-5">
                    <div class="card-body">
                        <i class="bi bi-clipboard-check card-icon d-block"></i>
                        <h3 class="fw-bold">Tomar Asistencia</h3>
                        <p class="opacity-75">Registrar asistencia de una clase, marcar presentes y ausentes.</p>
                    </div>
                </a>
            </div>

            <!-- Opción 2: Estadísticas -->
            <div class="col-md-5">
                <a href="consultar.php" class="card shadow action-card card-2 p-5">
                    <div class="card-body">
                        <i class="bi bi-graph-up-arrow card-icon d-block text-primary"></i>
                        <h3 class="fw-bold">Reportes y Estadísticas</h3>
                        <p class="text-muted">Ver gráficos de rendimiento, alertas de deserción e historial.</p>
                    </div>
                </a>
            </div>

        </div>
    </div>

</body>
</html>