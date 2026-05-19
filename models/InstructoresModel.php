<?php
// models/InstructoresModel.php
require_once __DIR__ . '/../helpers/db.php';

class InstructoresModel {
    private $conn;
    
    public function __construct() {
        $this->conn = conectarDB();
    }
    
    /**
     * Lista todos los instructores con su información completa
     */
    public function listarInstructores($busqueda = '') {
        if (!empty($busqueda)) {
            $busqueda = "%$busqueda%";
            $stmt = $this->conn->prepare("
                SELECT a.*, u.activo as usuario_activo,
                       GROUP_CONCAT(h.nombre SEPARATOR ', ') as horarios_asignados
                FROM afiliados_siao a
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                LEFT JOIN instructor_horario ih ON a.id = ih.instructor_id
                LEFT JOIN horarios h ON ih.horario_id = h.id
                WHERE a.rol = 'instructor' 
                AND (a.nombre_completo LIKE ? OR a.documento LIKE ? OR a.correo LIKE ? OR a.celular LIKE ?)
                GROUP BY a.id
                ORDER BY a.nombre_completo ASC
            ");
            $stmt->bind_param('ssss', $busqueda, $busqueda, $busqueda, $busqueda);
        } else {
            $stmt = $this->conn->prepare("
                SELECT a.*, u.activo as usuario_activo,
                       GROUP_CONCAT(h.nombre SEPARATOR ', ') as horarios_asignados
                FROM afiliados_siao a
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                LEFT JOIN instructor_horario ih ON a.id = ih.instructor_id
                LEFT JOIN horarios h ON ih.horario_id = h.id
                WHERE a.rol = 'instructor'
                GROUP BY a.id
                ORDER BY a.nombre_completo ASC
            ");
        }
        
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Busca un usuario por correo electrónico
     */
    public function buscarUsuarioPorCorreo($correo) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Crea un nuevo usuario en el sistema
     */
    public function crearUsuario($nombre, $correo, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, 'instructor')");
        $stmt->bind_param('sss', $nombre, $correo, $passwordHash);
        $stmt->execute();
        return $this->conn->insert_id;
    }
    
    /**
     * Vincula un afiliado con un usuario del sistema
     */
    public function vincularUsuarioAfiliado($afiliado_id, $usuario_id) {
        $stmt = $this->conn->prepare("UPDATE afiliados_siao SET usuario_id = ? WHERE id = ?");
        $stmt->bind_param('ii', $usuario_id, $afiliado_id);
        return $stmt->execute();
    }
    
    /**
     * Resetea la contraseña de un usuario
     */
    public function resetearPassword($usuario_id, $nueva_password) {
        $passwordHash = password_hash($nueva_password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt->bind_param('si', $passwordHash, $usuario_id);
        return $stmt->execute();
    }
    
    /**
     * Suspende/reactiva un usuario
     */
    public function suspenderUsuario($usuario_id) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET activo = NOT activo WHERE id = ?");
        $stmt->bind_param('i', $usuario_id);
        return $stmt->execute();
    }
    
    /**
     * Actualiza el rol de un usuario en la tabla usuarios
     */
    public function actualizarRolUsuario($usuario_id, $rol) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
        $stmt->bind_param('si', $rol, $usuario_id);
        return $stmt->execute();
    }

    /**
     * Asigna rol de instructor a un afiliado
     */
    public function asignarRolInstructor($afiliado_id) {
        $stmt = $this->conn->prepare("UPDATE afiliados_siao SET rol = 'instructor' WHERE id = ?");
        $stmt->bind_param('i', $afiliado_id);
        return $stmt->execute();
    }
    
    /**
     * Quita rol de instructor a un afiliado
     */
    public function quitarRolInstructor($afiliado_id) {
        // También eliminar horarios asignados
        $this->conn->prepare("DELETE FROM instructor_horario WHERE instructor_id = ?")->execute([$afiliado_id]);
        
        $stmt = $this->conn->prepare("UPDATE afiliados_siao SET rol = 'afiliado' WHERE id = ?");
        $stmt->bind_param('i', $afiliado_id);
        return $stmt->execute();
    }
    
    /**
     * Obtiene los horarios asignados a un instructor
     */
    public function obtenerHorariosInstructor($instructor_id) {
        $stmt = $this->conn->prepare("
            SELECT h.* FROM horarios h
            JOIN instructor_horario ih ON h.id = ih.horario_id
            WHERE ih.instructor_id = ? AND h.activo = 1
            ORDER BY h.nombre ASC
        ");
        $stmt->bind_param('i', $instructor_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Asigna horarios a un instructor
     */
    public function asignarHorarios($instructor_id, $horarios_ids) {
        // Primero eliminar horarios existentes
        $stmt = $this->conn->prepare("DELETE FROM instructor_horario WHERE instructor_id = ?");
        $stmt->bind_param('i', $instructor_id);
        $stmt->execute();
        
        // Luego asignar los nuevos
        if (!empty($horarios_ids)) {
            $stmt = $this->conn->prepare("INSERT INTO instructor_horario (instructor_id, horario_id) VALUES (?, ?)");
            foreach ($horarios_ids as $horario_id) {
                $stmt->bind_param('ii', $instructor_id, $horario_id);
                $stmt->execute();
            }
        }
        return true;
    }
    
    /**
     * Obtiene todos los horarios disponibles
     */
    public function obtenerTodosLosHorarios() {
        $stmt = $this->conn->prepare("SELECT * FROM horarios WHERE activo = 1 ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtiene información completa de un instructor
     */
    public function obtenerInstructor($instructor_id) {
        $stmt = $this->conn->prepare("
            SELECT a.*, u.activo as usuario_activo, u.correo as email_usuario
            FROM afiliados_siao a
            LEFT JOIN usuarios u ON a.usuario_id = u.id
            WHERE a.id = ? AND a.rol = 'instructor'
        ");
        $stmt->bind_param('i', $instructor_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Actualiza información de un instructor
     */
    public function actualizarInstructor($instructor_id, $datos) {
        $horario = isset($datos['horario']) && is_array($datos['horario']) 
            ? implode(', ', $datos['horario']) 
            : ($datos['horario'] ?? '');
            
        $stmt = $this->conn->prepare("
            UPDATE afiliados_siao SET 
            nombre_completo = ?, 
            documento = ?, 
            celular = ?, 
            correo = ?, 
            ciudad = ?, 
            horario = ?,
            foto_nombre = ?
            WHERE id = ?
        ");
        
        $stmt->bind_param('sssssssi', 
            $datos['nombre_completo'],
            $datos['documento'],
            $datos['celular'],
            $datos['correo'],
            $datos['ciudad'],
            $horario,
            $datos['foto_nombre'],
            $instructor_id
        );
        
        return $stmt->execute();
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>