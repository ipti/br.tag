<?php
/** @var DefaultController $this DefaultController */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Diário de Classe'));
$this->breadcrumbs = array(
    $this->module->id,
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/classDiary/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/classDiary/functions.js', CClientScript::POS_END);

?>
<div class="main">
    <h1><?php echo $discipline_name ?></h1>
    <div class="t-filter-bar">
         <?php echo CHtml::label('Data', 'date', array('class' => 't-field-select__label')); ?>
        <div class="t-filters is-one-quarter t-field-text clear-margin--bottom">
           
            <?php echo CHtml::textField('data', '', array(
                'class' => 't-field-text__input js-date',
                'placeholder' => 'data',
                'id' => 'date'
            )
            ); ?>
        </div>
        <div class="t-filters">
            <a class="clear-margin--all t-button-primary show--desktop js-change-date">Pesquisar</a>
            <a class=" clear-margin--all t-button-primary t-button-primary--icon show--tablet js-change-date"><span class="t-icon-search_icon"></span></a>
        </div>
    </div>
    <hr class="row t-separator">
    <div class="row">
        <div class="column is-two-fifths t-field-select clear-margin--x js-hide-is-not-valid">
            <?php echo CHtml::label('Conteúdo', 'coursePlan', array('class' => 't-field-select__label')); ?>
            <?php echo CHtml::dropDownList('coursePlan', '',  [], array( 'class' => 'select-search-on t-field-select__input', 'id' => 'coursePlan', 'style' => 'width: 100%')); ?> 
        </div>
         <div class="column hidden t-buttons-container clearleft--on-mobile">
            <a class="clear-margin--all t-button-primary js-save-course-plan">Salvar Plano de Aula</a>
        </div>
    </div>
    <hr class="row t-separator">
    <div class="row js-accordion">

    </div>
    <div class="row">   
        <div class="column js-frequency-element"></div>    
    </div>
</div>