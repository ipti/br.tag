<!-- _logItem.php -->
<div class="log-item">
    <?php if ($data['type'] == 'header'): ?>
        <strong>Arquivo: <?php echo CHtml::encode($data['file']); ?></strong>
    <?php else: ?>
        <pre><?php echo CHtml::encode($data['line']); ?></pre>
    <?php endif; ?>
</div>
