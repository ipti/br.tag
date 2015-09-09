<?php
/* @var $this DefaultController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript("variables",
    "var URLGetMapInfos = '".$this->createUrl("GetGMapInfo")."';", CClientScript::POS_BEGIN);
$cs->registerScriptFile('themes/default/common/js/loadMap.js', CClientScript::POS_END);
?>
<div id="map-canvas" style="width:700px; height: 400px;"></div>