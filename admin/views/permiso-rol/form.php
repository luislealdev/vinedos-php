<form action="permiso-rol.php?action=<?php echo isset($_GET['id']) ? 'update&id=' . $_GET['id'] : 'create'; ?>"
    method="POST" class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3><?php echo isset($_GET['id']) ? 'Editar' : 'Crear'; ?> permiso</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <!-- Select with permisos -->
                <select class="form-control" name="data[id_permiso]" required>
                    <option value="">Seleccione un permiso</option>
                    <?php foreach ($permisos as $permiso): ?>
                        <option value="<?php echo $permiso['id_permiso']; ?>" <?php echo isset($info['id_permiso']) && $info['id_permiso'] == $permiso['id_permiso'] ? 'selected' : ''; ?>>
                            <?php echo $permiso['permiso']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Select with roles  -->
                <label for="nombre">Rol</label>
                <select class="form-control" name="data[id_rol]" required>
                    <option value="">Seleccione un rol</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?php echo $rol['id_rol']; ?>" <?php echo isset($info['id_rol']) && $info['id_rol'] == $rol['id_rol'] ? 'selected' : ''; ?>>
                            <?php echo $rol['rol']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="permiso.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" name="submit">Guardar</button>
        </div>
    </div>
</form>