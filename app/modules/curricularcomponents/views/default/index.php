<?php
/** @var DefaultController $this EdcensoDisciplineController */
/** @var EdcensoDiscipline $model EdcensoDiscipline */


$this->setPageTitle('TAG - ' . Yii::t('default', 'Disciplines'));
$title = Yii::t('default', 'Disciplines');

?>


<div id="mainPage" class="main">
    <div class="row-fluid box-instructor">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Disciplines') ?></h1>
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('curricularcomponents/default/create')?>">Adicionar Componente</a>
            </div>
        </div>
        <div class="btn-group pull-right mt-30 responsive-menu dropdown-margin">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                Menu
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo Yii::app()->createUrl('curricularcomponents/default/create')?>"><i></i> Adicionar Componente</a></li>
            </ul>
        </div>
    </div>  
<div class="tag-inner">
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
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'edcenso-discipline-grid',
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns'=>array(
                        'id',
                        'name',
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