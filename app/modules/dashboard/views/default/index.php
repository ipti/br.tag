<?php
/* @var $this DefaultController */

$this->breadcrumbs = array(
	$this->module->id,
);

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '\powerbi.js', CClientScript::POS_END);
?>
<style>
	#reportContainer {
            height: 100%;
            min-height: 100%;
            display: block;
        }
		.main {
			height: 100vh;
		}
</style>
<div id="mainPage" class="main">
	<div id="reportContainer" class="fill"></div>
</div>
<script>
	$(function () {


		var embedToken = '<?php echo $token ?>';
		const models = window['powerbi-client'].models;
		const config = {
			type: 'report',
			tokenType: models.TokenType.Embed,
			accessToken: embedToken,
			embedUrl: '<?= $embedUrl ?>',
			id: '<?= $reportId ?>'
		};
		// Get a reference to the embedded dashboard HTML element 
		const reportContainer = $('#reportContainer')[0];
		// Embed the dashboard and display it within the div container. 
		powerbi.embed(reportContainer, config);
	});
</script>