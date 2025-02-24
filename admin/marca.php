<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './models/marca.php';

$web = new Marca();

$action = null;

$action = isset($_GET['action']) ?? null;

require_once './views/header.php';

switch ($action) {
    case 'create':
        $web->create();
        break;
    case 'update':
        $web->update();
        break;
    case 'delete':
        $web->delete();
        break;
    case 'findAll':
        $web->findAll();
        include_once './views/marca/index.php';
        break;
    case 'findOne':
        $web->findOne();
        break;
    default:
        $marcas = $web->findAll();
        include_once './views/marca/index.php';
        break;
}

require_once './views/footer.php';
