<?php
require_once __DIR__ . '/../models/ContabilidadModel.php';
require_once __DIR__ . '/../helpers/auth.php';

verificarSesion();
verificarRol('admin'); // Solo admins ven contabilidad

$model = new ContabilidadModel();
$anio_actual = date('Y');

// Acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'guardar_conf_anual') {
        $meta = floatval($_POST['meta_ingreso']);
        $saldo_anterior = floatval($_POST['saldo_anterior']);
        // Guardar Meta Anual Solamente
        if (isset($_POST['rubros_nombres']) && is_array($_POST['rubros_nombres'])) {
            foreach ($_POST['rubros_nombres'] as $id => $nombre) {
                // Pasamos 0 o null al porcentaje global ya que ahora es por concepto
                $model->actualizarRubro($id, $nombre, 0); 
            }
        }
        $model->guardarPresupuestoAnual($anio_actual, $meta, $saldo_anterior);
        header("Location: contabilidad_controller.php?view=config&msg=Configuracion+Guardada");
        exit;

    } elseif ($accion === 'subir_firma') {
        if (isset($_FILES['firma_imagen']) && $_FILES['firma_imagen']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['firma_imagen']['tmp_name'];
            $name = basename($_FILES['firma_imagen']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            $allowed = ['png', 'jpg', 'jpeg'];
            
            if (in_array($ext, $allowed)) {
                $destination = __DIR__ . '/../assets/img/firma_digital.png';
                // Asegurar directorio
                if (!is_dir(dirname($destination))) {
                    mkdir(dirname($destination), 0777, true);
                }
                
                if (move_uploaded_file($tmp_name, $destination)) {
                    header("Location: contabilidad_controller.php?view=config&msg=Firma+Actualizada");
                } else {
                    header("Location: contabilidad_controller.php?view=config&error=Error+al+guardar+imagen");
                }
            } else {
                header("Location: contabilidad_controller.php?view=config&error=Formato+no+valido+(solo+png,+jpg)");
            }
        } else {
            header("Location: contabilidad_controller.php?view=config&error=No+se+subio+ningun+archivo");
        }
        exit;

    } elseif ($accion === 'crear_concepto') {
        $nombre = $_POST['nombre'];
        $tipo = $_POST['tipo'];
        $rubro_default = !empty($_POST['rubro_default']) ? $_POST['rubro_default'] : null;
        
        if ($model->crearConcepto($nombre, $tipo, $rubro_default)) {
            header("Location: contabilidad_controller.php?view=config&msg=Concepto+Creado");
        } else {
            header("Location: contabilidad_controller.php?view=config&error=Error+al+crear");
        }
        exit;

    } elseif ($accion === 'guardar_distribucion_concepto') {
        $concepto_id = $_POST['concepto_id'];
        $distribucion = $_POST['rubros']; // Array [rubro_id => porcentaje]
        
        // Calcular total para validar (opcional, pero buena práctica)
        $total = array_sum($distribucion);
        // Podríamos redirigir con error si $total != 100, pero el usuario pidió flexibilidad ("validar pero permitir")
        
        $model->guardarDistribucionConcepto($concepto_id, $distribucion);
        header("Location: contabilidad_controller.php?view=config&msg=Distribucion+Actualizada");
        exit;

    } elseif ($accion === 'registrar_ingreso') {
        $datos = [
            'fecha' => $_POST['fecha'],
            'concepto_id' => $_POST['concepto_id'],
            'tercero_id' => !empty($_POST['afiliado_id']) ? $_POST['afiliado_id'] : null,
            'tercero_nombre' => $_POST['tercero_nombre'] ?? '', 
            'monto' => floatval($_POST['monto']),
            'cuenta_id' => !empty($_POST['cuenta_id']) ? $_POST['cuenta_id'] : null, // NUEVO
            'comprobante' => $_POST['comprobante'],
            'banco' => $_POST['banco_origen'] ?? '', // Cambiado nombre field en form para no confundir con cuenta interna
            'observaciones' => $_POST['observaciones'],
            'usuario_id' => $_SESSION['usuario_id']
        ];
        
        if ($model->registrarIngreso($datos)) {
            header("Location: contabilidad_controller.php?view=ingresos&msg=Ingreso+Registrado");
        } else {
            header("Location: contabilidad_controller.php?view=ingresos&error=Error+al+registrar");
        }
        exit;

    } elseif ($accion === 'registrar_egreso') {
        $datos = [
            'fecha' => $_POST['fecha'],
            'concepto_id' => $_POST['concepto_id'],
            'rubro_id' => $_POST['rubro_id'], 
            'tercero_id' => !empty($_POST['afiliado_id']) ? $_POST['afiliado_id'] : null,
            'beneficiario' => $_POST['beneficiario'],
            'monto' => floatval($_POST['monto']),
            'cuenta_id' => !empty($_POST['cuenta_id']) ? $_POST['cuenta_id'] : null, // NUEVO
            'comprobante' => $_POST['comprobante'],
            'banco' => $_POST['banco_origen'] ?? '',
            'observaciones' => $_POST['observaciones'],
            'usuario_id' => $_SESSION['usuario_id']
        ];

        if ($model->registrarEgreso($datos)) {
            header("Location: contabilidad_controller.php?view=egresos&msg=Gasto+Registrado");
        } else {
            header("Location: contabilidad_controller.php?view=egresos&error=Error+al+registrar");
        }
        exit;
    } elseif ($accion === 'guardar_cuenta') {
        $nombre = $_POST['nombre'];
        $tipo = $_POST['tipo'];
        $numero = $_POST['numero_cuenta'];
        $saldo = floatval($_POST['saldo_inicial']);
        
        // Si es editar (id presente)
        if (!empty($_POST['id'])) {
            $model->actualizarCuenta($_POST['id'], $nombre, $tipo, $numero);
            $msg = "Cuenta+Actualizada";
        } else {
            $model->crearCuenta($nombre, $tipo, $saldo, $numero);
            $msg = "Cuenta+Creada";
        }
        header("Location: contabilidad_controller.php?view=config&msg=$msg");
        exit;

    } elseif ($accion === 'editar_concepto_simple') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $tipo = $_POST['tipo'];
        $model->actualizarConcepto($id, $nombre, $tipo);
        header("Location: contabilidad_controller.php?view=config&msg=Concepto+Actualizado");
        exit;

    } elseif ($accion === 'editar_movimiento') {
        $datos = [
            'id' => $_POST['id'],
            'fecha' => $_POST['fecha'],
            'concepto_id' => $_POST['concepto_id'],
            'monto' => floatval($_POST['monto']),
            'cuenta_id' => !empty($_POST['cuenta_id']) ? $_POST['cuenta_id'] : null,
            'observaciones' => $_POST['observaciones']
            // 'rubro_id' si fuera egreso y permitiéramos cambiarlo, pero por complejidad lo omitimos en V2 general
        ];
        
        // Si es egreso y trae rubro (opcional)
        if (isset($_POST['rubro_id'])) {
            $datos['rubro_id'] = $_POST['rubro_id'];
        }

        if ($model->actualizarMovimiento($datos)) {
            // Retornar a la vista correspondiente según origen (ingresos o egresos)
            // Usamos $_POST['redirect_view'] si existe
            $view_redirect = $_POST['redirect_view'] ?? 'dashboard';
            header("Location: contabilidad_controller.php?view=$view_redirect&msg=Movimiento+Actualizado");
        } else {
            header("Location: contabilidad_controller.php?view=dashboard&error=Error+al+actualizar");
        }
        exit;
    
    } elseif ($accion === 'eliminar_movimiento') {
        $id = $_POST['id'];
        $view_redirect = $_POST['redirect_view'] ?? 'dashboard';
        
        if ($model->eliminarMovimiento($id)) {
            header("Location: contabilidad_controller.php?view=$view_redirect&msg=Movimiento+Eliminado");
        } else {
            header("Location: contabilidad_controller.php?view=$view_redirect&error=Error+al+eliminar");
        }
        exit;
    }
}
// Vistas
$view = $_GET['view'] ?? 'dashboard';

