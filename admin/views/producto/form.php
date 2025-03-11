<form enctype="multipart/form-data"
    action="producto.php?action=<?php echo isset($_GET['id']) ? 'update&id=' . $_GET['id'] : 'create'; ?>" method="POST"
    class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3><?php echo isset($_GET['id']) ? 'Editar' : 'Crear'; ?> Producto</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" maxlength="30" class="form-control" name="data[producto]" placeholder="Producto"
                    value="<?php echo isset($info['producto']) ? $info['producto'] : ''; ?>" required>
                <!-- Precio -->
                <label for="precio">Precio</label>
                <input type="number" class="form-control" name="data[precio]" placeholder="Precio"
                    value="<?php echo isset($info['precio']) ? $info['precio'] : ''; ?>" required>
                <!-- Marca -->
                <label for="marca">Marca</label>
                <select class="form-control" name="data[id_marca]" required>
                    <option value="">Seleccione una marca</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?php echo $marca['id_marca']; ?>" <?php echo isset($info['id_marca']) && $info['id_marca'] == $marca['id_marca'] ? 'selected' : ''; ?>>
                            <?php echo $marca['marca']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <!-- Descripción -->
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" name="data[descripcion]" placeholder="Descripción"
                    required><?php echo isset($info['descripcion']) ? $info['descripcion'] : ''; ?></textarea>
                <!-- Fotografía -->
                <label for="foto">Fotografía</label>
                <?php if (isset($info['fotografia']) && !empty($info['fotografia'])): ?>
                    <div class="mb-3">
                        <img src="<?= "../uploads/" . $info['fotografia'] ?>" alt="<?= $producto['producto'] ?>" alt="Fotografía del producto" class="img-thumbnail"
                            style="max-width: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" name="fotografia" placeholder="Fotografía">
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="producto.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" name="submit">Guardar</button>
        </div>
    </div>
</form>