<h1 class="my-4">Permisos</h1>
<a href="?action=create" class="btn btn-success mb-3">Crear</a>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Permiso</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($permisos as $permiso): ?>
            <tr>
                <th scope="row"><?= $permiso['id_permiso'] ?></th>
                <td><?= $permiso['permiso'] ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="?action=update&id=<?= $permiso['id_permiso'] ?>" class="btn btn-primary">Editar</a>
                        <a href="?action=delete&id=<?= $permiso['id_permiso'] ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>