<?php

$this->breadcrumbs = array(
    $this->module->id,
);
?>

<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Matricula Online'));
    ?>
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Matricula Online') ?></h1>
        </div>
    </div>

    <div class="tag-inner">

        <div class="">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
            <?php elseif (Yii::app()->user->hasFlash('error')): ?>
                <div class="alert alert-error">
                    <?php echo Yii::app()->user->getFlash('error') ?>
                </div>
            <?php else: ?>
                <div class="alert no-show"></div>
            <?php endif; ?>
            <div class="widget clearmargin">
                <div class="widget-body">
                    <?php

                    $this->widget('zii.widgets.grid.CGridView',[
                        'dataProvider' => $dataProvider,
                        'enablePagination' => true,
                        'enableSorting' => false,
                        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => $columns,

                    ]);
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>
