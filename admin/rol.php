<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './models/rol.php';

$web = new Rol();

$action = null;

$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;


$alert = [];

require_once './views/header.php';

switch ($action) {
    case 'create':
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            $result = $web->create($data);
            if ($result) {
                $alert['type'] = 'success';
                $alert['message'] = 'rol creada correctamente.';
                $web->alert($alert);
                $rols = $web->findAll();
                include_once './views/rol/index.php';
            } else {
                $alert['type'] = 'danger';
                $alert['message'] = 'Error al crear la rol.';
                $web->alert($alert);
                include_once './views/rol/form.php';
            }

            $rols = $web->findAll();
            include_once './views/rol/index.php';
        } else {
            include_once './views/rol/form.php';
        }
        break;
    case 'update':
        $info = null;
        $info = $web->findOne($id);
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            $result = $web->update($data, $id);
            if ($result) {
                $alert['type'] = 'success';
                $alert['message'] = 'rol actualizada correctamente.';
                $web->alert($alert);
                $rols = $web->findAll();
                include_once './views/rol/index.php';
            } else {
                $alert['type'] = 'danger';
                $alert['message'] = 'Error al actualizar la rol.';
                $web->alert($alert);
                include_once './views/rol/form.php';
            }

            $rols = $web->findAll();
            include_once './views/rol/index.php';
        } else {
            include_once './views/rol/form.php';
        }
        break;
    case 'delete':
        $result = $web->delete($id);
        if ($result) {
            $alert['type'] = 'success';
            $alert['message'] = 'La operación se ha realizado correctamente.';
        } else {
            $alert['type'] = 'danger';
            $alert['message'] = 'La operación no se ha realizado correctamente o existen productos asociados a esta rol.';
        }

        $web->alert($alert);
        $rols = $web->findAll();
        include_once './views/rol/index.php';

        break;
    case 'findAll':
        $web->findAll();
        include_once './views/rol/index.php';
        break;
    case 'findOne':
        $web->findOne($id);
        break;
    default:
        $rols = $web->findAll();
        include_once './views/rol/index.php';
        break;
}

require_once './views/footer.php';
