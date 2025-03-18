<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../admin/models/usuario.php';

$web = new Usuario();

$action = null;

$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

$alert = [];

require_once '../admin/views/header.php';

switch ($action) {
    case 'create':
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            $result = $web->create($data);
            if ($result) {
                $alert['type'] = 'success';
                $alert['message'] = 'Usuario creado correctamente.';
                $web->alert($alert);
                $usuarios = $web->findAll();
                include_once './views/form.php';
            } else {
                $alert['type'] = 'danger';
                $alert['message'] = 'Error al crear el usuario.';
                $web->alert($alert);
                include_once './views/form.php';
            }
        } else {
            include_once './views/form.php';
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
                $alert['message'] = 'Usuario actualizado correctamente.';
                $web->alert($alert);
                $usuarios = $web->findAll();
                include_once './views/form.php';
            } else {
                $alert['type'] = 'danger';
                $alert['message'] = 'Error al actualizar el usuario.';
                $web->alert($alert);
                include_once './views/form.php';
            }
        } else {
            include_once './views/form.php';
        }
        break;

    case 'delete':
        $result = $web->delete($id);
        if ($result) {
            $alert['type'] = 'success';
            $alert['message'] = 'Usuario eliminado correctamente.';
        } else {
            $alert['type'] = 'danger';
            $alert['message'] = 'Error al eliminar el usuario.';
        }
        $web->alert($alert);
        $usuarios = $web->findAll();
        include_once './views/form.php';
        break;

    case 'findAll':
        $usuarios = $web->findAll();
        include_once './views/form.php';
        break;

    case 'findOne':
        $info = $web->findOne($id); 
        include_once './views/form.php';
        break;

    default:
        $usuarios = $web->findAll();
        include_once './views/form.php';
        break;
}

require_once '../admin/views/footer.php';