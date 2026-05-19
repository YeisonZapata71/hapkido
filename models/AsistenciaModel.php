<?php
require_once __DIR__.'/../helpers/db.php';

class AsistenciaModel {
    private $conn;

    public function __construct() {
        $this->conn = conectarDB();
    }

    // Guarda la asistencia y evaluación de franjas
    public function guardarAsistencias($fecha, $horario_id, $instructor_id, $asistencias, $franjas, $observaciones) {
        foreach ($asistencias as $afiliado_id => $presente) {
            // $eval_franjas removido porque la columna no existe en BD
            $obs = isset($observaciones[$afiliado_id]) ? $observaciones[$afiliado_id] : null;

            // Verifica si ya existe
            $stmt = $this->conn->prepare("SELECT id FROM asistencia WHERE fecha = ? AND horario_id = ? AND afiliado_id = ?");
            $stmt->bind_param('sii', $fecha, $horario_id, $afiliado_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Actualiza
                $stmt->close();
                // Removida columna evaluacion_franjas
                $update = $this->conn->prepare("UPDATE asistencia SET estado=?, observaciones=? WHERE fecha=? AND horario_id=? AND afiliado_id=?");
                // Mapear booleano a string estado
                $estado = $presente ? 'presente' : 'ausente';
                // Tipos: estado(s), obs(s), fecha(s), horario_id(i), afiliado_id(i) -> sssii
                $update->bind_param('sssii', $estado, $obs, $fecha, $horario_id, $afiliado_id);
                $update->execute();
                $update->close();
            } else {
                // Inserta
                $stmt->close();
                // Removida columna evaluacion_franjas
                $insert = $this->conn->prepare("INSERT INTO asistencia (fecha, horario_id, instructor_id, afiliado_id, estado, observaciones) VALUES (?, ?, ?, ?, ?, ?)");
                $estado = $presente ? 'presente' : 'ausente';
                // Tipos: fecha(s), horario(i), instructor(i), afiliado(i), estado(s), obs(s) -> siiiss
                $insert->bind_param('siiiss', $fecha, $horario_id, $instructor_id, $afiliado_id, $estado, $obs);
                $insert->execute();
                $insert->close();
            }
        }
    }

    // Obtiene el ID de instructor (afiliado) dado un usuario_id
    public function obtenerIdInstructorPorUsuario($usuario_id) {
        $stmt = $this->conn->prepare("SELECT id FROM afiliados_siao WHERE usuario_id = ? AND activo = 1 LIMIT 1");
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            return $row['id'];
        }
        return null;
    }

    // Obtiene el ID del instructor asignado a un horario (Fallback para Admins)
    public function obtenerInstructorIdDeHorario($horario_id) {
        $stmt = $this->conn->prepare("SELECT instructor_id FROM instructor_horario WHERE horario_id = ? LIMIT 1");
        $stmt->bind_param('i', $horario_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            return $row['instructor_id'];
        }
        return null;
    }

    // Obtiene los horarios del usuario actual (solo los asignados si es instructor, todos si es admin)
    public function obtenerHorariosPorUsuario($usuario_id, $es_admin = false) {
    if ($es_admin) {
        $result = $this->conn->query("SELECT id, nombre FROM horarios WHERE activo = 1 ORDER BY nombre ASC");
    } else {
        $stmt = $this->conn->prepare(
            "SELECT h.id, h.nombre 
             FROM instructor_horario ih
             JOIN horarios h ON ih.horario_id = h.id
             JOIN afiliados_siao a ON ih.instructor_id = a.id
             WHERE a.usuario_id = ? AND h.activo = 1"
        );
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}


    // Obtiene deportistas inscritos en un horario (buscando en el campo 'horario' de afiliados_siao)
    // Obtiene deportistas inscritos en un horario (buscando en el campo 'horario' de afiliados_siao)
    public function obtenerDeportistasPorHorario($horario_id) {
        // PASO 1: Obtener el NOMBRE del horario, ya que en afiliados se guarda el nombre (string), no el ID
        $stmt_h = $this->conn->prepare("SELECT nombre FROM horarios WHERE id = ?");
        $stmt_h->bind_param("i", $horario_id);
        $stmt_h->execute();
        $res_h = $stmt_h->get_result();
        $row_h = $res_h->fetch_assoc();
        $stmt_h->close();

        if (!$row_h) return [];
        $nombre_horario = $row_h['nombre'];

        // PASO 2: Buscar ese nombre en la tabla de afiliados
        // ESTRATEGIA ROBUSTA: Split-and-Join
        // 1. Dividimos el nombre en "palabras" o tokens, usando como separadores: espacios, comas y guiones.
        //    Esto elimina la puntuación conflictiva original.
        $partes = preg_split('/[,\s\-]+/', $nombre_horario, -1, PREG_SPLIT_NO_EMPTY);
        
        // 2. Escapamos cada parte individualmente para seguridad regex
        $partes_safe = array_map(function($p) {
            return preg_quote($p);
        }, $partes);
        
        // 3. Reconstruimos el patrón uniendo las partes con un separador flexible
        //    El separador permite: cero o más espacios, comas o guiones.
        //    Usamos * en lugar de + para máxima permisividad (ej: "LunesMiércoles" improbable, pero "Lunes, Miércoles" -> match).
        //    Realmente queremos que haya ALGO de separación si en el original lo había, pero 
        //    [[:space:],\-]+ cubre " ", ",", "-", ", ", " - ", etc.
        $separador = '[[:space:],\-]+';
        $nombre_flexible = implode($separador, $partes_safe);
        
        // Construimos la expresión regular final
        // (^|;) -> Inicio de cadena o después de un punto y coma
        // ... -> El nombre reconstruido flexiblemente
        // (;|$) -> Fin de cadena o antes de un punto y coma
        $regexp = '(^|;)[[:space:]]*' . $nombre_flexible . '[[:space:]]*(;|$)';

        // También mantenemos la búsqueda por ID por si acaso existen registros mixtos
        $p_id_json = '%"' . $horario_id . '"%';

        $stmt = $this->conn->prepare(
            "SELECT id, nombre_completo, documento, grado_cinturon, rol, foto_nombre
             FROM afiliados_siao
             WHERE (
                horario REGEXP ? OR 
                horario LIKE ? OR
                horario = ?
             ) AND activo = 1"
        );
        
        // Bind parameters: s (regexp string), s (like string), s (exact id matches string/int)
        $stmt->bind_param('sss', $regexp, $p_id_json, $horario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    // Consulta historial (filtrado)
    public function consultarHistorial($filtros = []) {
        $sql = "SELECT asi.*, a.nombre_completo, a.documento, h.nombre AS horario_nombre
                FROM asistencia asi
                JOIN afiliados_siao a ON asi.afiliado_id = a.id
                JOIN horarios h ON asi.horario_id = h.id
                WHERE 1=1";
        $params = [];
        $types = '';

        if (!empty($filtros['fecha'])) {
            $sql .= " AND asi.fecha = ?";
            $params[] = $filtros['fecha'];
            $types .= 's';
        }
        if (!empty($filtros['horario_id'])) {
            $sql .= " AND asi.horario_id = ?";
            $params[] = $filtros['horario_id'];
            $types .= 'i';
        }
        if (!empty($filtros['instructor_id'])) {
            $sql .= " AND asi.instructor_id = ?";
            $params[] = $filtros['instructor_id'];
            $types .= 'i';
        }

        $sql .= " ORDER BY asi.fecha DESC, h.nombre ASC, a.nombre_completo ASC";
        $stmt = $this->conn->prepare($sql);

        if (count($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
