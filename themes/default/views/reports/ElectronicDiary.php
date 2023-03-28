<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto
 * Date: 06/01/2023
 * Time: 12:39
 */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/reports/electronic-diary.css');
$cs->registerScriptFile($baseUrl . '/js/reports/ElectronicDiary/index.js', CClientScript::POS_END);

$this->setPageTitle('TAG - Diário Eletrônico');

?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h1>Diário Eletrônico</h1>
    </div>
</div>

<div class="innerLR">
    <div class="alert-required-fields no-show alert alert-error hidden-print">
        Preencha os campos obrigatórios corretamente.
    </div>
    <div class="filter-bar margin-bottom-none hidden-print" style="display:flex; width:100%;">
        <input type="hidden" class="school-year" value="<?= $schoolyear ?>">
        <div class="report-filter">
            <?php echo CHtml::label("Relatório *", 'report-label', array('class' => 'control-label required electronic-diary-label')); ?>
            <select class="select-search-on electronic-diary-input" id="report">
                <option value="">Selecione...</option>
                <option value="frequency">Frequência</option>
                <option value="bulletin">Boletim</option>
            </select>
        </div>

        <div class="dependent-filters">
            <div class="classroom-container">
                <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 'control-label required electronic-diary-label')); ?>
                <select class="select-search-on electronic-diary-input" id="classroom">
                    <option value="">Selecione...</option>
                    <?php foreach ($classrooms as $classroom): ?>
                        <option value="<?= $classroom->id ?>"
                                fundamentalMaior="<?= $classroom->edcenso_stage_vs_modality_fk >= 14 && $classroom->edcenso_stage_vs_modality_fk <= 16 ? 0 : 1 ?>"><?= $classroom->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="students-container">
                <?php echo CHtml::label("Aluno *", 'student', array('class' => 'control-label required electronic-diary-label')); ?>
                <?php
                echo CHtml::dropDownList('student', '', array(), array(
                    'key' => 'id',
                    'class' => 'select-search-on',
                ));
                ?>
            </div>
            <div class="disciplines-container">
                <?php echo CHtml::label(yii::t('default', 'Discipline') . " *", 'discipline', array('class' => 'control-label required electronic-diary-label')); ?>
                <?php
                echo CHtml::dropDownList('discipline', '', array(), array(
                    'key' => 'id',
                    'class' => 'select-search-on',
                ));
                ?>
            </div>
            <div class="electronic-diary date-container">
                <?php echo CHtml::label("Período *", 'interval', array('class' => 'control-label required electronic-diary-label')); ?>
                <div style="height: 100%; padding: 5px 0; display: flex">
                <input size="10" maxlength="10" type="text" placeholder="dd/mm/aaaa" class="initial-date">
                <input size="10" maxlength="10" type="text" placeholder="dd/mm/aaaa" class="final-date">
                </div>
            </div>
            <div>
                <a id="loadreport"
                   class='btn btn-icon btn-small btn-primary glyphicons search'><?php echo Yii::t('default', 'Search') ?>
                    <i></i></a>
            </div>
            <i class="loading-report fa fa-spin fa-spinner"></i>
        </div>
    </div>
    <div class="report-container"></div>
    <button class="btn btn-icon btn-small btn-primary print-report"><i class="fa fa-print"></i> Imprimir</button>
    <div class="report-header"><?php $this->renderPartial('head'); ?></div>
    <div class="report-footer"><?php $this->renderPartial('footer'); ?></div>

</div>