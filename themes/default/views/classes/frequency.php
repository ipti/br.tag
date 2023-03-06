<?php
/** 
* @var ClassesController $this ClassesController
* @var CActiveDataProvider $dataProvider CActiveDataProvider
*/

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/classes/frequency/_initialization.js?v=1.0', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/template2.css');
$this->setPageTitle('TAG - ' . Yii::t('default', 'Classes'));

$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

?>
<div class="main">
    <?php 
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'classes-form',
        'enableAjaxValidation' => false,
        'action' => CHtml::normalizeUrl(array('classes/saveFrequency')),
    ));
    
    ?>
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Frequency'); ?></h1>
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

    <table class="table table-bordered table-striped visible-print">
        <tr>
            <th>Escola:</th>
            <td colspan="7"><?php echo $school->inep_id . " - " . $school->name ?></td>
        <tr>
        <tr>
            <th>Estado:</th>
            <td colspan="2"><?php echo $school->edcensoUfFk->name . " - " . $school->edcensoUfFk->acronym ?></td>
            <th>Municipio:</th>
            <td colspan="2"><?php echo $school->edcensoCityFk->name ?></td>
            <th>Endereço:</th>
            <td colspan="2"><?php echo $school->address ?></td>
        <tr>
        <tr>
            <th>Localização:</th>
            <td colspan="2"><?php echo ($school->location == 1 ? "URBANA" : "RURAL") ?></td>
            <th>Dependência Administrativa:</th>
            <td colspan="4"><?php
                            $ad = $school->administrative_dependence;
                            echo ($ad == 1 ? "FEDERAL" : ($ad == 2 ? "ESTADUAL" : ($ad == 3 ? "MUNICIPAL" :
                                        "PRIVADA")));
                            ?></td>
        <tr>
    </table>
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
                <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 'control-label required' ,'style' => 'width: 64px;' )); ?>
            
                <select class="select-search-on control-input frequency-input" id="classroom">
                    <option>Selecione a turma</option>
                    <?php foreach ($classrooms as $classroom) : ?>
                        <option value="<?= $classroom->id ?>" fundamentalMaior="<?= $classroom->edcenso_stage_vs_modality_fk >= 14 && $classroom->edcenso_stage_vs_modality_fk <= 16 ? 0 : 1 ?>"><?= $classroom->name ?></option>
                    <?php endforeach; ?>
                </select>
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
                <!-- diciplina -->
            <div class="disciplines-container">
                <?php echo CHtml::label(yii::t('default', 'Discipline') . " *", 'disciplines', array('class' => 'control-label required','style' => 'width: 88px;')); ?>
                <?php
                echo CHtml::dropDownList('disciplines', '', array(), array(
                    'key' => 'id',
                    'class' => 'select-search-on control-input frequency-input',
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