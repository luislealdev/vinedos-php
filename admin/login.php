<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './model.php';

$app = new Model();
$action = 'login';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

switch ($action) {
    case 'restablecer':
        # code...
        break;

    case 'logout':
        if ($app->logout()) {
            echo "Sesión cerrada";
            die();
        }
        break;

    default:
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            if ($app->login($data['correo'], $data['password'])) {
            }
        }
}