<?php if (isset($alert['type']) and isset($alert['message'])): ?>
    <div class="alert alert-<?php echo $alert['type']; ?>" role="alert">
        <?php echo $alert['message']; ?>
    </div>
<?php endif; ?>