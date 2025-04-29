<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './models/permiso-rol.php';
require_once './models/permiso.php';
require_once './models/rol.php';

$web = new PermisoRol();
$webPermiso = new Permiso();
$webRol = new Rol();

$action = null;

$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

$alert = [];

require_once './views/header.php';

switch ($action) {
    case 'create':
        $permisos = $webPermiso->findAll();
        $roles = $webRol->findAll();
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            $result = $web->create($data);
            if ($result) {
                $alert['type'] = 'success';
                $alert['message'] = 'permiso creada correctamente.';
                $web->alert($alert);
                $permisosRoles = $web->findAll();
                include_once './views/permiso-rol/index.php';
            } else {
                $alert['type'] = 'danger';
                $alert['message'] = 'Error al crear la permiso.';
                $web->alert($alert);
                include_once './views/permiso-rol/form.php';
            }

            $permisosRoles = $web->findAll();
            include_once './views/permiso-rol/index.php';
        } else {
            include_once './views/permiso-rol/form.php';
        }
        break;
    case 'update':
        $permisos = $webPermiso->findAll();
        $roles = $webRol->findAll();
        $info = null;
        $info = $web->findOne($id);
        if (isset($_POST['submit'])) {
            $data = $_POST['data'];
            $result = $web->update($data, $id);
            if ($result) {
                $alert['type'] = 'success';
                $alert['message'] = 'permiso actualizada correctamente.';
                $web->alert($alert);
                $permisosRoles = $web->findAll();
                include_once './views/permiso-rol/index.php';
            } else {
                $alert['type'] = 'danger';
                $alert['message'] = 'Error al actualizar la permiso.';
                $web->alert($alert);
                include_once './views/permiso-rol/form.php';
            }

            $permisosRoles = $web->findAll();
            include_once './views/permiso-rol/index.php';
        } else {
            include_once './views/permiso-rol/form.php';
        }
        break;
    case 'delete':
        $id_permiso = isset($_GET['id_permiso']) ? $_GET['id_permiso'] : null;
        $id_rol = isset($_GET['id_rol']) ? $_GET['id_rol'] : null;

        $result = $web->delete($id_permiso, $id_rol);
        if ($result) {
            $alert['type'] = 'success';
            $alert['message'] = 'La operación se ha realizado correctamente.';
        } else {
            $alert['type'] = 'danger';
            $alert['message'] = 'La operación no se ha realizado correctamente o existen productos asociados a esta permiso.';
        }

        $web->alert($alert);
        $permisosRoles = $web->findAll();
        include_once './views/permiso-rol/index.php';

        break;
    case 'findAll':
        $permisosRoles = $web->findAll();
        $web->findAll();
        include_once './views/permiso-rol/index.php';
        break;
    case 'findOne':
        $web->findOne($id);
        break;
    default:
        $permisosRoles = $web->findAll();
        $permisos = $webPermiso->findAll();
        $roles = $webRol->findAll();
        include_once './views/permiso-rol/index.php';
        break;
}

require_once './views/footer.php';
