<div class="widget-scroll margin-bottom-none warn-widget" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false" total="<?= $total ?>">
    <div class="alerta" hidden><?= $total ?></div>
    <div class="home-page-table-header ">
        <h5 class="t-margin-medium--left text-color--white">Cadastros Pendentes</h5>
    </div>
    <div id="warns" class="widget-body warns in" style="height: auto;">
        <?= $html ?>
        <?php if ($total > $limit): ?>
            <span class="t-button-primary load-more warn-list">Carregar mais</span>
        <?php endif; ?>
    </div>
</div>
