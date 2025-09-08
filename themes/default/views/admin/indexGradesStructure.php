<?php
/* @var $this AdminController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$this->pageTitle = 'TAG - ' . Yii::t('defgault', 'Estrutura de Unidades');

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
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl("admin/gradesStructure") ?>" class="t-button-primary  "> Adicionar Estrutura</a>
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
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary table  table-striped table-hover table-primary table-vertical-center',
                    'columns' => array(
                        array(
                            'name' => 'Código',
                            'type' => 'raw',
                            'value' => '$data->id',
                        ),
                        array(
                            'name' => 'nome',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->name,Yii::app()->createUrl("admin/gradesStructure",array("id"=>$data->id)))',
                        ),
                        array(
                            'name' => 'etapa',
                            'type' => 'raw',
                            'value' => '$data->edcensoStageVsModalityFk->name',
                        ),
                        array(
                            'header' => 'Ações',
                            'class' => 'CButtonColumn',
                            'template' => '{update}{delete}',
                            'buttons' => array(
                                'update' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                                    'url' => 'Yii::app()->createUrl("admin/gradesStructure&id=$data->id")',
                                ),
                                'delete' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                                    'url' => 'Yii::app()->createUrl("admin/gradesStructureDelete&id=$data->id")',
                                    'visible' => '!empty($data->created_at) && (date("Y", strtotime($data->created_at)) >= 2025)',
                                )
                            ),
                            'updateButtonOptions' => array('style' => 'margin-right: 20px;'),
                            'deleteButtonOptions' => array('style' => 'cursor: pointer;'),
                            'htmlOptions' => array('width' => '80px', 'style' => 'text-align: center'),
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>
