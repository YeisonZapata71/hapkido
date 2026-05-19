<?php
require_once '../../helpers/db.php';
$conn = conectarDB();

$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM afiliados_siao WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$afiliado = $stmt->get_result()->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carné Digital | Club Deportivo SIAO</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:wght@400;600&family=Noto+Serif+JP:wght@700&display=swap');

    body {
      background: #f0f2f5;
      font-family: 'Open Sans', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    /* CARNET CONTAINER */
    .carnet-h {
      width: 100%;
      max-width: 600px; /* Slightly wider */
      aspect-ratio: 1.586; 
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: row;
      border: none;
      margin-bottom: 30px;
    }

    /* Watermark Main Area (Subtle Kanji) */
    .carnet-watermark {
      position: absolute;
      top: 10%;
      right: 5%;
      font-family: 'Noto Serif JP', serif;
      font-size: 180px;
      color: #000;
      opacity: 0.03;
      pointer-events: none;
      z-index: 0;
      line-height: 1;
    }
    .carnet-watermark::after {
      content: '道'; /* "Do" - The Way (Martial Arts) */
    }

    /* SIDEBAR (Left) */
    .carnet-sidebar {
      width: 38%;
      background-color: #1a2a6c;
      /* Imagen de patrón subida por el usuario */
      background-image: url('../../assets/img/patron_carnet.png'); 
      background-size: cover;
      background-position: center;
      
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      z-index: 1;
      border-right: 3px solid #d63031;
    }
    
    .member-status {
      margin-top: 15px;
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-size: 0.85rem;
      color: #fff;
      text-shadow: 0 2px 4px rgba(0,0,0,0.5);
      background: rgba(0,0,0,0.3);
      padding: 6px 16px;
      border-radius: 20px;
      border: 1px solid rgba(255,255,255,0.2);
    }

    /* LOGO - Bigger & No Circle */
    .logo-container {
      width: 100%;
      display: flex;
      justify-content: center;
      margin-bottom: 25px;
      /* Removed border/background/radius */
    }
    
    .carnet-sidebar img.logo {
      width: 130px; /* Much bigger */
      height: auto;
      object-fit: contain;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
    }

    .foto-marco {
      width: 120px;
      height: 120px;
      aspect-ratio: 1 / 1; /* Force square ratio */
      flex-shrink: 0; /* Prevent squeezing in flex container */
      border-radius: 50%; /* Circle */
      border: 4px solid #fff;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(0,0,0,0.25);
      background: #fff;
      margin-bottom: 15px;
      position: relative;
    }
    
    .foto-marco::after {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      border-radius: 50%;
      border: 1px solid rgba(0,0,0,0.1);
      pointer-events: none;
    }

    .carnet-sidebar img.foto-afiliado {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .member-status {
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-size: 0.85rem;
      color: #fff;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
      background: rgba(0,0,0,0.2);
      padding: 4px 12px;
      border-radius: 20px;
    }

    /* MAIN CONTENT (Right) */
    .carnet-content {
      flex: 1;
      padding: 30px;
      z-index: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between; /* Spread out */
      position: relative;
    }

    .carnet-header {
      margin-bottom: 15px;
    }

    .carnet-header h4 {
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      color: #1a1a1a;
      font-size: 1.8rem;
      margin-bottom: 5px;
      text-transform: uppercase;
      letter-spacing: -0.5px;
      line-height: 1;
    }
    
    .carnet-id-badge {
      display: inline-block;
      background: #d63031; /* Red badge */
      color: #fff;
      padding: 3px 10px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: 700;
      font-family: 'Montserrat', sans-serif;
      box-shadow: 0 2px 5px rgba(214, 48, 49, 0.3);
    }

    .info-grid {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-top: 10px;
    }

    .info-item {
      display: flex;
      flex-direction: column;
    }

    .info-label {
      font-family: 'Montserrat', sans-serif;
      font-size: 0.65rem;
      text-transform: uppercase;
      color: #999;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 2px;
    }

    .info-value {
      font-size: 1.1rem;
      color: #2c3e50;
      font-weight: 600;
      border-bottom: 2px solid #f0f0f0;
      padding-bottom: 2px;
    }
    
    .accent-red {
      color: #d63031;
    }
    
    /* Footer inside styling */
    .carnet-inner-footer {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      border-top: 2px solid #f0f0f0;
      padding-top: 15px;
    }
    
    .footer-label {
      font-size: 0.6rem;
      color: #aaa;
      text-transform: uppercase;
      display: block;
    }
    
    .footer-val {
      font-size: 0.9rem;
      font-weight: 700;
      color: #444;
    }

    /* Responsive */
    @media (max-width: 600px) {
      .carnet-h {
        flex-direction: column;
        aspect-ratio: unset;
        height: auto;
      }
      .carnet-sidebar {
        width: 100%;
        flex-direction: row;
        justify-content: space-between;
        padding: 20px;
        border-right: none;
        border-bottom: 3px solid #d63031;
      }
      .logo-container { width: auto; margin-bottom: 0; }
      .carnet-sidebar img.logo { width: 60px; }
      
      .foto-marco { 
        width: 80px; height: 80px; 
        margin-bottom: 0; 
        border-width: 3px;
      }
      .member-status { display: none; }
      .carnet-content { padding: 25px; }
      .carnet-header h4 { font-size: 1.5rem; }
      .carnet-watermark { font-size: 100px; top: 15%; }
    }
  </style>
</head>
<body>
  <div class="carnet-h" id="carnet-digital">
    
    <!-- Watermark Background -->
    <div class="carnet-warning"></div> 
    
    <!-- Sidebar -->
    <div class="carnet-sidebar">
      <div class="logo-container">
        <img src="../../assets/img/logo_siao.png" class="logo" alt="Logo SIAO">
      </div>
      <div class="foto-marco">
        <img src="../../assets/uploads/fotos/<?= htmlspecialchars($afiliado['foto_nombre']) ?>" class="foto-afiliado" alt="Foto">
      </div>
      <div class="member-status">Afiliado Activo</div>
    </div>

    <!-- Main Content -->
    <div class="carnet-content">
      <div class="carnet-header">
        <h4><?= htmlspecialchars($afiliado['nombre_completo']) ?></h4>
        <span class="carnet-id-badge">ID: <?= htmlspecialchars($afiliado['documento']) ?></span>
      </div>

      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">Grado / Cinturón</span>
          <span class="info-value accent-red"><?= htmlspecialchars($afiliado['grado_cinturon']) ?></span>
        </div>
        
        <div class="row">
          <div class="col-6 info-item">
            <span class="info-label">Ciudad</span>
            <span class="info-value"><?= htmlspecialchars($afiliado['ciudad']) ?></span>
          </div>
          <div class="col-6 info-item">
            <span class="info-label">Teléfono</span>
            <span class="info-value"><?= htmlspecialchars($afiliado['celular']) ?></span>
          </div>
        </div>
        
        <div class="info-item">
            <span class="info-label">Fecha de Inscripción</span>
            <span class="info-value"><?= htmlspecialchars($afiliado['fecha_inscripcion']) ?></span>
        </div>
      </div>

      <div class="carnet-inner-footer">
        <div>
          <span class="footer-label">Emitido por:</span>
          <span class="footer-val">Club Deportivo SIAO</span>
        </div>
        <div class="text-end">
          <span class="footer-label">Válido hasta:</span>
          <span class="footer-val">Dic <?= date('Y') ?></span>
        </div>
      </div>
    </div>
  </div>
    <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center my-3">
  <!-- Botón Descargar Carné como Imagen -->
  <button id="descargar-carnet" class="btn btn-success d-flex align-items-center px-3">
    <i class="bi bi-download me-2" style="font-size: 1.2rem;"></i>
    Descargar carné como imagen
  </button>

  <!-- Botón Compartir por WhatsApp -->
  <a href="https://wa.me/?text=Mi%20Carné%20Digital%20Club%20SIAO:%20<?= urlencode('https://app.siaooficial.com/APP/views/afiliados/carnet.php?id='.$afiliado['id']) ?>" 
     target="_blank" 
     class="btn btn-outline-success d-flex align-items-center px-3"
     title="Compartir por WhatsApp">
    <i class="bi bi-whatsapp me-2" style="font-size: 1.3rem;"></i>
    Compartir WhatsApp
  </a>
</div>

  <!-- Enviar por Email 
  <a href="enviar_carnet.php?id=<?= $afiliado['id'] ?>" class="btn btn-primary btn-sm">
    <i class="bi bi-envelope"></i> Enviar por Email
  </a>-->
 
  <!--imagen de carnet-->
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
</body>
    <script>
    document.getElementById('descargar-carnet').addEventListener('click', function() {
      const carnet = document.getElementById('carnet-digital');
      
      // Asegurar que no haya bordes de selección
      carnet.style.borderRadius = "0"; 
      
      html2canvas(carnet, {
        scale: 3, // Mayor calidad
        useCORS: true,
        backgroundColor: null, // Transparencia si aplica
        logging: false
      }).then(function(canvas) {
        // Restaurar borde
        carnet.style.borderRadius = "20px";
        
        var link = document.createElement('a');
        link.download = 'Carnet_<?= preg_replace("/[^a-zA-Z0-9]/", "_", $afiliado["nombre_completo"]) ?>.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
      });
    });
    </script>
</html>
