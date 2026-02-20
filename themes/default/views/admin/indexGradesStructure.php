<?php
/* @var $this GradesStructureController */

$this->pageTitle = 'TAG - ' . Yii::t('default', 'Estrutura de Unidades');
?>

<div class="main">

    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Estruturas'); ?></h1>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('gradesStructure/create') ?>"> Adicionar Estrutura</a>
            </div>
        </div>
    </div>
    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('error')): ?>
            <div class="alert alert-error">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
            <br/>
        <?php endif ?>

        <?php if (Yii::app()->user->hasFlash('notice')):?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('notice') ?>
            </div>
        <?php endif ?>

        <div class="widget clearmargin">
            <div class="widget-body">
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting'    => false,
                    'ajaxUpdate'       => false,
                    'itemsCssClass'    => 'js-tag-table tag-table-primary table table-striped table-hover table-primary table-vertical-center',
                    'columns' => array(
                        array(
                            'name'  => 'Código',
                            'type'  => 'raw',
                            'value' => '$data->id',
                        ),
                        array(
                            'name'  => 'nome',
                            'type'  => 'raw',
                            'value' => 'CHtml::link($data->name, Yii::app()->createUrl("gradesStructure/create", array("id" => $data->id)))',
                        ),
                        array(
                            'name'  => 'Ano de Vigência',
                            'type'  => 'raw',
                            'value' => '$data->school_year',
                        ),
                        array(
                            'name'  => 'etapa',
                            'type'  => 'raw',
                            'value' => '$data->edcensoStageVsModalityFk->name',
                        ),
                        array(
                            'header'     => 'Ações',
                            'type'       => 'raw',
                            'htmlOptions'=> array('width' => '100px', 'style' => 'text-align: center; white-space: nowrap;'),
                            'value'      =>
                                'CHtml::link("", "#", array(' .
                                    '"class" => "t-button-icon t-icon-copy js-open-copy-modal",' .
                                    '"title" => "Copiar",' .
                                    '"data-id"   => $data->id,' .
                                    '"data-name" => $data->name,' .
                                ')) . " " .' .
                                'CHtml::link(CHtml::image(Yii::app()->theme->baseUrl . "/img/editar.svg"), Yii::app()->createUrl("gradesStructure/create", array("id" => $data->id)), array("title" => "Editar")) . " " .' .
                                'CHtml::link(CHtml::image(Yii::app()->theme->baseUrl . "/img/deletar.svg"), Yii::app()->createUrl("gradesStructure/delete", array("id" => $data->id)), array("title" => "Excluir"))',
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     Modal – Copiar Estrutura
     ============================================================ -->
<div id="modal-copy-structure" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalCopyLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="modalCopyLabel">Copiar Estrutura</h3>
    </div>
    <form id="form-copy-structure" method="GET" action="/">
        <input type="hidden" name="r" value="gradesStructure/copy">
        <input type="hidden" id="copy-structure-id" name="id" value="">
        <div class="modal-body">
            <p id="copy-structure-name" style="font-weight: bold; margin-bottom: 12px;"></p>
            <div class="control-group">
                <label class="control-label" for="copy-target-year">Ano de destino</label>
                <div class="controls">
                    <input type="number"
                           id="copy-target-year"
                           name="year"
                           class="input-small"
                           min="2000"
                           max="2100"
                           value="<?php echo (int) Yii::app()->user->year; ?>"
                           required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Copiar</button>
        </div>
    </form>
</div>

<script>
$(document).on('click', '.js-open-copy-modal', function (e) {
    e.preventDefault();
    var $btn  = $(this);
    var id    = $btn.data('id');
    var name  = $btn.data('name');
    $('#copy-structure-id').val(id);
    $('#copy-structure-name').text(name);
    $('#modal-copy-structure').modal('show');
});
</script>
