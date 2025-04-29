<h1 class="my-4">Estados</h1>
<a href="?action=create" class="btn btn-success mb-3">Crear</a>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Estado</th>
            <!-- <th scope="col">Cantidad</th> -->
            <th scope="col">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($estados as $estado): ?>
            <tr>
                <th scope="row"><?= $estado['id_estado'] ?></th>
                <td><?= $estado['estado'] ?></td>
                <!-- <td><?= $estado['cantidad'] ?></td> -->
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="?action=update&id=<?= $estado['id_estado'] ?>" class="btn btn-primary">Editar</a>
                        <a href="?action=delete&id=<?= $estado['id_estado'] ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>