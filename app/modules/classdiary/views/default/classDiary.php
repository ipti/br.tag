<?php
/** @var DefaultController $this DefaultController */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Diário de Classe'));
$this->breadcrumbs = array(
    $this->module->id,
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/classDiary/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/classDiary/functions.js?v='.TAG_VERSION, CClientScript::POS_END);

?>
<div class="main">
    <div class="row">
        <div class="column">
            <h1><?php echo $classroom_name .' - '. $date?></h1>
            <h2><?php echo $discipline_name ?? null ?></h2>
        </div>
    </div>
    <div class="alert alert-error js-validate hide">
    </div>
    <div class="row js-hide-is-not-valid" style="display:none;">
        <div class="column">
            <hr class="t-separator">
            <h2>Conteúdo ministrado em sala de aula</h2>
            <div class="row">
                <div class="column is-two-fifths t-multiselect clearfix js-hide-is-not-valid  t-margin-none--top t-margin-none--bottom">
                    <?php echo CHtml::dropDownList('coursePlan', '',  [],
                    array('multiple' => 'multiple',
                    'class' => 'select-search-on t-margin-none--top t-multiselect multiselect',
                    'id' => 'coursePlan', 'style' => 'width: 100%;')); ?>
                </div>
            </div>
            <div class="row">
                <div class="column t-buttons-container clearfix">
                    <a class="clear-margin--all t-button-primary js-save-course-plan">Salvar Aula Ministrada</a>
                    <a class="clear-margin--all t-button-secondary js-new-class-content">Nova Aula</a>
                </div>
            </div>
            <div class="hide js-add-new-class-content-form">
                <h2>Adicionar aula</h2>
                <div class="row">
                    <div class="column is-two-fifths clearfix t-field-select">
                        <label for="" class="t-field-select__label--required">Plano de aula</label>
                        <?php echo CHtml::dropDownList('coursePlans', '',  $coursePlans,
                            array(
                            'class' => 'select-search-on t-margin-none--top js-course-plan-id t-field-select__input',
                            "prompt" => 'Selecione um plano',
                            'id' => 'coursePlan', 'style' => 'width: 100%;')); ?>
                    </div>
                </div>
                <div class="row t-field-text">
                    <div class="t-field-tarea t-margin-none--bottom clearfix column is-two-fifths">
                            <label for="class-content" class="t-field-select__label--required">Conteúdo</label>
                            <textarea id="class-content" class="t-field-tarea__input course-class-content js-class-content-textarea" placeholder="Digite o conteúdo ministrado" rows="10"></textarea>
                    </div>
                </div>
                <div class="row t-field-text">
                    <div class="t-field-text clearfix column is-two-fifths">
                        <label for="class-content-methodology" class="t-field-select__label--required">Metodologia</label>
                        <textarea for="class-content-methodology" class="t-field-tarea__input course-class-methodology js-class-content-methodology" placeholder="Digite o conteúdo ministrado" rows="10"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="column is-two-fifths clearfix t-field-select">
                        <label for="" class="t-field-select__label--required">Habilidade(s)</label>
                        <?php echo CHtml::dropDownList('abilities', '',  $abilities,
                            array(
                            'class' => 'select-search-on t-margin-none--top t-field-select__input js-add-abilities',
                            "prompt" => 'Selecione um plano',
                            'id' => 'coursePlan', 'style' => 'width: 100%;')); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="column is-two-fifths courseplan-abilities-selected clearfix">

                    </div>
                </div>
            </div>
            <hr class="t-separator">
        </div>
    </div>
    <div class="t-accordeon-primary js-course-classes-accordion">

    </div>
    <div class="row">
        <div class="column js-frequency-element"></div>
    </div>
</div>
