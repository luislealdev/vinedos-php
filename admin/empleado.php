<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './models/usuario.php'; // Assuming a Usuario model exists
require_once './models/empleado.php';

$web = new Empleado();
$webUsuario = new Usuario(); // Replacing Marca with Usuario

$action = null;

$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

$alert = [];

require_once './views/header.php';

switch ($action) {
    case 'create':
        $usuarios = $webUsuario->findAll(); // Fetch all users for the dropdown

        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            $result = $web->create($data);
            if ($result) {
                $alert['type'] = 'success';
                $alert['message'] = 'Empleado creado correctamente.';
                $web->alert($alert);
                $empleados = $web->findAll();
                include_once './views/empleado/index.php';
            } else {
                $alert['type'] = 'danger';
                $alert['message'] = 'Error al crear el empleado.';
                $web->alert($alert);
                include_once './views/empleado/form.php';
            }
        } else {
            include_once './views/empleado/form.php';
        }
        break;

    case 'update':
        $info = null;
        $usuarios = $webUsuario->findAll(); // Fetch all users for the dropdown
        $info = $web->findOne($id);
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            $result = $web->update($data, $id);
            if ($result) {
                $alert['type'] = 'success';
                $alert['message'] = 'Empleado actualizado correctamente.';
                $web->alert($alert);
                $empleados = $web->findAll();
                include_once './views/empleado/index.php';
            } else {
                $alert['type'] = 'danger';
                $alert['message'] = 'Error al actualizar el empleado.';
                $web->alert($alert);
                include_once './views/empleado/form.php';
            }
        } else {
            include_once './views/empleado/form.php';
        }
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
        $empleados = $web->findAll();
        include_once './views/empleado/index.php';
        break;

    case 'findAll':
        $empleados = $web->findAll($id);
        include_once './views/empleado/index.php';
        break;

    case 'findOne':
        $info = $web->findOne($id);
        // Optionally include a view here if you want to display a single employee
        break;

    default:
        $empleados = $web->findAll($id);
        include_once './views/empleado/index.php';
        break;
}

require_once './views/footer.php';
?>