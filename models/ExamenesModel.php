<?php
/**
 * Modelo de Exámenes
 * Gestión de exámenes de ascenso de grado
 */

class ExamenesModel {
    private $conn;
    
    public function __construct($conexion) {
        $this->conn = $conexion;
    }
    
    /**
     * Programar un nuevo examen
     */
    public function programarExamen($afiliado_id, $grado_actual, $grado_siguiente, $fecha_examen, $observaciones = null) {
        // Verificar que el deportista existe y su grado actual coincide
        $sql_check = "SELECT id, grado_cinturon, nombre_completo FROM afiliados_siao WHERE id = ?";
        $stmt = $this->conn->prepare($sql_check);
        if (!$stmt) return ['success' => false, 'message' => 'Error de base de datos (Verificar tablas)'];
        
        $stmt->bind_param("i", $afiliado_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$result || $result->num_rows === 0) {
            return ['success' => false, 'message' => 'Deportista no encontrado'];
        }
        
        $deportista = $result->fetch_assoc();
        
        if ($deportista['grado_cinturon'] !== $grado_actual) {
            return [
                'success' => false, 
                'message' => "El grado actual del deportista es '{$deportista['grado_cinturon']}', no '{$grado_actual}'"
            ];
        }
        
        // Crear el examen
        $sql = "INSERT INTO examenes (afiliado_id, grado_actual, grado_siguiente, fecha_examen, observaciones) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return ['success' => false, 'message' => 'Error al preparar examen (¿Tabla creada?)'];
        
        $stmt->bind_param("issss", $afiliado_id, $grado_actual, $grado_siguiente, $fecha_examen, $observaciones);
        
        if ($stmt->execute()) {
            return [
                'success' => true, 
                'message' => "Examen programado exitosamente para {$deportista['nombre_completo']}",
                'id' => $stmt->insert_id
            ];
        } else {
            return ['success' => false, 'message' => 'Error al programar examen: ' . $stmt->error];
        }
    }
    
