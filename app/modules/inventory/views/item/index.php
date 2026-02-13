<?php
/* @var $this ItemController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - Catálogo de Itens');
$this->breadcrumbs=array(
	'Almoxarifado'=>array('movement/index'),
	'Itens',
);
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Catálogo de Itens</h1>
            <p>Lista de todos os itens cadastrados no sistema.</p>
        </div>
    </div>

    <div class="row t-buttons-container">
        <?php echo CHtml::link('Cadastrar Novo Item', array('create'), array('class'=>'t-button-primary')); ?>
    </div>

    <div class="row">
        <div class="column is-full">
            <div class="widget clearmargin">
                <div class="widget-body">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'inventory-item-grid',
                        'dataProvider'=>$dataProvider,
                        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'enableSorting' => false,
                        'afterAjaxUpdate' => 'js:function(id, data){ initDatatable(); }',
                        'columns'=>array(
                            array(
                                'name' => 'id',
                                'header' => 'ID',
                                'htmlOptions' => array('width' => '50px')
                            ),
                            array(
                                'name' => 'name',
                                'header' => 'Nome',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name, Yii::app()->createUrl("inventory/item/update", array("id"=>$data->id)))',
                                'htmlOptions' => array('class' => 'link-update-grid-view'),
                            ),
                            array(
                                'name' => 'unit',
                                'header' => 'Unidade',
                            ),
                            array(
                                'name' => 'description',
                                'header' => 'Descrição',
                            ),
                            array(
                                'header' => 'Ações',
                                'class' => 'CButtonColumn',
                                'template' => '{view}{update}{delete}',
                                'buttons' => array(
                                    'view' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/search-icon.svg',
                                    ),
                                    'update' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/editar.svg',
                                    ),
                                    'delete' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                                    )
                                ),                                
                                'updateButtonOptions' => array('style' => 'margin-right: 20px;'),
                                'deleteButtonOptions' => array('style' => 'cursor: pointer;'),
                                'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
