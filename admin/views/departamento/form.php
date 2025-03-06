<form action="departamento.php?action=<?php echo isset($_GET['id']) ? 'update&id=' . $_GET['id'] : 'create'; ?>"
    method="POST" class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3><?php echo isset($_GET['id']) ? 'Editar' : 'Crear'; ?> Departamento</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" maxlength="30" class="form-control" name="data[departamento]"
                    placeholder="Departamento"
                    value="<?php echo isset($info['departamento']) ? $info['departamento'] : ''; ?>" required>
                <label for="marca">Departamento</label>
                <select class="form-control" name="data[id_marca]">
                    <option value="">Dependiente de...</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?php echo $marca['id_marca']; ?>" <?php echo isset($info['id_marca']) && $info['id_marca'] == $marca['id_marca'] ? 'selected' : ''; ?>>
                            <?php echo $marca['marca']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="departamento.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" name="submit">Guardar</button>
        </div>
    </div>
</form>