<?php
/* @var $this DefaultController */

$this->breadcrumbs = array(
	$this->module->id,
);

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '\powerbi.js?v='.TAG_VERSION, CClientScript::POS_END);
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
			id: '<?= $reportId ?>',
            settings: {
                panes: {
                    filters: {
                        visible: false // Hide the filter pane
                    },
                    pageNavigation: {
                        visible: true
                    }
                },
                bars: {
                    statusBar: {
                        visible: true
                    }
                }
            }
        };
        const reportContainer = document.getElementById('reportContainer');
        const report = powerbi.embed(reportContainer, config);

        const currentHost = window.location.hostname;

        const filter = {
            $schema: "http://powerbi.com/product/schema#basic",
            target: {
                table: "Accumulated_Data",
                column: "Database_name"
            },
            operator: "In",
            values: [currentHost]
        };
		// Embed the dashboard and display it within the div container.
		powerbi.embed(reportContainer, config);
        report.on('loaded', function() {
            report.updateFilters(models.FiltersOperations.Add, [filter])
                .then(function() {
                    console.log('Filter applied');
                })
                .catch(function(errors) {
                    console.error('Error applying filter:', errors);
                });
	});
})
</script>
