<?php
/* @var $this FarmerRegisterController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Farmer Registers',
);

$this->menu=array(
	array('label'=>'Create FarmerRegister', 'url'=>array('create')),
	array('label'=>'Manage FarmerRegister', 'url'=>array('admin')),
);

$this->setPageTitle('TAG - Agricultores');
$title = "Agricultores";
?>



<div id="mainPage" class="main">
    <div class="row">
        <h1>Agricultores</h1>
    </div>
    <div class="row">
        <div class="t-buttons-container">
            <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('foods/farmerregister/create')?>">Adicionar Agricultor</a>
            <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('foods/farmerregister/activateFarmers')?>">Exibir Inativos</a>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
        <br/>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
        <br/>
    <?php endif ?>

    <div class="tag-inner">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'farmer-register-grid',
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns'=>array(
                        'id',
                        'name',
                        'cpf',
                        'status',
                        array(
                            'header' => 'Ações',
                            'class' => 'CButtonColumn',
                            'template' => '{update}{delete}',
                            'buttons' => array(
                                'update' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl.'/img/editar.svg',
                                ),
                                'delete' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                                )
                            ),
                            'afterDelete' => 'window.location.href = "?r=foods/farmerregister/index";',
                            'updateButtonOptions' => array('style' => 'margin-right: 20px;', 'class'=>"stageUpdate"),
                            'deleteButtonOptions' => array('style' => 'cursor: pointer;', 'class'=>"stageDelete"),
                            'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                        ),
                    ),
                )); ?>
            </div>
        </div>
    </div>
</div>
