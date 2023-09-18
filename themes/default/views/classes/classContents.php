<?php

/**  
 * @var ClassesController $this ClassesController
 * @var CActiveDataProvider $dataProvider CActiveDataProvider
 *
 */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/classes/class-contents/_initialization.js?v=1.0', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classes/class-contents/functions.js?v=1.0', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Classes Contents'));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classes-form',
    'enableAjaxValidation' => false,
    'action' => CHtml::normalizeUrl(array('classes/saveClassContents')),
));

$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

?>
<div class="main">
    <div class="row">
        <h1><?php echo Yii::t('default', 'Class Contents'); ?></h1>
    </div>
    <div class="mobile-row justify-content--space-between">
        <div class="t-buttons-container auto-width">
            <a id="print" class='t-button-secondary hide'>
                <span class="t-icon-printer"></span>
                <?php echo Yii::t('default', 'Print') ?>
            </a>
        </div>
        <a id="save" class='t-button-secondary hide'><?php echo Yii::t('default', 'Save') ?></a>
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
            <td colspan="2"><?php echo $school->location == 1 ? "URBANA" : "RURAL" ?></td>
            <th>Dependência Administrativa:</th>
            <td colspan="4"><?php
                            $ad = $school->administrative_dependence;
                            echo $ad == 1 ? "FEDERAL" : ($ad == 2 ? "ESTADUAL" : ($ad == 3 ? "MUNICIPAL" :
                                "PRIVADA"));
                            ?></td>
        <tr>
    </table>
    <br>
    <div>
        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
    <div class="alert-save no-show alert alert-success">
        Aulas ministradas atualizadas com sucesso!
    </div>
    <div class="alert-required-fields no-show alert alert-error">
        Os campos com * são obrigatórios.
    </div>
    <div id="select-container" class="tablet-row align-items--center-on-desktop">
        <div class="mobile-row">
            <div class="column clearleft">
                <div class="t-field-select">
                    <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 'control-label t-field-select__label--required', 'style' => 'width: 53px;')); ?>
                    <select class="select-search-on t-field-select__input " id="classroom" name="classroom">
                        <option>Selecione a turma</option>
                        <?php foreach ($classrooms as $classroom) : ?>
                            <option value="<?= $classroom->id ?>" fundamentalmaior="<?= !TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk) ?>"><?= $classroom->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="column">
                <div class="t-field-select">
                    <?php echo CHtml::label(yii::t('default', 'Month') . " *", 'month', array('class' => 'control-label t-field-select__label--required', 'style' => 'width: 53px;')); ?>
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
                        'class' => 'select-search-on t-field-select__input ',
                        'prompt' => 'Selecione o mês',
                    ));
                    ?>
                </div>
            </div>
        </div>   
        <div class="mobile-row helper">
            <div class="column clearleft on-tablet disciplines-container" style="display: none;">
                <div class="t-field-select">
                    <?php echo CHtml::label(yii::t('default', 'Discipline') . " *", 'disciplines', array('class' => 'control-label t-field-select__label--required')); ?>
                    <?php
                    echo CHtml::dropDownList('disciplines', '', array(), array(
                        'key' => 'id',
                        'class' => 'select-search-on t-field-select__input ',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="column clearleft on-tablet align-items--center ">
            <img class="loading-class-contents"  height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
        </div>
    </div>
    <div class="clear"></div>
    <div class="widget" id="widget-class-contents" style="display:none; margin-top: 8px;">
        <table id="class-contents" class="tag-table-secondary table-bordered" aria-labelledby="create class contents">
            <thead>
            </thead>
            <tbody>
            <tr>
                <td class="center">1</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="error-badge"></div>
</div>
    <div class="row">
        <div class="t-buttons-container">
            <a id="save-button-mobile" class='t-button-primary align-items--center hide'><?php echo Yii::t('default', 'Save') ?></a>  
        </div>        
    </div>

    <div class="modal fade t-modal-container" id="js-classroomdiary" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">      
                <div class="t-modal__header">
                    <h4 class="t-title" id="myModalLabel">Diário de Aula</h4>   
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="">
                    </button>             
                </div>
                <form method="post">
                <input type="hidden" class="classroom-diary-day">
                    <div class="t-modal__body">
                        <div class="t-field-tarea">
                            <label class="t-field-tarea__label">Diário de Aula Geral</label>
                            <textarea class="t-field-tarea__input large classroom-diary-textarea" placeholder="Digite"></textarea>
                        </div>
                        
                        <label>Diário de Aula por Aluno</label>
                        <div class="alert alert-error classroom-diary-no-students no-show">Não há alunos matriculados na turma.</div>
                        <div class="accordion-students"></div>
                        
                    
                        <div class="t-modal__footer row reverse">
                            <div class="t-buttons-container justify-content--center">
                                <button type="button" class="t-button-secondary" data-dismiss="modal">Cancelar</button>
                            </div>
                            <div class="t-buttons-container justify-content--center">
                                <button type="button" class="t-button-primary clear-margin--right js-add-classroom-diary" data-dismiss="modal">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->endWidget(); ?>