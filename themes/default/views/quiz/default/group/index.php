<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Group'));
    $this->menu = array(
        array('label' => Yii::t('default', 'Create Group'), 'url' => array('quizCreate'), 'description' => Yii::t('default', 'This action create a new Group')),
    );
    ?>

    <div class="row-fluid">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Group') ?></h3>  
            <div class="buttons">
                <a href="<?php echo Yii::app()->createUrl('quiz/default/createGroup') ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> <?= Yii::t('default', 'New Group') ?> </a>
            </div>
        </div>
    </div>

    <div class="innerLR">
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
                        'dataProvider' => $filter->search(),
                        'filter' => $filter,
                        'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'enablePagination' => true,
                        'columns' => array(
                            array(
                                'name' => 'id',
                                'htmlOptions' => array('width' => '150px')
                            ),
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name ." [" . $data->quiz->name . "]",Yii::app()->createUrl("quiz/default/updateGroup", array("id"=>$data->id)))',
                            ))
                    ));
                    ?>
                </div>   
            </div>
        </div>
    </div>
</div>
