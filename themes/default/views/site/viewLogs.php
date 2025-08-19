<h1 style="text-align: center;">Logs da Aplicação </h1>
<div style="margin: 0 auto;">
<a style="margin-left:30px; margin-bottom: 10px; text-align: center;" href="<?php echo $this->createUrl('site/downloadFileLog'); ?>" target="_blank" rel="noopener noreferrer"><button>Download</button></a>
</div>
<div style="margin: 0 auto;">

    <!-- Caixa de exibição dos logs -->
    <div style="background-color: #f8f9fa; padding: 15px; border: 1px solid #ddd; max-height: 600px; overflow-y: auto;">
        <pre style="white-space: pre-wrap; word-wrap: break-word;"><?php echo CHtml::encode($logContent); ?></pre>
    </div>

    <!-- Controles de paginação -->
    <div style="text-align: center; margin-top: 20px;">
        <div class="pagination">
            <?php if($currentPage > 1): ?>
                <a href="<?php echo $this->createUrl('site/viewFileLogs', array('page' => $currentPage - 1)); ?>" style="padding: 10px 15px; border: 1px solid #ccc; background-color: #f1f1f1; text-decoration: none; margin-right: 5px;">&laquo; Anterior</a>
            <?php endif; ?>

            Página <?php echo $currentPage; ?> de <?php echo $totalPages; ?>

            <?php if($currentPage < $totalPages): ?>
                <a href="<?php echo $this->createUrl('site/viewFileLogs', array('page' => $currentPage + 1)); ?>" style="padding: 10px 15px; border: 1px solid #ccc; background-color: #f1f1f1; text-decoration: none; margin-left: 5px;">Próxima &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Estilos adicionais -->
<style>
    .pagination a:hover {
        background-color: #ddd;
        border-color: #bbb;
    }
</style>
