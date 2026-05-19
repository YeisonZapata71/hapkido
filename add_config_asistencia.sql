-- ============================================================
-- SIAO App - Tabla configuración ventana especial de asistencia
-- Ejecutar en phpMyAdmin sobre la BD: siao_formulario
-- ============================================================

CREATE TABLE IF NOT EXISTS config_asistencia (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    clave       VARCHAR(50) NOT NULL UNIQUE,
    valor       TEXT,
    actualizado TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Valores iniciales
INSERT INTO config_asistencia (clave, valor) VALUES
  ('ventana_activa',  '0'),
  ('ventana_desde',   NULL),
  ('ventana_hasta',   NULL),
  ('ventana_motivo',  NULL)
ON DUPLICATE KEY UPDATE clave = clave;
