<?php
/** @var DefaultController $this DefaultController */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Diário de Classe'));
$this->breadcrumbs = array(
    $this->module->id,
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/classDays/_initialization.js', CClientScript::POS_END);
?>
<div class="main">
<h1><?php echo $discipline_name ?></h1>
    <hr class="row t-separator" />
    <div class="t-filter-bar">
        <div class="t-filters is-one-quarter t-field-text clear-margin--bottom">
            <div class="t-field-select">
                <label class="t-field-select__label--required">Mês/Ano</label>
                <select class="t-field-select__input js-months">
                    <option value="">Selecione um mês</option>
                </select>
            </div>
        </div>
    </div>
    <hr class="row t-separator" />
    <div class="row wrap js-days-cards">

    </div>
</div>
