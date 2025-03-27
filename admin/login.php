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