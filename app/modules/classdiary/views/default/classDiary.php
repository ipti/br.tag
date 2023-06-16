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
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classDiary',
    'enableAjaxValidation' => false,
)
);
?>
<div class="main">
    <h1>Diário de Classe</h1>
    <div class="t-filter-bar">
        <div class="t-filters is-one-quarter t-field-text clear-margin--bottom">
            <?php echo CHtml::textField('data', '', array(
                'class' => 't-field-text__input js-date',
                'placeholder' => 'data'
            )
            ); ?>
        </div>
        <div class="t-filters">
            <a class="clear-margin--all t-button-primary js-search">Pesquisar</a>
        </div>
    </div>
    <div class="row js-frequency-element">

    </div>
    <?php $this->endWidget(); ?>
</div>