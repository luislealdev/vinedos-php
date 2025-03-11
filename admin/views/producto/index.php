<h1>Productos</h1>
<a href="?action=create" class="btn btn-success">Crear</a>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Fotografía</th>
            <th scope="col">Producto</th>
            <th scope="col">Precio</th>
            <th scope="col">Marca</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <th scope="row"><?= $producto['id_producto'] ?></th>
                <!-- Show image -->
                <td>
                    <img src="<?= "../uploads/" . $producto['fotografia'] ?>" alt="<?= $producto['producto'] ?>" width="50">
                </td>
                <td><?= $producto['producto'] ?></td>
                <td><?= $producto['precio'] ?></td>
                <td><?= $producto['marca'] ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="?action=update&id=<?= $producto['id_producto'] ?>" class="btn btn-primary">Editar</a>
                        <a href="?action=delete&id=<?= $producto['id_producto'] ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>