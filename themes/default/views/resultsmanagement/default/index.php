<?php
/* @var $this DefaultController */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cityName = "Município";
$this->headerDescription = CHtml::tag("span", [], yii::t("resultsmanagementModule.index", 'In this area it is possible to know some of the educational indicators of the schools in the city of {city}', ['{city}' => '']));

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript("variables",
    "var URLGetMapInfos = '".$this->createUrl("GetGMapInfo")."';", CClientScript::POS_BEGIN);
$cs->registerScriptFile($baseScriptUrl.'/common/js/index/loadMap.js', CClientScript::POS_END);
$cs->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=&callback=initMap', CClientScript::POS_END, ["async", "defer"] );
$cs->registerScriptFile('http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js', CClientScript::POS_END);
$cs->registerCssFile($baseScriptUrl.'/common/css/resultsmanagement.css');

?>
<h3><?=$cityName?></h3>
<!--<div id="map-canvas" style="width: 100%; height: 400px;"></div>-->
<style>
#sidewid .widget{display:none;}
</style>
<div class="col-md">
     <?php if(!Yii::app()->user->hardfoot){$this->widget('resultsmanagement.components.sideInfoWidget');}?>
</div>