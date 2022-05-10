<?php
	$baseUrl = Yii::app()->baseUrl;
	$cs = Yii::app()->getClientScript();
	$cs->registerScriptFile($baseUrl . '/js/site/index.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/amcharts/amcharts.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/amcharts/serial.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/amcharts/pie.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/amcharts/lang/pt.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/amcharts/themes/light.js', CClientScript::POS_END);
	/* @var $this SiteController */

	$cs->registerScript("vars",
		"var loadMoreLogs = '" . $this->createUrl("site/loadMoreLogs") . "'; " .
		"var loadLineChartData = '" . $this->createUrl("site/loadLineChartData") . "'; " .
		"var loadCylinderChartData = '" . $this->createUrl("site/loadCylinderChartData") . "'; " .
		"var loadPieChartData = '" . $this->createUrl("site/loadPieChartData") . "'; ", CClientScript::POS_HEAD);

	$this->pageTitle = Yii::app()->name . '';
	$this->breadcrumbs = [
		'',
	];

	$year = Yii::app()->user->year;

	$logCount = count(Log::model()->findAll("school_fk = :school", [':school' => Yii::app()->user->school]));
?>


<div class="row-fluid">
	<div class="span12">
		<h3 class="heading-mosaic">Página Inicial</h3>
	</div>
</div>
<div class="innerLR eggs">
	<?php echo BOARD_MSG; ?>
	<div class="row-fluid">
		<div class="span9">
			<div class="widget widget-scroll widget-gray margin-bottom-none"
			     data-toggle="collapse-widget" data-scroll-height="223px"
			     data-collapse-closed="false" total="<?= $logCount ?>">
				<div class="widget-head"><h5 class="heading glyphicons history"><i></i>Atividades Recentes</h5>
				</div>
				<div class="widget-body logs in" style="height: auto;">
					<?= $html ?>
					<span class="load-more fa fa-plus-circle"> Carregar mais</span>
				</div>
			</div>
		</div>
		<div class="span3">
			<div>
				<div class="next-events widget widget-scroll widget-gray margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
					<div class="widget-head"><h5 class="heading glyphicons calendar"><i class="fa fa-bars"></i>Etapas da pré-matrícula</h5></div>
					<div class="widget-body events in" style="height: auto;">
						<div>
							<i class="fa fa-circle-o left pre-enrollment-icon"></i>
							<span class="actual-date"><strong> Reaproveitamento das turmas</strong></span>
						</div>
						<div>
							<i class="fa fa-circle-o left pre-enrollment-icon"></i>
							<span class="actual-date"><strong> Pré-matrícula dos alunos</strong></span>
						</div>
						<div>
							<i class="fa fa-circle-o left pre-enrollment-icon"></i>
							<span class="actual-date"><strong> Impressão das fichas</strong></span>
						</div>
						<div>
							<i class="fa fa-circle-o left pre-enrollment-icon"></i>
							<span class="actual-date"><strong> Confirmação de matrícula</strong></span>
						</div>
					</div>
				</div>
			</div>
			</div>

		</div>
	</div>
</div>
<script>
$(".pre-enrollment-icon").on('click', function () {
        var str = $(this).attr('class');
		if(str.match(/circle-o/g) != null){
			$(this).removeClass('fa-circle-o');
			$(this).addClass('fa-circle');
		}
		else{
			$(this).removeClass('fa-circle');
			$(this).addClass('fa-circle-o');
		}
    });

</script>