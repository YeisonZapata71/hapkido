<?php
require_once __DIR__.'/../models/InstructoresModel.php';
$model = new InstructoresModel();

// Acciones acceso al sistema
if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];

    switch ($accion) {
        case 'dar_acceso':
            $afiliado_id = $_POST['afiliado_id'];
            $nombre = trim($_POST['nombre']);
            $correo = trim($_POST['correo']);
            $password = trim($_POST['password']);
            $usuario_existente = $model->buscarUsuarioPorCorreo($correo);

            if ($usuario_existente) {
                $usuario_id = $usuario_existente['id'];
                $model->vincularUsuarioAfiliado($afiliado_id, $usuario_id);
                // SINCRONIZAR: asegurar que el usuario vinculado tenga rol 'instructor'
                $model->actualizarRolUsuario($usuario_id, 'instructor');
                header('Location: ../views/instructores/index.php?msg=usuario_vinculado');
                exit;
            } else {
                $usuario_id = $model->crearUsuario($nombre, $correo, $password);
                $model->vincularUsuarioAfiliado($afiliado_id, $usuario_id);
                header('Location: ../views/instructores/index.php?msg=usuario_creado');
                exit;
            }
            break;

        case 'reset_password':
            $usuario_id = $_GET['id'];
            $nueva = generarPasswordTemporal();
            $model->resetearPassword($usuario_id, $nueva);
            header('Location: ../views/instructores/index.php?msg=password_reset&nueva='.urlencode($nueva));
            exit;

        case 'suspender_acceso':
            $usuario_id = $_GET['id'];
            $model->suspenderUsuario($usuario_id);
            header('Location: ../views/instructores/index.php?msg=usuario_suspendido');
            exit;
    }
}

// Listado instructores para la vista
$instructores = $model->listarInstructores();

// Password temporal
function generarPasswordTemporal($length = 10) {
    return substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789'), 0, $length);
}

// Incluye la vista
include __DIR__.'/../views/instructores/index.php';
