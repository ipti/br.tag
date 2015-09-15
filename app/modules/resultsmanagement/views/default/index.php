<?php
/* @var $this DefaultController */

$cityName = "Boquim";

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript("variables",
    "var URLGetMapInfos = '".$this->createUrl("GetGMapInfo")."';", CClientScript::POS_BEGIN);
$cs->registerScriptFile('/themes/default/common/js/loadMap.js', CClientScript::POS_END);
$cs->registerCssFile('/themes/default/common/css/resultsmanagement.css');
?>
<h3>Boquim</h3>
<div id="map-canvas" style="width: 100%; height: 400px;"></div>