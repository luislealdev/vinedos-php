<form enctype="multipart/form-data"
    action="empleado.php?action=<?php echo isset($_GET['id']) ? 'update&id=' . $_GET['id'] : 'create'; ?>" method="POST"
    class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3><?php echo isset($_GET['id']) ? 'Editar' : 'Crear'; ?> Empleado</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <!-- Primer Apellido -->
                <label for="primer_apellido">Primer Apellido</label>
                <input type="text" maxlength="50" class="form-control" name="data[primer_apellido]"
                    placeholder="Primer Apellido"
                    value="<?php echo isset($info['primer_apellido']) ? $info['primer_apellido'] : ''; ?>" required>

                <!-- Segundo Apellido -->
                <label for="segundo_apellido">Segundo Apellido</label>
                <input type="text" maxlength="50" class="form-control" name="data[segundo_apellido]"
                    placeholder="Segundo Apellido"
                    value="<?php echo isset($info['segundo_apellido']) ? $info['segundo_apellido'] : ''; ?>" required>

                <!-- Nombre -->
                <label for="nombre">Nombre</label>
                <input type="text" maxlength="50" class="form-control" name="data[nombre]" placeholder="Nombre"
                    value="<?php echo isset($info['nombre']) ? $info['nombre'] : ''; ?>" required>

                <!-- Nacimiento -->
                <label for="nacimiento">Fecha de Nacimiento</label>
                <input type="date" class="form-control" name="data[nacimiento]"
                    value="<?php echo isset($info['nacimiento']) ? $info['nacimiento'] : ''; ?>" required>

                <!-- RFC -->
                <label for="rfc">RFC</label>
                <input type="text" maxlength="13" class="form-control" name="data[rfc]"
                    placeholder="RFC (13 caracteres)" value="<?php echo isset($info['rfc']) ? $info['rfc'] : ''; ?>"
                    pattern="[A-Z]{4}[0-9]{6}[A-Z0-9]{3}" title="Formato: ABCD123456XYZ" required>

                <!-- CURP -->
                <label for="curp">CURP</label>
                <input type="text" maxlength="18" class="form-control" name="data[curp]"
                    placeholder="CURP (18 caracteres)" value="<?php echo isset($info['curp']) ? $info['curp'] : ''; ?>"
                    pattern="[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9]{2}" title="Formato: ABCD123456HDFXYZ01" required>

                <!-- Correo -->
                <label for="correo">Correo</label>
                <input type="text" maxlength="50" class="form-control" name="data[correo]" placeholder="Correo"
                    value="<?php echo isset($info['correo']) ? $info['correo'] : ''; ?>" required>

                <!-- Contraseña -->
                <label for="password">Contraseña</label>
                <input type="password" maxlength="50" class="form-control" name="data[password]"
                    placeholder="Contraseña" value="" <?php echo (!isset($_GET['id']) || $_GET['action'] !== 'update') ? 'required' : ''; ?>>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="empleado.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" name="submit">
                <?php echo isset($_GET['id']) ? 'Actualizar' : 'Guardar'; ?>
            </button>
        </div>
    </div>
</form>