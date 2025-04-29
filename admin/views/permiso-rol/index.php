<h1 class="my-4">Permisos y Roles</h1>
<a href="?action=create" class="btn btn-success mb-3">Crear</a>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Permiso</th>
            <th scope="col">Rol</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($permisosRoles as $permisoRol): ?>
            <tr>
                <td>
                    <?php
                    // Buscar el texto del permiso en $permisos
                    foreach ($permisos as $permiso) {
                        if ($permiso['id_permiso'] == $permisoRol['id_permiso']) {
                            echo htmlspecialchars($permiso['permiso']);
                            break;
                        }
                    }
                    ?>
                </td>
                <td>
                    <?php
                    // Buscar el texto del rol en $roles
                    foreach ($roles as $rol) {
                        if ($rol['id_rol'] == $permisoRol['id_rol']) {
                            echo htmlspecialchars($rol['rol']);
                            break;
                        }
                    }
                    ?>
                </td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="?action=update&id_permiso=<?= urlencode($permisoRol['id_permiso']) ?>&id_rol=<?= urlencode($permisoRol['id_rol']) ?>"
                            class="btn btn-primary">Editar</a>
                        <a href="?action=delete&id_permiso=<?= urlencode($permisoRol['id_permiso']) ?>&id_rol=<?= urlencode($permisoRol['id_rol']) ?>"
                            class="btn btn-danger"
                            onclick="return confirm('¿Seguro que quieres eliminar este registro?')">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>