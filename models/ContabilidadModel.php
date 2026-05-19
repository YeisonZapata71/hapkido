<?php
require_once __DIR__ . '/../helpers/db.php';

class ContabilidadModel {
    private $conn;

    public function __construct() {
        $this->conn = conectarDB();
    }

    // --- SECCIÓN 1: RUBROS Y PRESUPUESTO ---

    public function obtenerRubros() {
        $result = $this->conn->query("SELECT * FROM contabilidad_rubros WHERE activo = 1");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function actualizarRubro($id, $nombre, $porcentaje) {
        $stmt = $this->conn->prepare("UPDATE contabilidad_rubros SET nombre = ?, porcentaje_defecto = ? WHERE id = ?");
        $stmt->bind_param('sdi', $nombre, $porcentaje, $id);
        return $stmt->execute();
    }

    public function obtenerPresupuestoAnual($anio) {
        $stmt = $this->conn->prepare("SELECT * FROM contabilidad_presupuesto_anual WHERE anio = ?");
        $stmt->bind_param('i', $anio);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function guardarPresupuestoAnual($anio, $meta, $saldo_anterior) {
        // Upsert (Insert or Update)
        $stmt = $this->conn->prepare(
            "INSERT INTO contabilidad_presupuesto_anual (anio, meta_ingreso_total, saldo_anterior) 
             VALUES (?, ?, ?) 
             ON DUPLICATE KEY UPDATE meta_ingreso_total = VALUES(meta_ingreso_total), saldo_anterior = VALUES(saldo_anterior)"
        );
        $stmt->bind_param('idd', $anio, $meta, $saldo_anterior);
        return $stmt->execute();
    }

    // --- SECCIÓN 1.5: CUENTAS BANCARIAS ---
    
    public function obtenerCuentas() {
        $result = $this->conn->query("SELECT * FROM contabilidad_cuentas_bancarias WHERE activo = 1");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function crearCuenta($nombre, $tipo, $saldo_inicial, $numero_cuenta) {
        $stmt = $this->conn->prepare("INSERT INTO contabilidad_cuentas_bancarias (nombre, tipo_cuenta, saldo_actual, numero_cuenta) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssds', $nombre, $tipo, $saldo_inicial, $numero_cuenta);
        return $stmt->execute();
    }

    public function actualizarCuenta($id, $nombre, $tipo, $numero_cuenta) {
        $stmt = $this->conn->prepare("UPDATE contabilidad_cuentas_bancarias SET nombre=?, tipo_cuenta=?, numero_cuenta=? WHERE id=?");
        $stmt->bind_param('sssi', $nombre, $tipo, $numero_cuenta, $id);
        return $stmt->execute();
    }
    
    public function toggleCuenta($id, $estado) {
        $stmt = $this->conn->prepare("UPDATE contabilidad_cuentas_bancarias SET activo=? WHERE id=?");
        $stmt->bind_param('ii', $estado, $id);
        return $stmt->execute();
    }

    private function actualizarSaldoCuenta($cuenta_id, $monto, $tipo_movimiento) {
        // tipo_movimiento: 'ingreso' (suma), 'egreso' (resta)
        // Si monto es negativo en reversión, la lógica debe ser consistente.
        // Aquí asumimos monto positivo.
        
        if ($tipo_movimiento == 'ingreso') {
            $sql = "UPDATE contabilidad_cuentas_bancarias SET saldo_actual = saldo_actual + ? WHERE id = ?";
        } else {
            $sql = "UPDATE contabilidad_cuentas_bancarias SET saldo_actual = saldo_actual - ? WHERE id = ?";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('di', $monto, $cuenta_id);
        return $stmt->execute();
    }

    // --- SECCIÓN 2: CONCEPTOS ---

    // --- SECCIÓN 2: CONCEPTOS ---

    public function obtenerConceptos($tipo = null) {
        $sql = "SELECT c.*, r.nombre as rubro_default_nombre 
                FROM contabilidad_conceptos c 
                LEFT JOIN contabilidad_rubros r ON c.rubro_predeterminado_id = r.id
                WHERE c.activo = 1";
        if ($tipo) {
            $sql .= " AND c.tipo = '$tipo'";
        }
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function crearConcepto($nombre, $tipo, $rubro_default = null) {
        $stmt = $this->conn->prepare("INSERT INTO contabilidad_conceptos (nombre, tipo, rubro_predeterminado_id) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $nombre, $tipo, $rubro_default);
        return $stmt->execute();
    }

    public function actualizarConcepto($id, $nombre, $tipo_nuevo) {
        // Nota: Cambiar el tipo de un concepto puede afectar reportes históricos si no se maneja con cuidado.
        // Por ahora permitimos cambio simple.
        $stmt = $this->conn->prepare("UPDATE contabilidad_conceptos SET nombre = ?, tipo = ? WHERE id = ?");
        $stmt->bind_param('ssi', $nombre, $tipo_nuevo, $id);
        return $stmt->execute();
    }

    public function obtenerConcepto($id) {
        $result = $this->conn->query("SELECT * FROM contabilidad_conceptos WHERE id=$id");
        return $result->fetch_assoc();
    }

    // Gestionar distribución de UN concepto
    public function guardarDistribucionConcepto($concepto_id, $distribucion) {
        // distribucion = [rubro_id => porcentaje]
        // 1. Limpiar previa
        $this->conn->query("DELETE FROM contabilidad_concepto_distribucion WHERE concepto_id = $concepto_id");
        
        // 2. Insertar nueva
        $stmt = $this->conn->prepare("INSERT INTO contabilidad_concepto_distribucion (concepto_id, rubro_id, porcentaje) VALUES (?, ?, ?)");
        foreach ($distribucion as $rubro_id => $pct) {
            if ($pct > 0) {
                $stmt->bind_param('iid', $concepto_id, $rubro_id, $pct);
                $stmt->execute();
            }
        }
        return true;
    }

    public function obtenerDistribucionConcepto($concepto_id) {
        $sql = "SELECT d.*, r.nombre as rubro_nombre 
                FROM contabilidad_concepto_distribucion d
                JOIN contabilidad_rubros r ON d.rubro_id = r.id
                WHERE d.concepto_id = $concepto_id";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // --- SECCIÓN 3: MOVIMIENTOS (INGRESOS / EGRESOS) ---

    public function registrarIngreso($datos) {
        // 1. Obtener distribución ESPECÍFICA del concepto
        $dist_reglas = $this->obtenerDistribucionConcepto($datos['concepto_id']);
        
        $distribucion_real = [];
        
        if (empty($dist_reglas)) {
            // Fallback: Si no tiene reglas, todo va a "Sin Clasificar" o se marca error. 
            // Para evitar errores fatales, lo asignamos a un "rubro fantasma" o al primer rubro disponible por ahora.
            // OJO: El usuario DEBE configurar esto.
            // Opción B: Usar distribucion global (legacy) si existiera. Pero vamos a forzar la específica.
        } else {
            foreach ($dist_reglas as $regla) {
                $monto_rubro = ($datos['monto'] * $regla['porcentaje']) / 100;
                $distribucion_real[$regla['rubro_id']] = [
                    'nombre' => $regla['rubro_nombre'],
                    'porcentaje' => $regla['porcentaje'],
                    'monto' => $monto_rubro
                ];
            }
        }
        
        $distribucion_json = json_encode($distribucion_real);

        $stmt = $this->conn->prepare(
            "INSERT INTO contabilidad_movimientos 
            (fecha, tipo, concepto_id, tercero_id, tercero_nombre, monto, cuenta_id, comprobante_banco, banco_origen, observaciones, detalle_distribucion_json, usuario_registro_id)
            VALUES (?, 'ingreso', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $cuenta_id = $datos['cuenta_id'] ?? null;

        $stmt->bind_param(
            'siississssi', 
            $datos['fecha'], 
            $datos['concepto_id'], 
            $datos['tercero_id'], 
            $datos['tercero_nombre'], 
            $datos['monto'], 
            $cuenta_id,
            $datos['comprobante'], 
            $datos['banco'], // Este campo banco_origen queda como referencia de texto si es externo
            $datos['observaciones'], 
            $distribucion_json,
            $datos['usuario_id']
        );
        
        if ($stmt->execute()) {
            // Actualizar Saldo Cuenta (SUMAR)
            if ($cuenta_id) {
                $this->actualizarSaldoCuenta($cuenta_id, $datos['monto'], 'ingreso');
            }
            return true;
        }
        return false;
    }

    public function registrarEgreso($datos) {
        // En egresos, el usuario selecciona el rubro manual O viene predeterminado.
        // Aquí asumimos que el controlador ya resolvió qué rubro_id usar.
        $distribucion = [
            $datos['rubro_id'] => [
                'monto' => $datos['monto'], 
                'nota' => 'Egreso directo'
            ]
        ];
        $distribucion_json = json_encode($distribucion);

        $stmt = $this->conn->prepare(
            "INSERT INTO contabilidad_movimientos 
            (fecha, tipo, concepto_id, tercero_id, tercero_nombre, monto, cuenta_id, comprobante_banco, banco_origen, observaciones, detalle_distribucion_json, usuario_registro_id)
            VALUES (?, 'egreso', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $cuenta_id = $datos['cuenta_id'] ?? null;
        $tercero_id = !empty($datos['tercero_id']) ? $datos['tercero_id'] : null;

        $stmt->bind_param(
            'siississssi', 
            $datos['fecha'], 
            $datos['concepto_id'], 
            $tercero_id,
            $datos['beneficiario'], 
            $datos['monto'], 
            $cuenta_id,
            $datos['comprobante'], 
            $datos['banco'], 
            $datos['observaciones'], 
            $distribucion_json,
            $datos['usuario_id']
        );

        if ($stmt->execute()) {
            // Actualizar Saldo Cuenta (RESTAR)
            if ($cuenta_id) {
                $this->actualizarSaldoCuenta($cuenta_id, $datos['monto'], 'egreso');
            }
            return true;
        }
        return false;
    }

    public function obtenerMovimiento($id) {
        // Join con conceptos, cuentas y terceros para info completa
        $sql = "SELECT m.*, c.nombre as concepto_nombre, cb.nombre as cuenta_nombre,
                a.nombre_completo, a.documento as tercero_documento_real,
                CASE WHEN m.tercero_nombre != '' THEN m.tercero_nombre 
                     WHEN a.nombre_completo IS NOT NULL THEN a.nombre_completo
                     ELSE '' END as tercero_final
                FROM contabilidad_movimientos m
                LEFT JOIN contabilidad_conceptos c ON m.concepto_id = c.id
                LEFT JOIN contabilidad_cuentas_bancarias cb ON m.cuenta_id = cb.id
                LEFT JOIN afiliados_siao a ON m.tercero_id = a.id
                WHERE m.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarMovimiento($datos) {
        /*
         Lógica compleja:
         1. Obtener movimiento ANTERIOR.
         2. Revertir impacto en saldo de cuenta ANTERIOR.
            - Si era Ingreso, RESTAR saldo.
            - Si era Egreso, SUMAR saldo.
         3. Actualizar movimiento.
         4. Aplicar impacto en saldo de cuenta NUEVA.
            - Si es Ingreso, SUMAR saldo.
            - Si es Egreso, RESTAR saldo.
        */
        $mov_ant = $this->obtenerMovimiento($datos['id']);
        if (!$mov_ant) return false;

        $tipo = $mov_ant['tipo']; // 'ingreso' o 'egreso' (no permitimos cambiar tipo de movimiento por seguridad básica, aunque concepto sí)
        
        // REVERTIR
        if ($mov_ant['cuenta_id']) {
            $monto_revertir = $mov_ant['monto'];
            // Si era ingreso, lo restamos para anular. Si era egreso, lo sumamos.
            $accion_reversion = ($tipo == 'ingreso') ? 'egreso' : 'ingreso'; 
            $this->actualizarSaldoCuenta($mov_ant['cuenta_id'], $monto_revertir, $accion_reversion);
        }

        // ACTUALIZAR REGISTRO
        // Dependiendo de si es Ingreso o Egreso, los campos pueden variar un poco, 
        // pero trataremos de generalizar.
        // Asumimos que los datos vienen limpios.
        
        // Recalcular distribución si cambió el monto (solo para Ingresos con reglas, o Egresos simples)
        // Por simplicidad en V2, si cambian monto, mantenemos proporciones del JSON anterior O regeneramos si es simple.
        // Para Ingresos: Re-calcular distribución basada en el concepto actual (si cambió concepto) o el histórico.
        // ESTRATEGIA: Si es ingreso, REGENERAR distribución usando reglas del concepto actual.
        
        $dist_json = $mov_ant['detalle_distribucion_json'];
        
        if ($tipo == 'ingreso') {
            $dist_reglas = $this->obtenerDistribucionConcepto($datos['concepto_id']);
            $dist_real = [];
            if (!empty($dist_reglas)) {
                foreach ($dist_reglas as $regla) {
                    $monto_rubro = ($datos['monto'] * $regla['porcentaje']) / 100;
                    $dist_real[$regla['rubro_id']] = [
                        'nombre' => $regla['rubro_nombre'],
                        'porcentaje' => $regla['porcentaje'],
                        'monto' => $monto_rubro
                    ];
                }
                $dist_json = json_encode($dist_real);
            }
            // Si no hay reglas, mantenemos o advertimos.
        } elseif ($tipo == 'egreso') {
            // Egreso suele ser directo a un rubro. Si cambió rubro en edición...
            // Asumimos que $datos['rubro_id'] viene si es edición de egreso
            if (isset($datos['rubro_id'])) {
                $dist_real = [
                    $datos['rubro_id'] => [
                        'monto' => $datos['monto'], 
                        'nota' => 'Egreso editado'
                    ]
                ];
                $dist_json = json_encode($dist_real);
            }
        }

        $stmt = $this->conn->prepare("UPDATE contabilidad_movimientos SET 
            fecha=?, concepto_id=?, monto=?, cuenta_id=?, observaciones=?, detalle_distribucion_json=?
            WHERE id=?");
        
        $cuenta_nueva_id = $datos['cuenta_id'] ?? null;
        
        $stmt->bind_param('sidissi', 
            $datos['fecha'], 
            $datos['concepto_id'], 
            $datos['monto'], 
            $cuenta_nueva_id, 
            $datos['observaciones'], 
            $dist_json,
            $datos['id']
        );
        
        if ($stmt->execute()) {
            // APLICAR NUEVA CUENTA
            if ($cuenta_nueva_id) {
                // Si es Ingreso, SUMAR. Si es Egreso, RESTAR.
                $this->actualizarSaldoCuenta($cuenta_nueva_id, $datos['monto'], $tipo);
            }
            return true;
        }
        return false;
    }

    public function eliminarMovimiento($id) {
        $mov = $this->obtenerMovimiento($id);
        if (!$mov) return false;

        // 1. Revertir Saldo
        if ($mov['cuenta_id']) {
            $accion = ($mov['tipo'] == 'ingreso') ? 'egreso' : 'ingreso';
            $this->actualizarSaldoCuenta($mov['cuenta_id'], $mov['monto'], $accion);
        }

        // 2. Eliminar registro
        $stmt = $this->conn->prepare("DELETE FROM contabilidad_movimientos WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // --- SECCIÓN 4: REPORTES Y DASHBOARD ---

    // Obtener saldo consolidado por rubro (Ingresos distribuidos - Egresos directos)
    public function obtenerSaldosRubros() {
        $rubros = $this->obtenerRubros();
        $saldos = [];
        foreach ($rubros as $r) {
            $saldos[$r['id']] = [
                'nombre' => $r['nombre'],
                'ingresos' => 0,
                'egresos' => 0,
                'saldo' => 0
            ];
        }

        // Procesar todos los movimientos
        // Nota: Si hay muchos movimientos, esto debería optimizarse con vistas materializadas o tablas de saldo separadas.
        $res = $this->conn->query("SELECT tipo, monto, detalle_distribucion_json FROM contabilidad_movimientos");
        
        while ($row = $res->fetch_assoc()) {
            $dist = json_decode($row['detalle_distribucion_json'], true);
            if (!$dist) continue;

            if ($row['tipo'] == 'ingreso') {
                foreach ($dist as $rubro_id => $info) {
                    if (isset($saldos[$rubro_id])) {
                        $saldos[$rubro_id]['ingresos'] += $info['monto'];
                        $saldos[$rubro_id]['saldo'] += $info['monto'];
                    }
                }
            } elseif ($row['tipo'] == 'egreso') {
                foreach ($dist as $rubro_id => $info) {
                    if (isset($saldos[$rubro_id])) {
                        $saldos[$rubro_id]['egresos'] += $info['monto'];
                        $saldos[$rubro_id]['saldo'] -= $info['monto'];
                    }
                }
            }
        }
        return $saldos;
    }

    public function obtenerTotalIngresosAnio($anio) {
        $stmt = $this->conn->prepare("SELECT SUM(monto) as total FROM contabilidad_movimientos WHERE tipo='ingreso' AND YEAR(fecha) = ?");
        $stmt->bind_param('i', $anio);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res['total'] ?? 0;
    }

    // Obtener lista de deportistas y su estado de último pago (Simplificado)
    public function obtenerEstadoDeudores() {
        // Esta es una lógica compleja. Para simplificar en V1:
        // Traemos todos los afiliados activos.
        // Buscamos su último pago de "Mensualidad" en contabilidad_movimientos.
        
        $sql = "SELECT a.id, a.nombre_completo, a.celular, 
                (SELECT MAX(fecha) FROM contabilidad_movimientos m 
                 JOIN contabilidad_conceptos c ON m.concepto_id = c.id 
                 WHERE m.tercero_id = a.id AND c.nombre LIKE '%Mensualidad%') as ultima_mensualidad
                FROM afiliados_siao a 
                WHERE a.activo = 1 AND a.rol != 'admin'";
                
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Obtener historial reciente
    public function obtenerUltimosMovimientos($limit = 10) {

        $sql = "SELECT m.*, c.nombre as concepto_nombre, cb.nombre as cuenta_nombre, 
                a.nombre_completo as deportista_nombre,
                CASE WHEN m.tercero_nombre != '' THEN m.tercero_nombre 
                     WHEN a.nombre_completo IS NOT NULL THEN a.nombre_completo 
                     ELSE 'Particular' END as tercero_final
                FROM contabilidad_movimientos m 
                LEFT JOIN contabilidad_conceptos c ON m.concepto_id = c.id 
                LEFT JOIN contabilidad_cuentas_bancarias cb ON m.cuenta_id = cb.id
                LEFT JOIN afiliados_siao a ON m.tercero_id = a.id
                ORDER BY m.fecha DESC, m.id DESC LIMIT $limit";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // --- SECCIÓN 7: ESTADÍSTICAS MEJORADAS PARA DASHBOARD ---
    
    /**
     * Obtener estadísticas completas para el dashboard
     */
    public function obtenerEstadisticasDashboard() {
        $stats = [];
        
        // Total de deportistas activos
        $result = $this->conn->query("SELECT COUNT(*) as total FROM afiliados_siao WHERE activo = 1");
        $stats['total_deportistas_activos'] = $result ? intval($result->fetch_assoc()['total']) : 0;
        
        // Ingresos del mes actual
        $mes_actual = date('Y-m');
        $sql = "SELECT COALESCE(SUM(monto), 0) as total 
                FROM contabilidad_movimientos 
                WHERE tipo = 'ingreso' 
                AND DATE_FORMAT(fecha, '%Y-%m') = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $mes_actual);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['ingresos_mes_actual'] = floatval($result->fetch_assoc()['total']);
        
        // Egresos del mes actual
        $sql = "SELECT COALESCE(SUM(monto), 0) as total 
                FROM contabilidad_movimientos 
                WHERE tipo = 'egreso' 
                AND DATE_FORMAT(fecha, '%Y-%m') = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $mes_actual);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['egresos_mes_actual'] = floatval($result->fetch_assoc()['total']);
        
        // Balance del mes
        $stats['balance_mes_actual'] = $stats['ingresos_mes_actual'] - $stats['egresos_mes_actual'];
        
        // Total en cuentas bancarias
        $result = $this->conn->query("SELECT COALESCE(SUM(saldo_actual), 0) as total FROM contabilidad_cuentas_bancarias WHERE activo = 1");
        $stats['total_saldo_cuentas'] = $result ? floatval($result->fetch_assoc()['total']) : 0;
        
        return $stats;
    }
    
    /**
     * Obtener estadísticas por horario
     */
    /**
     * Obtener estadísticas por horario (Mejorado)
     */
    public function obtenerEstadisticasPorHorario($horario_id) {
        $stats = ['total_deportistas' => 0, 'distribucion_grados' => []];
        
        // 1. Obtener regex
        $stmt_h = $this->conn->prepare("SELECT nombre FROM horarios WHERE id = ?");
        $stmt_h->bind_param("i", $horario_id);
        $stmt_h->execute();
        $res_h = $stmt_h->get_result();
        
        if ($row_h = $res_h->fetch_assoc()) {
            $nombre_horario = $row_h['nombre'];
            
            $partes = preg_split('/[,\s\-]+/', $nombre_horario, -1, PREG_SPLIT_NO_EMPTY);
            $partes_safe = array_map(function($p) { return preg_quote($p); }, $partes);
            $separador = '[[:space:],\-]+';
            $nombre_flexible = implode($separador, $partes_safe);
            $regexp = '(^|;)[[:space:]]*' . $nombre_flexible . '[[:space:]]*(;|$)';
            
            $p_id_json = '%"' . $horario_id . '"%';

            // Total de deportistas en el horario
            $sql_total = "SELECT COUNT(*) as total FROM afiliados_siao WHERE (horario REGEXP ? OR horario LIKE ? OR horario = ?) AND activo = 1";
            $stmt = $this->conn->prepare($sql_total);
            $stmt->bind_param('sss', $regexp, $p_id_json, $horario_id);
            $stmt->execute();
            $stats['total_deportistas'] = intval($stmt->get_result()->fetch_assoc()['total']);
            
            // Distribución por grado
            $sql_grados = "SELECT grado_cinturon, COUNT(*) as cantidad
                           FROM afiliados_siao
                           WHERE (horario REGEXP ? OR horario LIKE ? OR horario = ?) AND activo = 1
                           GROUP BY grado_cinturon
                           ORDER BY cantidad DESC";
            $stmt = $this->conn->prepare($sql_grados);
            $stmt->bind_param('sss', $regexp, $p_id_json, $horario_id);
            $stmt->execute();
            $stats['distribucion_grados'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        
        return $stats;
    }
    
    /**
     * Obtener distribución por grado (todos los deportistas)
     */
    public function obtenerDistribucionPorGrado() {
        $sql = "SELECT grado_cinturon, COUNT(*) as cantidad
                FROM afiliados_siao
                WHERE activo = 1
                GROUP BY grado_cinturon
                ORDER BY cantidad DESC";
        
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtener lista de horarios disponibles (Desde tabla oficial)
     */
    public function obtenerHorarios() {
        // Usamos la tabla oficial de horarios, no el campo de texto libre
        $sql = "SELECT id, nombre FROM horarios WHERE activo = 1 ORDER BY nombre";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Obtener ingresos y egresos de un mes específico
     */
    public function obtenerIngresosPorMes($mes) {
        $stats = [];
        
        // Total ingresos del mes
        $stmt = $this->conn->prepare(
            "SELECT COALESCE(SUM(monto), 0) as total 
             FROM contabilidad_movimientos 
             WHERE tipo = 'ingreso' AND DATE_FORMAT(fecha, '%Y-%m') = ?"
        );
        $stmt->bind_param('s', $mes);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['total_ingresos'] = floatval($result->fetch_assoc()['total']);
        
        // Total egresos del mes
        $stmt = $this->conn->prepare(
            "SELECT COALESCE(SUM(monto), 0) as total 
             FROM contabilidad_movimientos 
             WHERE tipo = 'egreso' AND DATE_FORMAT(fecha, '%Y-%m') = ?"
        );
        $stmt->bind_param('s', $mes);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['total_egresos'] = floatval($result->fetch_assoc()['total']);
        
        // Balance
        $stats['balance'] = $stats['total_ingresos'] - $stats['total_egresos'];
        
        // Por concepto (ingresos)
        $stmt = $this->conn->prepare(
            "SELECT c.nombre, COALESCE(SUM(m.monto), 0) as total
             FROM contabilidad_movimientos m
             JOIN contabilidad_conceptos c ON m.concepto_id = c.id
             WHERE m.tipo = 'ingreso' AND DATE_FORMAT(m.fecha, '%Y-%m') = ?
             GROUP BY c.nombre
             ORDER BY total DESC"
        );
        $stmt->bind_param('s', $mes);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['ingresos_por_concepto'] = $result->fetch_all(MYSQLI_ASSOC);
        
        // Por concepto (egresos)
        $stmt = $this->conn->prepare(
            "SELECT c.nombre, COALESCE(SUM(m.monto), 0) as total
             FROM contabilidad_movimientos m
             JOIN contabilidad_conceptos c ON m.concepto_id = c.id
             WHERE m.tipo = 'egreso' AND DATE_FORMAT(m.fecha, '%Y-%m') = ?
             GROUP BY c.nombre
             ORDER BY total DESC"
        );
        $stmt->bind_param('s', $mes);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['egresos_por_concepto'] = $result->fetch_all(MYSQLI_ASSOC);
        
        return $stats;
    }

    /**
     * Obtener movimientos detallados con filtros (Mejorado para horarios sucios)
     */
    public function obtenerMovimientosDetallados($mes, $horario_id = null) {
        // Base Query
        $sql = "SELECT m.*, c.nombre as concepto_nombre, a.nombre_completo as deportista_nombre, a.horario as deportista_horario, 
                CASE WHEN m.tercero_nombre != '' THEN m.tercero_nombre 
                     WHEN a.nombre_completo IS NOT NULL THEN a.nombre_completo
                     ELSE 'Particular' END as tercero_final
                FROM contabilidad_movimientos m 
                LEFT JOIN contabilidad_conceptos c ON m.concepto_id = c.id
                LEFT JOIN afiliados_siao a ON m.tercero_id = a.id
                WHERE DATE_FORMAT(m.fecha, '%Y-%m') = ?";
        
        $types = "s";
        $params = [$mes];

        if ($horario_id) {
            // Lógica similar a AsistenciaModel: Buscar el nombre del horario y usar REGEX
            $stmt_h = $this->conn->prepare("SELECT nombre FROM horarios WHERE id = ?");
            $stmt_h->bind_param("i", $horario_id);
            $stmt_h->execute();
            $res_h = $stmt_h->get_result();
            if ($row_h = $res_h->fetch_assoc()) {
                $nombre_horario = $row_h['nombre'];
                
                // Construir REGEX flexible
                $partes = preg_split('/[,\s\-]+/', $nombre_horario, -1, PREG_SPLIT_NO_EMPTY);
                $partes_safe = array_map(function($p) { return preg_quote($p); }, $partes);
                $separador = '[[:space:],\-]+';
                $nombre_flexible = implode($separador, $partes_safe);
                $regexp = '(^|;)[[:space:]]*' . $nombre_flexible . '[[:space:]]*(;|$)';
                
                // Aplicar filtro complejo
                $sql .= " AND (a.horario REGEXP ? OR a.horario LIKE ? OR a.horario = ?)";
                $types .= "sss";
                $p_id_json = '%"' . $horario_id . '"%'; // Por si acaso usa JSON IDs en el futuro
                $params[] = $regexp;
                $params[] = $p_id_json;
                $params[] = $horario_id; // Por si acaso exact match ID
            }
            $stmt_h->close();
        }

        $sql .= " ORDER BY m.fecha DESC, m.id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function __destruct() {
        if ($this->conn) $this->conn->close();
    }
}
?>
