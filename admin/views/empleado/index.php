<h1>Empleados</h1>
<a href="?action=create" class="btn btn-success">Crear</a>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre Completo</th>
            <th scope="col">Nacimiento</th>
            <th scope="col">RFC</th>
            <th scope="col">CURP</th>
            <th scope="col">Correo</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($empleados as $empleado): ?>
            <tr>
                <th scope="row"><?= $empleado['id_empleado'] ?></th>
                <td><?= $empleado['nombre'] . ' ' . $empleado['primer_apellido'] . ' ' . $empleado['segundo_apellido'] ?>
                </td>
                <td><?= $empleado['nacimiento'] ?></td>
                <td><?= $empleado['rfc'] ?></td>
                <td><?= $empleado['curp'] ?></td>
                <td><?= $empleado['correo'] ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="?action=update&id=<?= $empleado['id_empleado'] ?>" class="btn btn-primary">Editar</a>
                        <a href="?action=delete&id=<?= $empleado['id_empleado'] ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>