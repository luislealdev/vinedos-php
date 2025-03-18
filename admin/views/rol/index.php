<h1 class="my-4">Roles</h1>
<a href="?action=create" class="btn btn-success mb-3">Crear</a>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Rol</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rols as $rol): ?>
            <tr>
                <th scope="row"><?= $rol['id_rol'] ?></th>
                <td><?= $rol['rol'] ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="?action=update&id=<?= $rol['id_rol'] ?>" class="btn btn-primary">Editar</a>
                        <a href="?action=delete&id=<?= $rol['id_rol'] ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>