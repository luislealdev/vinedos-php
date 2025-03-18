<form action="usuario.php?action=<?php echo isset($_GET['id']) ? 'update&id=' . $_GET['id'] : 'create'; ?>"
    method="POST" class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3><?php echo isset($_GET['id']) ? 'Editar' : 'Crear'; ?> usuario</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" maxlength="30" class="form-control" name="data[correo]" placeholder="correo"
                    value="<?php echo isset($info['correo']) ? $info['correo'] : ''; ?>" required>
                <label for="nombre">Contraseña</label>
                <input type="password" maxlength="30" class="form-control" name="data[password]"
                    placeholder="password"
                    value="<?php echo isset($info['password']) ? $info['password'] : ''; ?>" required>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="usuario.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" name="submit">Guardar</button>
        </div>
    </div>
</form>