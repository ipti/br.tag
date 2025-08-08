<div class="main">
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
    $cs->registerScriptFile($baseUrl . '/js/reports/ElectronicDiary/index.js?v=' . TAG_VERSION, CClientScript::POS_END);

    $this->setPageTitle('TAG - Diário Eletrônico');

    ?>

    <div class="row-fluid hidden-print">
        <div class="span12">
            <h1>Diário Eletrônico</h1>
        </div>
    </div>

    <div class="js-alert alert" style="display:none;"></div>
    <div class="innerLR">
        <div class="alert-report no-show alert alert-error hidden-print"></div>
        <div class="filter-bar margin-bottom-none hidden-print" style="display:flex; width:100%; flex-wrap: wrap;">
            <input type="hidden" class="school-year" value="<?= $schoolyear ?>">
            <div class="report-filter">
                <?php echo CHtml::label('Relatório *', 'report-label', ['class' => 'control-label required electronic-diary-label']); ?>
                <select class="select-search-on electronic-diary-input" id="report">
                    <option value="">Selecione...</option>
                    <option value="frequency">Frequência</option>
                    <option value="gradesByStudent">Notas por Aluno</option>
                </select>
            </div>

            <div class="dependent-filters">
                <div class="classroom-container">
                    <?php echo CHtml::label(yii::t('default', 'Classroom') . ' *', 'classroom', ['class' => 'control-label required electronic-diary-label']); ?>
                    <select class="select-search-on electronic-diary-input" id="classroom">
                        <option value="">Selecione...</option>
                        <?php foreach ($classrooms as $classroom): ?>
                            <option value="<?= $classroom->id ?>"
                                    fundamentalMaior="<?= !TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk) ?>"
                                    isMultiStage="<?=TagUtils::isMultiStage($classroom->edcenso_stage_vs_modality_fk)?>"><?= $classroom->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="students-container" style="display:none;">
                    <?php echo CHtml::label('Aluno *', 'student', ['class' => 'control-label required electronic-diary-label']); ?>
                    <?php
                    echo CHtml::dropDownList('student', '', [], [
                        'key' => 'id',
                        'class' => 'select-search-on',
                    ]);
    ?>
            </div>
            <div class="stages-container" style="display:none;">
                    <?php echo CHtml::label('Etapa *', 'stage', ['class' => 'control-label required electronic-diary-label']); ?>
                    <?php
        echo CHtml::dropDownList('stage', '', [], [
            'key' => 'id',
            'class' => 'select-search-on',
        ]);
    ?>
            </div>
            <div class="electronic-diary date-container" style="display:none;">
                    <?php echo CHtml::label('Período *', 'interval', ['class' => 'control-label required electronic-diary-label']); ?>
                    <div style="height: 100%; padding:5px 0 0 0; display: flex">
                    <input size="10" maxlength="10" type="text" placeholder="dd/mm/aaaa" class="initial-date">
                    <input size="10" maxlength="10" type="text" placeholder="dd/mm/aaaa" class="final-date">
                    </div>
            </div>
            <div class="disciplines-container" style="display:none;">
                    <?php echo CHtml::label(yii::t('default', 'Discipline') . ' *', 'discipline', ['class' => 'control-label required electronic-diary-label']); ?>
                    <?php
    echo CHtml::dropDownList('discipline', '', [], [
        'key' => 'id',
        'class' => 'select-search-on',
    ]);
    ?>
                </div>
             <div>
                    <a id="loadreport"
                    class='t-button-primary'><?php echo Yii::t('default', 'Search') ?>
                        </a>
                </div>
                <img class="loading-report"  style="display:none;margin: 10px 20px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
        </div>
        <div class="report-container"></div>
        <button class="btn btn-icon btn-small btn-primary print-report"><i class="fa fa-print"></i> Imprimir</button>
        <div class="report-header"><?php $this->renderPartial('head'); ?></div>
        <div class="report-footer"><?php $this->renderPartial('footer'); ?></div>

    </div>
</div>
