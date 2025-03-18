<form action="<?php echo $action === 'create' ? '?action=create' : ''; ?>" method="POST"
    class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3><?php echo $action === 'create' ? 'Crear Cuenta' : 'Iniciar Sesión'; ?></h3>
        </div>
        <div class="card-body">
            <?php if (!empty($alert)): ?>
                <div class="alert alert-<?php echo $alert['type']; ?>">
                    <?php echo $alert['message']; ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" maxlength="100" class="form-control"
                    name="<?php echo $action === 'create' ? 'data[correo]' : 'correo'; ?>"
                    placeholder="Correo electrónico" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" maxlength="255" class="form-control"
                    name="<?php echo $action === 'create' ? 'data[password]' : 'password'; ?>" placeholder="Contraseña"
                    required>
            </div>
            <!-- <?php if ($action === 'create'): ?>
                <div class="form-group">
                    <label for="token">Token (Opcional)</label>
                    <input type="text" class="form-control" name="data[token]" placeholder="Token (si aplica)">
                </div>
            <?php endif; ?> -->
        </div>
        <div class="card-footer d-flex justify-content-between">
            <?php if ($action === 'create'): ?>
                <a href="?" class="btn btn-secondary">Volver al Login</a>
                <button type="submit" class="btn btn-primary" name="submit">Registrar</button>
            <?php else: ?>
                <a href="?action=create" class="btn btn-secondary">Crear usuario</a>
                <button type="submit" class="btn btn-primary" name="submit">Iniciar Sesión</button>
            <?php endif; ?>
        </div>
    </div>
</form>