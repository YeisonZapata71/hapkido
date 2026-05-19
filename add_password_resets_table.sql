-- ============================================================
-- SIAO App - Tabla para restablecimiento de contraseña
-- Ejecutar en phpMyAdmin sobre la BD: siao_formulario
-- ============================================================

CREATE TABLE IF NOT EXISTS password_resets (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT NOT NULL,
    token       VARCHAR(64) NOT NULL UNIQUE,
    expira_en   DATETIME NOT NULL,
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
