<?php
/* @var $this StudentIMCController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Student Imcs',
);

$this->menu = array(
    array('label' => 'Create StudentIMC', 'url' => array('create')),
    array('label' => 'Manage StudentIMC', 'url' => array('admin')),
);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Acompanhamento de Saúde'));
?>

<div class="main">

    <h1><?= $student->name ?></h1>

    <div class="tag-inner">
        <div class="row-fluid">
            <div class="span12">
                <div class="t-buttons-container">
                    <a class="t-button-primary" href="<?= Yii::app()->createUrl("studentimc/studentimc/create", array("studentId" => $student->id)) ?>">
                        Nova Coleta
                    </a>
                     <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('forms/studentIMCHistoryReport', array("studentId" => $student->id)) ?>">Histórico de coleta</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="t-badge-info t-margin-none--left">
                <span class="t-info_positive"></span>
                Maior IMC: <?= $highest ?>
            </div>
            <div class="t-badge-info t-margin-none--left">
                <span class="t-info_positive"></span>
                Menor IMC: <?= $lowest ?>
            </div>
            <div class="t-badge-info t-margin-none--left">
                <span class="t-info_positive"></span>
                Taxa de variação: <?= $variationRate ?>
            </div>
        </div>
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => array(
                        array(
                            'name' => 'id',
                            'type' => 'raw',
                            'value' => '$data->id',
                        ),
                        array(
                            'name' => 'IMC',
                            'type' => 'raw',
                            'value' => 'CHtml::link(number_format((float) $data->IMC, 2, ".", ""),Yii::app()->createUrl("studentimc/studentimc/update",array("id"=>$data->id)))',
                            'htmlOptions' => array('width' => '400px', 'class' => 'link-update-grid-view'),
                        ),
                        array(
                            'name' => 'Classificação',
                            'type' => 'raw',
                            'value' => '$data->studentImcClassificationFk->classification',
                        ),
                        array(
                            'name' => 'height',
                            'type' => 'raw',
                            'value' => 'number_format((float) $data->height, 2, ".", "")',
                        ),
                        array(
                            'name' => 'weight',
                            'type' => 'raw',
                            'value' => 'number_format((float) $data->weight, 2, ".", "")',
                        ),
                        array(
                            'name' => 'created_at',
                            'filter' => false,
                            'value' => 'Yii::app()->dateFormatter->format("dd/MM/yyyy", $data->created_at)',
                        ),
                        array(
                            'header' => 'Ações',
                            'class' => 'CButtonColumn',
                            'template' => '{update}{delete}',
                            'buttons' => array(
                                'update' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                                ),
                                'delete' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                                )
                            ),
                            'updateButtonOptions' => array('style' => 'margin-right: 20px;'),
                            'deleteButtonOptions' => array('style' => 'cursor: pointer;'),
                            'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>
