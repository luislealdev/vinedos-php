<h1>Empleados</h1>

<?php if ($web->check_permission('empleado_escritura')): ?>
    <a href="?action=create" class="btn btn-success">Crear</a>
<?php endif; ?>

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
                <th scope="row"><?= htmlspecialchars($empleado['id_empleado']) ?></th>
                <td><?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['primer_apellido'] . ' ' . $empleado['segundo_apellido']) ?>
                </td>
                <td><?= htmlspecialchars($empleado['nacimiento']) ?></td>
                <td><?= htmlspecialchars($empleado['rfc']) ?></td>
                <td><?= htmlspecialchars($empleado['curp']) ?></td>
                <td><?= htmlspecialchars($empleado['correo']) ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="?action=update&id=<?= urlencode($empleado['id_empleado']) ?>"
                            class="btn btn-primary">Editar</a>
                        <a href="?action=delete&id=<?= urlencode($empleado['id_empleado']) ?>"
                            class="btn btn-danger">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>