    /**
     * Obtener todos los exámenes
     */
    public function obtenerExamenes($limit = 100) {
        $sql = "SELECT e.*, a.nombre_completo, a.documento, a.horario
                FROM examenes e
                JOIN afiliados_siao a ON e.afiliado_id = a.id
                ORDER BY e.fecha_examen DESC
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [];
        
        $stmt->bind_param("i", $limit);
        if (!$stmt->execute()) return [];
        
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Obtener un examen específico
     */
    public function obtenerExamen($id) {
        $sql = "SELECT e.*, a.nombre_completo, a.documento, a.horario, a.grado_cinturon
                FROM examenes e
                JOIN afiliados_siao a ON e.afiliado_id = a.id
                WHERE e.id = ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;
        
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) return null;
        
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Obtener exámenes de un deportista específico
     */
    public function obtenerExamenesPorDeportista($afiliado_id) {
        $sql = "SELECT * FROM examenes 
                WHERE afiliado_id = ? 
                ORDER BY fecha_examen DESC";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [];
        
        $stmt->bind_param("i", $afiliado_id);
        if (!$stmt->execute()) return [];
        
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Obtener próximos exámenes (dentro de X días)
     */
    public function obtenerProximosExamenes($dias = 30) {
        $sql = "SELECT e.*, a.nombre_completo, a.documento, a.horario
                FROM examenes e
                JOIN afiliados_siao a ON e.afiliado_id = a.id
                WHERE e.fecha_examen >= CURDATE() 
                AND e.fecha_examen <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
                AND e.aprobado IS NULL
                ORDER BY e.fecha_examen ASC";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [];
        
        $stmt->bind_param("i", $dias);
        if (!$stmt->execute()) return [];
        
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Obtener exámenes pendientes de calificación (fecha pasada, sin calificar)
     */
    public function obtenerExamenesPendientesCalificacion() {
        $sql = "SELECT e.*, a.nombre_completo, a.documento, a.horario
                FROM examenes e
                JOIN afiliados_siao a ON e.afiliado_id = a.id
                WHERE e.aprobado IS NULL 
                AND e.fecha_examen < CURDATE()
                ORDER BY e.fecha_examen ASC";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [];
        
        if (!$stmt->execute()) return [];
        
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Calificar un examen (aprobar o reprobar)
     */
    public function calificarExamen($id, $aprobado, $observaciones = null) {
        // Verificar que el examen existe y no ha sido calificado
        $examen = $this->obtenerExamen($id);
        
        if (!$examen) {
            return ['success' => false, 'message' => 'Examen no encontrado'];
        }
        
        if ($examen['aprobado'] !== null) {
            return ['success' => false, 'message' => 'Este examen ya ha sido calificado'];
        }
        
        // Actualizar el examen
        $sql = "UPDATE examenes SET aprobado = ?, observaciones = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isi", $aprobado, $observaciones, $id);
        
        if ($stmt->execute()) {
            $resultado = $aprobado == 1 ? 'APROBADO' : 'REPROBADO';
            $mensaje = "Examen calificado como {$resultado}";
            
            if ($aprobado == 1) {
                $mensaje .= ". El grado del deportista se actualizó automáticamente a '{$examen['grado_siguiente']}'";
            }
            
            return ['success' => true, 'message' => $mensaje];
        } else {
            return ['success' => false, 'message' => 'Error al calificar examen: ' . $stmt->error];
        }
    }
    
    /**
     * Actualizar información de un examen (solo si no ha sido calificado)
     */
    public function actualizarExamen($id, $datos) {
        // Verificar que el examen existe y no ha sido calificado
        $examen = $this->obtenerExamen($id);
        
        if (!$examen) {
            return ['success' => false, 'message' => 'Examen no encontrado'];
        }
        
        if ($examen['aprobado'] !== null) {
            return ['success' => false, 'message' => 'No se puede editar un examen ya calificado'];
        }
        
        // Construir query dinámicamente
        $campos = [];
        $valores = [];
        $tipos = "";
        
        if (isset($datos['fecha_examen'])) {
            $campos[] = "fecha_examen = ?";
            $valores[] = $datos['fecha_examen'];
            $tipos .= "s";
        }
        
        if (isset($datos['observaciones'])) {
            $campos[] = "observaciones = ?";
            $valores[] = $datos['observaciones'];
            $tipos .= "s";
        }
        
        if (empty($campos)) {
            return ['success' => false, 'message' => 'No hay datos para actualizar'];
        }
        
        $valores[] = $id;
        $tipos .= "i";
        
        $sql = "UPDATE examenes SET " . implode(", ", $campos) . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($tipos, ...$valores);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Examen actualizado exitosamente'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar examen: ' . $stmt->error];
        }
    }
    
    /**
     * Contar próximos exámenes
     */
    public function contarProximosExamenes($dias = 30) {
        $sql = "SELECT COUNT(*) as total FROM examenes 
                WHERE fecha_examen >= CURDATE() 
                AND fecha_examen <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
                AND aprobado IS NULL";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return 0;
        
        $stmt->bind_param("i", $dias);
        if (!$stmt->execute()) return 0;
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return intval($row['total'] ?? 0);
    }
    
    /**
     * Obtener estadísticas de exámenes
     */
    public function obtenerEstadisticas() {
        $stats = [];
        
        // Total de exámenes
        $result = $this->conn->query("SELECT COUNT(*) as total FROM examenes");
        $stats['total_examenes'] = $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;
        
        // Aprobados
        $result = $this->conn->query("SELECT COUNT(*) as total FROM examenes WHERE aprobado = 1");
        $stats['aprobados'] = $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;
        
        // Reprobados
        $result = $this->conn->query("SELECT COUNT(*) as total FROM examenes WHERE aprobado = 0");
        $stats['reprobados'] = $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;
        
        // Pendientes
        $result = $this->conn->query("SELECT COUNT(*) as total FROM examenes WHERE aprobado IS NULL");
        $stats['pendientes'] = $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;
        
        // Tasa de aprobación
        if ($stats['total_examenes'] > 0) {
            $stats['tasa_aprobacion'] = round(($stats['aprobados'] / $stats['total_examenes']) * 100, 2);
        } else {
            $stats['tasa_aprobacion'] = 0;
        }
        
        return $stats;
    }
}
?>
