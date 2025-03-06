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
                <label for="departamento">Departamento</label>
                <select class="form-control" name="data[id_departamento_depende]">
                    <option value="">Dependiente de...</option>
                    <?php foreach ($departamentos as $departamento): ?>
                        <option value="<?php echo $departamento['id_departamento']; ?>" <?php echo isset($info['id_departamento']) && $info['id_departamento'] == $departamento['id_departamento'] ? 'selected' : ''; ?>>
                            <?php echo $departamento['departamento']; ?>
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