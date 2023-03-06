<?php 

/* @var $this InstructorController */
/* @var $dataProvider CActiveDataProvider */

    $baseUrl = Yii::app()->baseUrl;
	$themeUrl = Yii::app()->theme->baseUrl;
	$cs = Yii::app()->getClientScript();
	$cs->registerScriptFile($baseUrl . '/js/instructor/frequency.js', CClientScript::POS_END);
	$cs->registerCssFile($themeUrl . '/css/template2.css');
	$this->setPageTitle('TAG - ' . Yii::t('default', 'Instructor frequency'));
    ?>
<div class="main">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'classes-form',
        'enableAjaxValidation' => false,
        'action' => CHtml::normalizeUrl(array('instructor/saveFrequency')),
    ));
    ?>

    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Instructor frequency'); ?></h1>
            <h5> Marcar apenas faltas.</h5>
            <div class="buttons span9">
                <!--            <a id="print" class='btn btn-icon glyphicons print hidden-print'>-->
                <?php //echo Yii::t('default', 'Print') 
                ?><!--<i></i></a>-->
                <!--            <a href="-->
                <?php //echo Yii::app()->createUrl('reports/bfreport') 
                ?><!--" class='btn btn-icon glyphicons print hidden-print'>Bolsa Familia<i></i></a>-->
                <!-- <a id="save" class='btn btn-icon btn-primary glyphicons circle_ok hidden-print'><?php echo Yii::t('default', 'Save') ?><i></i></a> -->
            </div>
        </div>
    </div>
    <br>
    <div class="tag-inner">

        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div class="alert-required-fields no-show alert alert-error">
            Os Campos com * são obrigatórios.
        </div>
        <div class="row filter-bar margin-bottom-none">
            <div>
                <?php echo CHtml::label(yii::t('default', 'Instructor') . " *", 'instructor', array('class' => 'control-label required' ,'style' => 'width: 80px;' )); ?>
            
                <select class="select-search-on control-input frequency-input" id="instructor">
                    <option>Selecione o professor</option>
                    <?php foreach ($instructors as $instructor) : ?>
                        <option value="<?= $instructor->id ?>"><?= $instructor->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- turmas -->
            <div class="classroom-container" style="display: none">
                <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 'control-label required' ,'style' => 'width: 64px;' )); ?>
            
                <?php
                echo CHtml::dropDownList('classrooms', '', array(), array(
                    'key' => 'id',
                    'class' => 'select-search-on control-input frequency-input',
                ));
                ?>
            </div>

            <!-- disciplina -->
            <div class="disciplines-container" style="display: none">
                <?php echo CHtml::label(yii::t('default', 'Discipline') . " *", 'disciplines', array('class' => 'control-label required','style' => 'width: 88px;')); ?>
                <?php
                echo CHtml::dropDownList('disciplines', '', array(), array(
                    'key' => 'id',
                    'class' => 'select-search-on control-input frequency-input',
                ));
                ?>
            </div>

            <div>
                <?php echo CHtml::label(yii::t('default', 'Month') . " *", 'month', array('class' => 'control-label required','style' => 'width: 53px;')); ?>
                <?php
                echo CHtml::dropDownList('month', '', array(
                    1 => 'Janeiro',
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
                    12 => 'Dezembro'
                ), array(
                    'key' => 'id',
                    'class' => 'select-search-on control-input frequency-input',
                    'width: 53px;',
                    'prompt' => 'Selecione o mês',
                ));
                ?>
            </div>
            <div class="row">
                    <a id="classesSearch" class='t-button-primary'><i class="fa-search fa icon-button-tag"></i><?php echo Yii::t('default', 'Search') ?>
                    </a>
                </div>
                <i class="loading-frequency fa fa-spin fa-spinner"></i>
            </div>

            <div class="alert-incomplete-data alert alert-warning display-hide"></div>
            <div id="frequency-container" class="table-responsive"></div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>

<div class="modal fade" id="save-justification-modal" tabindex="-1" role="dialog" aria-labelledby="Save Justification">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="cancel-save-justification close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Justificativa</h4>
                </div>
                <div class="centered-loading-gif">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <?= chtml::label("Justificativa", "title", array('class' => 'control-label')); ?>
                                <div class="form-control">
                                    <input type="hidden" id="justification-classroomid">
                                    <input type="hidden" id="justification-studentid">
                                    <input type="hidden" id="justification-day">
                                    <input type="hidden" id="justification-month">
                                    <input type="hidden" id="justification-schedule">
                                    <input type="hidden" id="justification-fundamentalmaior">
                                    <textarea class="justification-text span12"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-save-justifiaction btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-save-justification">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
</div>