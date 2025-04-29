<h1 class="my-4">Marcas</h1>
<a href="?action=create" class="btn btn-success mb-3">Crear</a>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Marca</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($marcas as $marca): ?>
            <tr>
                <th scope="row"><?= $marca['id_marca'] ?></th>
                <td><?= $marca['marca'] ?></td>
                <td><?= $marca['cantidad'] ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="?action=update&id=<?= $marca['id_marca'] ?>" class="btn btn-primary">Editar</a>
                        <a href="?action=delete&id=<?= $marca['id_marca'] ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>