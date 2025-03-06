<form action="estado.php?action=<?php echo isset($_GET['id']) ? 'update&id=' . $_GET['id'] : 'create'; ?>" method="POST"
    class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3><?php echo isset($_GET['id']) ? 'Editar' : 'Crear'; ?> Estado</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" maxlength="30" class="form-control" name="data[estado]" placeholder="Estado"
                    value="<?php echo isset($info['estado']) ? $info['estado'] : ''; ?>" required>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="estado.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" name="submit">Guardar</button>
        </div>
    </div>
</form>