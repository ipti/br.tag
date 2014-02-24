<?php
/* @var $this ClassBoardController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    Yii::t('default', 'Class Boards'),
);

$this->menu = array(
    array('label' => 'Create ClassBoard', 'url' => array('create')),
    array('label' => 'Manage ClassBoard', 'url' => array('admin')),
);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classroom-form',
    'enableAjaxValidation' => false,
        ));
?>

<?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
    <div class="span12">
        <div class="heading-buttons" data-spy="affix" data-offset-top="95" data-offset-bottom="0" class="affix">
            <div class="row-fluid">
                <div class="span8">
                    <h3><?php echo Yii::t('default', 'Class Boards'); ?><span> | <?php echo Yii::t('help', 'ClassBoard Subtitle') ?></span></h3>        
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="innerLR">


    <div class="widget widget-tabs border-bottom-none">

        <div class="widget-head">
            <ul class="tab-classboard">
                <li id="tab-classboard" class="active" ><a class="glyphicons user" href="#classboard" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Class Boards') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <!-- Tab content -->
                <div class="tab-pane active" id="classboard">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'classroom_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($model, 'classroom_fk', CHtml::listData(Classroom::model()->findAll('school_inep_fk=' . Yii::app()->user->school, array('order' => 'name')), 'id', 'name'), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on',
                                        'prompt' => 'Selecione a Turma',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('classBoard/getClassBoard'),
                                            'success' => "function(events){
                                                var events = jQuery.parseJSON(events);
                                                lessons = events;
                                                if(events != null){
                                                    $.each(events, function(i, event){
                                                        calendar.fullCalendar('renderEvent',event);
                                                    });     
                                                }
                                                }",
                                    )));
                                    ?>
                                    <?php echo $form->error($model, 'classroom_fk'); ?>
                                </div>
                            </div>
                        </div>

<!--                        <div class="span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'estimated_classes', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
//                                    foreach ([] as $discipline) {
//                                        echo '';
//                                    }
//                                    echo $form->dropDownList($model, 'classroom_fk', CHtml::listData(Classroom::model()->findAll('school_inep_fk=' . Yii::app()->user->school, array('order' => 'name')), 'id', 'name'), array(
//                                        'key' => 'id',
//                                        'class' => 'select-search-on',
//                                        'prompt' => 'Selecione a Turma',
//                                        'ajax' => array(
//                                            'type' => 'POST',
//                                            'url' => CController::createUrl('classBoard/getClassBoard'),
//                                            'success' => "function(data){}",
//                                    )));
                                    ?>
                                    <?php echo $form->error($model, 'classroom_fk'); ?>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="row-fluid">         
                        <div id='loading' style='display:none'>loading...</div>
                        <div id='calendar'></div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>

    <div id="create-dialog-form" title="<?php echo Yii::t('default', 'Insert class'); ?>">
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?php echo CHtml::label( Yii::t('default','Discipline'), 'discipline', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::dropDownList('discipline', '', CHtml::listData(EdcensoDiscipline::model()->findAll(array('order' => 'name')), 'id', 'name'),array('prompt'=> 'Selecione a disciplina','class' => 'select-search-on')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="update-dialog-form" title="<?php echo Yii::t('default', 'Update class'); ?>">
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?php echo CHtml::label( Yii::t('default','Discipline'), 'update-discipline', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::dropDownList('update-discipline', '', CHtml::listData(EdcensoDiscipline::model()->findAll(array('order' => 'name')), 'id', 'name'),array('prompt'=> 'Selecione a disciplina','class' => 'select-search-on')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



