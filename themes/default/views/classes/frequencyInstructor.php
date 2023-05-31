<?php

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
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
    <div class="tag-inner">
        <div class="alert-required-fields no-show alert alert-error">
            Os Campos com * são obrigatórios.
        </div>
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
    </div>
    <?php $this->endWidget(); ?>
</div>