<h1 class="my-4">Departamentos</h1>
<a href="?action=create" class="btn btn-success mb-3">Crear</a>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Departamento</th>
            <!-- <th scope="col">Cantidad</th> -->
            <th scope="col">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($departamentos as $departamento): ?>
            <tr>
                <th scope="row"><?= $departamento['id_departamento'] ?></th>
                <td><?= $departamento['departamento'] ?></td>
                <!-- <td><?= $departamento['cantidad'] ?></td> -->
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="?action=update&id=<?= $departamento['id_departamento'] ?>"
                            class="btn btn-primary">Editar</a>
                        <a href="?action=delete&id=<?= $departamento['id_departamento'] ?>"
                            class="btn btn-danger">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>