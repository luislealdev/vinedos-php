<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './models/marca.php';

$web = new Marca();

$action = null;

$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['action']) ? $_GET['id'] : null;
$alert = [];

require_once './views/header.php';

switch ($action) {
    case 'create':
        $web->create();
        break;
    case 'update':
        $web->update();
        break;
    case 'delete':
        $result = $web->delete($id);
        if ($result) {
            $alert['type'] = 'success';
            $alert['message'] = 'La operación se ha realizado correctamente.';
        } else {
            $alert['type'] = 'danger';
            $alert['message'] = 'La operación no se ha realizado correctamente.';
        }

        $web->alert($alert);
        $marcas = $web->findAll();
        include_once './views/marca/index.php';

        break;
    case 'findAll':
        $web->findAll();
        include_once './views/marca/index.php';
        break;
    case 'findOne':
        $web->findOne($id);
        break;
    default:
        $marcas = $web->findAll();
        include_once './views/marca/index.php';
        break;
}

require_once './views/footer.php';
