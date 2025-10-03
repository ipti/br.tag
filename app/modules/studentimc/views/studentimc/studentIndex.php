<?php

use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\Value;
/* @var $this StudentIMCController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Student Imcs',
);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Alunos'));

$this->menu = array(
    array('label' => 'Create StudentIMC', 'url' => array('create')),
    array('label' => 'Manage StudentIMC', 'url' => array('admin')),
);
?>
<div class="main">

    <h1>Alunos</h1>

    <div class="tag-inner">
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
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->name,Yii::app()->createUrl("studentimc/studentimc/create",array("id"=>$data->id)))',
                            'htmlOptions' => array('width' => '400px', 'class' => 'link-update-grid-view'),
                        ),
                        array(
                            'name' => 'documents',
                            'header' => 'CPF',
                            'value' => '$data->documentsFk->cpf',
                            'htmlOptions' => array('width' => '400px')
                        ),
                        array(
                            'name' => 'birthday_date',
                            'filter' => false,
                            'value' => 'Yii::app()->dateFormatter->format("dd/MM/yyyy", $data->birthday)',
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

