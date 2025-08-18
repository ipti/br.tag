<?php
/** @var DefaultController $this DefaultController */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Diário de Classe'));
$this->breadcrumbs = [
    $this->module->id,
];
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/index/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/index/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
?>
<div class="main">
	<h1>Diário de Classe</h1>
	<div class="row wrap js-add-classrooms-cards">
	</div>
</div>