if ($view === 'dashboard') {
    $presupuesto = $model->obtenerPresupuestoAnual($anio_actual);
    $ingresos_reales = $model->obtenerTotalIngresosAnio($anio_actual);
    $saldos_rubros = $model->obtenerSaldosRubros();
    
    // Cálculo de meta
    $meta = $presupuesto['meta_ingreso_total'] ?? 1; // Evitar div/0
    $porcentaje_avance = ($ingresos_reales / $meta) * 100;
    
    // NUEVO: Integrar estadísticas de exámenes
    require_once __DIR__ . '/../models/ExamenesModel.php';
    $examenes_model = new ExamenesModel($model->conn ?? conectarDB());
    $proximos_examenes = $examenes_model->obtenerProximosExamenes(30);
    $count_examenes = $examenes_model->contarProximosExamenes(30);
    
    // Estadísticas mejoradas del dashboard
    $stats_dashboard = $model->obtenerEstadisticasDashboard();

    include __DIR__ . '/../views/contabilidad/dashboard_contable.php';


} elseif ($view === 'config') {
    $rubros = $model->obtenerRubros();
    $presupuesto = $model->obtenerPresupuestoAnual($anio_actual);
    $cuentas = $model->obtenerCuentas(); // NUEVO
    
    // Cargamos conceptos para poder editarlos
    $conceptos_ingreso = $model->obtenerConceptos('ingreso');
    $conceptos_egreso = $model->obtenerConceptos('egreso');
    
    // Si se pide editar uno específico (para cargar su distribución)
    $editar_concepto_id = $_GET['editar_concepto'] ?? null;
    $distribucion_actual = [];
    if ($editar_concepto_id) {
        $raw_dist = $model->obtenerDistribucionConcepto($editar_concepto_id);
        foreach ($raw_dist as $row) {
            $distribucion_actual[$row['rubro_id']] = $row['porcentaje'];
        }
    }
    
    include __DIR__ . '/../views/contabilidad/configuracion.php';


} elseif ($view === 'ingresos') {
    $conceptos = $model->obtenerConceptos('ingreso');
    $movimientos = $model->obtenerUltimosMovimientos(20);
    $cuentas = $model->obtenerCuentas(); // NUEVO para el select
    // Necesitamos lista de afiliados para el select
    $conn_temp = conectarDB();
    $afiliados = $conn_temp->query("SELECT id, nombre_completo FROM afiliados_siao WHERE activo=1 ORDER BY nombre_completo")->fetch_all(MYSQLI_ASSOC);
    $conn_temp->close();
    
    // Lista de deudores
    $deudores = $model->obtenerEstadoDeudores();

    include __DIR__ . '/../views/contabilidad/ingresos.php';

} elseif ($view === 'egresos') {
    $conceptos = $model->obtenerConceptos('egreso');
    $rubros = $model->obtenerRubros(); 
    $movimientos = $model->obtenerUltimosMovimientos(20);
    // Necesitamos lista de afiliados para el select (Proveedores también pueden ser terceros)
    $conn_temp = conectarDB();
    $afiliados = $conn_temp->query("SELECT id, nombre_completo FROM afiliados_siao WHERE activo=1 ORDER BY nombre_completo")->fetch_all(MYSQLI_ASSOC);
    $conn_temp->close();
    
    // Lista de cuentas
    $cuentas = $model->obtenerCuentas();

    include __DIR__ . '/../views/contabilidad/egresos.php';

} elseif ($view === 'reportes') {
    $mes = $_GET['mes'] ?? date('Y-m');
    $horario = $_GET['horario'] ?? '';
    
    $stats_mensuales = $model->obtenerIngresosPorMes($mes);
    $distribucion_grados = $model->obtenerDistribucionPorGrado();
    $horarios = $model->obtenerHorarios();
    
    $stats_horario = null;
    if ($horario) {
        $stats_horario = $model->obtenerEstadisticasPorHorario($horario);
    }
    
    // Obtener movimientos detallados para la tabla
    $movimientos_detallados = $model->obtenerMovimientosDetallados($mes, $horario);

    include __DIR__ . '/../views/contabilidad/reportes.php';

} elseif ($view === 'imprimir_recibo') {
    $id = $_GET['id'];
    $movimiento = $model->obtenerMovimiento($id);
    
    if (!$movimiento) {
        die("Movimiento no encontrado");
    }
    
    // Datos completos para el recibo
    // Necesitamos saber si es ingreso o egreso para el título y formato
    $titulo_doc = ($movimiento['tipo'] == 'ingreso') ? 'RECIBO DE CAJA' : 'COMPROBANTE DE EGRESO';
    
    // Si hay tercero_id, buscar datos extra del tercero (como documento)
    $tercero_doc = '';
    if ($movimiento['tercero_id']) {
        $conn_temp = conectarDB();
        $res_ter = $conn_temp->query("SELECT documento FROM afiliados_siao WHERE id = " . $movimiento['tercero_id']);
        if ($res_ter && $row_ter = $res_ter->fetch_assoc()) {
            $tercero_doc = $row_ter['documento'];
        }
        $conn_temp->close();
    }

    include __DIR__ . '/../views/contabilidad/ver_recibo.php';

} elseif ($view === 'editar_movimiento') {
    $id = $_GET['id'];
    $movimiento = $model->obtenerMovimiento($id);
    if (!$movimiento) {
        die("Movimiento no encontrado");
    }
    
    // Cargar listas necesarias para los selects
    $cuentas = $model->obtenerCuentas();
    
    if ($movimiento['tipo'] == 'ingreso') {
        $conceptos = $model->obtenerConceptos('ingreso');
        // También necesitamos afiliados si quisiéramos editar el tercero, pero por ahora lo dejamos
    } else {
        $conceptos = $model->obtenerConceptos('egreso');
        $rubros = $model->obtenerRubros(); // Solo para referencia en egresos
    }
    
    include __DIR__ . '/../views/contabilidad/editar_movimiento.php';
}
?>
