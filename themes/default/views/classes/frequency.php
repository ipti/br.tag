<?php

/** 
 * @var ClassesController $this ClassesController
 * @var CActiveDataProvider $dataProvider CActiveDataProvider
 */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/classes/frequency/_initialization.js?v=1.0', CClientScript::POS_END);
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
        <div id="select-container" class="tablet-row align-items--center-on-desktop">
            <!-- Mês e componente curricular -->
            <div class="mobile-row">
                <!-- Mês -->
                <div class="column clearleft">
                    <div class="t-field-select">
                        <?php echo CHtml::label(yii::t('default', 'Month') . " *", 'month', array('class' => 't-field-select__label--required')); ?>
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
                            'class' => 'select-search-on t-field-select__input',
                            'prompt' => 'Selecione o mês',
                        ));
                        ?>
                    </div>
                </div>
                <div class="column">
                    <div class="t-field-select">
                        <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 't-field-select__label--required')); ?>
                        <select class="select-search-on frequency-input t-field-select__input" id="classroom">
                            <option>Selecione a turma</option>
                            <?php foreach ($classrooms as $classroom) : ?>
                                <option value="<?= $classroom->id ?>" fundamentalMaior="<?= !TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk) ?>"><?= $classroom->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- diciplina -->
                </div>
            </div>
            <div class="mobile-row helper">
                <div class="column  clearleft on-tablet disciplines-container">
                    <div class="t-field-select" id="classesSearch">
                        <?php echo CHtml::label(yii::t('default', 'Discipline') . " *", 'disciplines', array('class' => 't-field-select__label--required')); ?>
                        <?php
                        echo CHtml::dropDownList('disciplines', '', array(), array(
                            'key' => 'id',
                            'class' => 'select-search-on t-field-select__input',
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-row">
            <!-- <div class="column no-grow">
                <a id="classesSearch" class='t-button-icon secondary'>
                    <span class="t-icon-search_icon"></span>-->
            <!-- <?php echo Yii::t('default', 'Search') ?> -->
            <!-- </a> 
            </div> -->
            <img class="loading-frequency" style="display:none;margin: 10px 20px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
        </div>
        <div class="alert-incomplete-data alert alert-warning display-hide"></div>
        <div id="frequency-container" class="table-responsive frequecy-container"></div>
    </div>
    <?php $this->endWidget(); ?>

</div>

<div class="modal fade t-modal-container helper" id="save-justification-modal" tabindex="-1" role="dialog" aria-labelledby="Save Justification">
    <div class="modal-dialog" role="document">
        <div class="t-modal__header">
            <h4 class="modal-title" id="myModalLabel">Justificativa</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
        </div>
        <div class="centered-loading-gif">
            <i class="fa fa-spin fa-spinner"></i>
        </div>
        <form method="post">
            <div class="t-modal__body">
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

                <div class="t-modal__footer mobile-row">
                    <button type="button" class="t-button-primary btn-save-justification">Adicionar</button>
                </div>
            </div>
        </form>
    </div>
</div>