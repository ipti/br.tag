<?php
/* @var $this EnrollmentOnlineStudentIdentificationController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - '. Yii::t('default', 'Solicitações de Matrícula'));
$this->breadcrumbs=array(
	$this->module->id,
);

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
?>

<div id="content">
    <div class="main">
        <h1>Solicitações de Matrícula</h1>
        <div class="js-add-solicitations">
        </div>
    </div>
</div>
