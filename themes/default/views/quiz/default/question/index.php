<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Question'));
    $this->menu = array(
        array('label' => Yii::t('default', 'Create Question'), 'url' => array('createQuestion'), 'description' => Yii::t('default', 'This action create a new Question')),
    );
    ?>

    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Question') ?></h1>  
            <div class="t-buttons-container">
                <a href="<?php echo Yii::app()->createUrl('quiz/default/createQuestion') ?>" class="t-button-primary"><i></i> <?= Yii::t('default', 'New Question') ?> </a>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <div class="columnone" style="padding-right: 1em">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <div class="widget">
                <div class="widget-body">
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'enableSorting' => false,
                        'dataProvider' => $dataProvider,
                        // 'filter' => $filter,
                        'itemsCssClass' => 'js-tag-table tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'enablePagination' => false,
                        'columns' => array(
                            array(
                                'name' => 'id',
                                'htmlOptions' => array('width' => '150px'),
                            ),
                            array(
                                'name' => 'description',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->description ,Yii::app()->createUrl("quiz/default/updateQuestion", array("id"=>$data->id)))',
                            ))
                    ));
                    ?>
                </div>   
            </div>
        </div>
    </div>
</div>
