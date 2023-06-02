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
    <div class="tag-inner">

        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div class="alert-required-fields no-show alert alert-error">
            Os Campos com * são obrigatórios.
        </div>
        <!-- Mês e componente curricular -->
        <div class="mobile-row">
            <!-- Mês -->
            <div class="column helper">
                <div class="t-field-select__helper">
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
                <!-- diciplina -->
                <div class="t-field-select__helper">
                    <?php echo CHtml::label(yii::t('default', 'Discipline') . " *", 'disciplines', array('class' => 't-field-select__label--required')); ?>
                    <?php
                    echo CHtml::dropDownList('disciplines', '', array(), array(
                        'key' => 'id',
                        'class' => 'select-search-on t-field-select__input',
                    ));
                    ?>
                </div>
            </div>
            <div class="column helper">
                <div class="t-field-select__helper" <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 't-field-select__label--required')); ?> <select class="select-search-on frequency-input t-field-select__input" id="classroom">
                    <option>Selecione a turma</option>
                    <?php foreach ($classrooms as $classroom) : ?>
                        <option value="<?= $classroom->id ?>" fundamentalMaior="<?= $classroom->edcenso_stage_vs_modality_fk >= 14 && $classroom->edcenso_stage_vs_modality_fk <= 16 ? 0 : 1 ?>"><?= $classroom->name ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="">
                    <a id="classesSearch" class='t-button-primary'><i class="fa-search fa icon-button-tag"></i><?php echo Yii::t('default', 'Search') ?>
                    </a>
                </div>
                <img class="loading-frequency" style="display:none;margin: 10px 20px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
            </div>
        </div>
        <div class="alert-incomplete-data alert alert-warning display-hide"></div>
        <div class="t-accordeon">
            <input type="checkbox" id="item-1" class="t-accordeon"></input>
            <label for="item-1"><span></span>Expandable Item 1</label>
            <article>
                <div id="frequency-container" class="table-frequency">

                </div>
            </article>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div>

<div class="modal fade modal-content" id="save-justification-modal" tabindex="-1" role="dialog" aria-labelledby="Save Justification">
    <div class="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
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

                <div class="modal-footer">
                    <button type="button" class="cancel-save-justifiaction btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-save-justification">Adicionar</button>
                </div>
            </div>
        </form>
    </div>
</div>