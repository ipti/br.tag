<?php
/* @var $this DefaultController */

$cityName = "Boquim";
$this->headerDescription = CHtml::tag("span", [], yii::t("resultsmanagementModule.index", 'In this area it is possible to know some of the educational indicators of the schools in the city of {city}', ['{city}' => $cityName]));

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript("variables",
    "var URLGetMapInfos = '".$this->createUrl("GetGMapInfo")."';", CClientScript::POS_BEGIN);
$cs->registerScriptFile('/themes/default/common/js/loadMap.js', CClientScript::POS_END);
$cs->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDjWiKq1o5qX_LEokFDPUkIin3ckXpmWY0&callback=initMap', CClientScript::POS_END, ["async", "defer"] );
$cs->registerScriptFile('http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js', CClientScript::POS_END);
$cs->registerCssFile('/themes/default/common/css/resultsmanagement.css');

?>
<h3><?=$cityName?></h3>
<div id="map-canvas" style="width: 100%; height: 400px;"></div>