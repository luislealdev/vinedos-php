<h1 class="my-4">Usuarios</h1>
<a href="?action=create" class="btn btn-success mb-3">Crear</a>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Correo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <th scope="row"><?= $usuario['id_usuario'] ?></th>
                <td><?= $usuario['correo'] ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="?action=update&id=<?= $usuario['id_usuario'] ?>" class="btn btn-primary">Editar</a>
                        <a href="?action=delete&id=<?= $usuario['id_usuario'] ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>