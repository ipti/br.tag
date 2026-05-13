<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Group'));
    $this->menu = array(
        array('label' => Yii::t('default', 'Create Group'), 'url' => array('quizCreate'), 'description' => Yii::t('default', 'This action create a new Group')),
    );
    ?>

    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Group') ?></h1>  
            <div class="t-buttons-container">
                <a href="<?php echo Yii::app()->createUrl('quiz/default/createGroup') ?>" class="t-button-primary"><i></i> <?= Yii::t('default', 'New Group') ?> </a>
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
                        // 'dataProvider' => $filter->search(),
                        'dataProvider' => $dataProvider,
                        // $filter->search(),
                        // 'filter' => $filter,
                        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'enablePagination' => false,
                        'columns' => array(
                            array(
                                'name' => 'id',
                                'htmlOptions' => array('width' => '150px')
                            ),
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name ." [" . $data->quiz->name . "]",Yii::app()->createUrl("quiz/default/updateGroup", array("id"=>$data->id)))',
                            )
                        )
                    ));
                    ?>
                </div>   
            </div>
        </div>
    </div>
</div>
