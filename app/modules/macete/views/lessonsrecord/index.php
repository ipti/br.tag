<?php
/* @var $this LessonsrecordController */
/* @var $classrooms Classroom[] */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/lesson-record-days.js?v=' . TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - Registrar Aula MACETE');
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1>Registrar Aula MACETE</h1>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success"><?php echo Yii::app()->user->getFlash('success'); ?></div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
    <?php endif; ?>

    <div class="mobile-row align-items--end">
        <div class="column t-field-select clearleft is-one-quarter">
            <label class="t-field-select__label--required">Turma</label>
            <select id="macete-record-classroom" class="select-search-on t-field-select__input">
                <option value="">Selecione a turma</option>
                <?php foreach ($classrooms as $classroom): ?>
                    <option value="<?php echo (int) $classroom->id; ?>"><?php echo CHtml::encode($classroom->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="column t-field-select is-one-quarter">
            <label class="t-field-select__label--required">Mês</label>
            <select id="macete-record-month" class="select-search-on t-field-select__input" disabled>
                <option value="">Selecione a turma primeiro</option>
            </select>
        </div>
        <div class="column t-buttons-container">
            <label class="t-field-select__label" style="visibility: hidden;">Imprimir</label>
            <a id="macete-record-print" class="t-button-secondary hide" target="_blank" rel="noopener">
                <span class="t-icon-printer"></span> Imprimir relatório do mês
            </a>
        </div>
    </div>

    <div id="macete-record-error" class="alert alert-error hide"></div>

    <div class="js-macete-record-subtitle hide">
        <h3>Selecione um dia para registrar ou ver a aula</h3>
    </div>
    <div id="macete-record-days" class="row wrap"></div>
</div>
