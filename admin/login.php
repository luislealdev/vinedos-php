<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './model.php';
include_once './views/header.php';

$app = new Model();
$action = 'login';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$alert = [];

switch ($action) {
    case 'restablecer':
        # code...
        break;

    case 'logout':
        if ($app->logout()) {
            $alert['type'] = 'danger';
            $alert['message'] = 'La sesión se cerró correctamente.';
            $app->alert($alert);
            include_once '/vinedos/auth/views/form.php';
        }
        break;

    case 'change':
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            $token = $app->changePassword($data['mail']);
            if ($token) {
                $alert['type'] = 'success';
                $alert['message'] = 'Se ha enviado un correo para restablecer la contraseña.';
                $app->alert($alert);

                $mensaje = 'Se ha enviado un correo para restablecer la contraseña.';
                $asunto = 'Restablecer contraseña';
                $destinatario = $data['mail'];
                $cuerpo = 'Para restablecer la contraseña, haga clic en el siguiente enlace: ';
                $cuerpo .= 'http://localhost/vinedos/admin/login.php?action=restore&mail=' . $data['mail'] . '&token=' . $token;

                $app->sendEmail($destinatario, $asunto, $cuerpo);

                include_once '../auth/views/form.php';
            } else {
                $alert['type'] = 'danger';
                $alert['message'] = 'Error al enviar el correo.';
                $app->alert($alert);
                include_once '/vinedos/auth/views/form.php';
            }
        }

    case 'restore':
        $mail = isset($_GET['mail']) ? $_GET['mail'] : null;
        $token = isset($_GET['token']) ? $_GET['token'] : null;
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            $result = $app->restorePassword($data['password'], $mail, $token);
            if ($result) {
                $alert['type'] = 'success';
                $alert['message'] = 'Contraseña restablecida correctamente.';
                $app->alert($alert);
                include_once '/vinedos/auth/views/form.php';
            } else {
                $alert['type'] = 'danger';
                $alert['message'] = 'Error al restablecer la contraseña.';
                $app->alert($alert);
                include_once '/vinedos/auth/views/form.php';
            }
        } else {
            include_once '../auth/views/restore.php';
        }
        break;


    default:
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            if ($app->login($data['correo'], $data['password'])) {
                $alert['type'] = 'success';
                $alert['message'] = 'Inicio de sesión correcto.';
                $app->alert($alert);

            }
        }
}