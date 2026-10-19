<?php $this->pageTitle = 'Logs da Aplicação — Ferramentas'; ?>

<style>
.logs-page        { padding: 24px; max-width: 1200px; }
.logs-header      { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.logs-header h1   { margin: 0; font-size: 20px; font-weight: 700; color: #252A31; font-family: 'Inter', sans-serif; }
.logs-header p    { margin: 4px 0 0; color: #5F738C; font-size: 13px; font-family: 'Inter', sans-serif; }
.logs-back        { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: #3f45ea; text-decoration: none; margin-bottom: 16px; font-family: 'Inter', sans-serif; }
.logs-back:hover  { text-decoration: underline; }

.log-file-header {
    background: #252A31;
    color: #a6adc8;
    font-family: monospace;
    font-size: 12px;
    padding: 6px 14px;
    border-radius: 4px 4px 0 0;
    margin-top: 20px;
    margin-bottom: 0;
}
.log-entry {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    padding: 4px 14px;
    border-left: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    line-height: 1.5;
    word-break: break-all;
    white-space: pre-wrap;
    color: #2d3748;
    background: #fafafe;
}
.log-entry:last-child  { border-bottom: 1px solid #e2e8f0; border-radius: 0 0 4px 4px; }
.log-entry.error       { background: #fff5f5; color: #c53030; }
.log-entry.warning     { background: #fffbeb; color: #92400e; }

.logs-empty {
    background: #f7f8fa;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 32px;
    text-align: center;
    color: #5F738C;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
}
</style>

<div class="logs-page">

    <a href="<?= Yii::app()->createUrl('tools/default/index') ?>" class="logs-back">
        ← Voltar para Ferramentas
    </a>

    <div class="logs-header">
        <span class="t-icon-info" style="font-size: 22px; color: #3f45ea;"></span>
        <div>
            <h1>Logs da Aplicação</h1>
            <p>Entradas de log do dia <strong><?= date('d/m/Y') ?></strong>. Exibindo as mais recentes primeiro.</p>
        </div>
    </div>

    <?php if ($dataProvider->getTotalItemCount() === 0): ?>
        <div class="logs-empty">
            Nenhum arquivo de log encontrado para hoje.
        </div>
    <?php else: ?>

        <?php
        $widget = $this->widget('zii.widgets.CListView', [
            'dataProvider' => $dataProvider,
            'itemView'     => false,
            'template'     => '{pager}{items}{pager}',
        ]);
        ?>

        <?php foreach ($dataProvider->getData() as $entry): ?>
            <?php if ($entry['type'] === 'header'): ?>
                <div class="log-file-header">📄 <?= CHtml::encode($entry['file']) ?></div>
            <?php else: ?>
                <?php
                $line = $entry['line'];
                $class = 'log-entry';
                if (stripos($line, 'error') !== false || stripos($line, 'exception') !== false) {
                    $class .= ' error';
                } elseif (stripos($line, 'warning') !== false) {
                    $class .= ' warning';
                }
                ?>
                <div class="<?= $class ?>"><?= CHtml::encode($line) ?></div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div style="margin-top: 16px;">
            <?= $this->widget('CLinkPager', ['pages' => $dataProvider->getPagination()], true) ?>
        </div>

    <?php endif; ?>

</div>
