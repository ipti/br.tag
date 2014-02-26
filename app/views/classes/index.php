<?php
/* @var $this ClassesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Classes',
);

$this->menu = array(
    array('label' => 'Create Classes', 'url' => array('create')),
    array('label' => 'Manage Classes', 'url' => array('admin')),
);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classes-form',
    'enableAjaxValidation' => false,
        ));
?>

<?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
    <div class="span12">
        <div class="heading-buttons" data-spy="affix" data-offset-top="95" data-offset-bottom="0" class="affix">
            <div class="row-fluid">
                <div class="span8">
                    <h3><?php echo Yii::t('default', 'Classes'); ?><span> | <?php echo Yii::t('help', 'Classes subtitle') ?></span></h3>        
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
                                <?php echo CHtml::label(yii::t('default', 'Classroom'), 'classroom', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo CHtml::dropDownList('classroom', '', CHtml::listData(Classroom::model()->findAll('school_inep_fk=' . Yii::app()->user->school, array('order' => 'name')), 'id', 'name'), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on',
                                        'prompt' => 'Selecione a turma',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('classes/getDisciplines'),
                                            'update' => '#disciplines',
                                    )));
                                    ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo CHtml::label(yii::t('default', 'Month'), 'month', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo CHtml::dropDownList('month', '', array(1 => 'Janeiro',
                                        2 => 'Fevereiro',
                                        3 => 'Março',
                                        4 => 'Abril',
                                        5 => 'Maio',
                                        6 => 'Junho',
                                        7 => 'Julho',
                                        8 => 'Agosto',
                                        9 => 'Setembro',
                                        10 => 'Outubro',
                                        11 => 'Novembro',
                                        12 => 'Dezembro'), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on',
                                        'prompt' => 'Selecione o mês',
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <a id="classesSearch" class='btn btn-icon btn-primary glyphicons search'><?php echo Yii::t('default', 'Search') ?><i></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <?php echo CHtml::label(yii::t('default', 'Disciplines'), 'disciplines', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo CHtml::dropDownList('disciplines', '', array(), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on',
                                        'prompt' => 'Selecione a disciplina',
                                    ));
                                    ?>
                                </div>
                            </div> 
                        </div>
                    </div> <hr>
                    <div class="row-fluid">
                        <div class="span12">

                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $('#classroom').on('change', function(){
        $('#disciplines').val('').trigger('change');
    });
    $('#classesSearch').on('click', function(){
        jQuery.ajax({
            'type':'POST',
            'url':'/tag/index.php?r=classes/getClasses',
            'cache':false,
            'data':jQuery('#classroom').parents("form").serialize(),
            'success':function(html){
                //Mostrar tabela de frequencia
                //jQuery("#Classroom_assistance_type").html(html); 
                //jQuery("#Classroom_assistance_type").trigger('change');
            }});
    });
</script>