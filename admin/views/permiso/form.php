<form action="permiso.php?action=<?php echo isset($_GET['id']) ? 'update&id=' . $_GET['id'] : 'create'; ?>" method="POST"
    class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3><?php echo isset($_GET['id']) ? 'Editar' : 'Crear'; ?> permiso</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" maxlength="30" class="form-control" name="data[permiso]" placeholder="permiso"
                    value="<?php echo isset($info['permiso']) ? $info['permiso'] : ''; ?>" required>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="permiso.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" name="submit">Guardar</button>
        </div>
    </div>
</form>