<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Question Group'));
    $this->menu = array(
        array('label' => Yii::t('default', 'Create Question Group'), 'url' => array('createQuestionGroup'), 'description' => Yii::t('default', 'This action create a new Question Group')),
    );
    ?>

    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Question Group') ?></h1>  
            <div class="buttons">
                <a href="<?php echo Yii::app()->createUrl('quiz/default/createQuestionGroup') ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> <?= Yii::t('default', 'New Question Group') ?> </a>
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
                                'name' => 'question_group_id',
                                'htmlOptions' => array('width' => '150px')
                            ),
                            array(
                                'name' => 'question_id',
                                'type' => 'raw',
                                'value' => 'CHtml::link(Question::model()->findByPk($data->question_id)->description,Yii::app()->createUrl("quiz/default/updateQuestionGroup", array("questionGroupId"=>$data->question_group_id, "questionId"=>$data->question_id)))',
                            ))
                    ));
                    ?>
                </div>   
            </div>
        </div>
    </div>
</div>
