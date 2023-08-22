<?php
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/site/index.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/amcharts/amcharts.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/amcharts/serial.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/amcharts/pie.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/amcharts/lang/pt.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/amcharts/themes/light.js', CClientScript::POS_END);

/* @var $this SiteController */

$cs->registerScript(
	"vars",
	"var loadMoreLogs = '" . $this->createUrl("site/loadMoreLogs") . "'; " .
		"var loadLineChartData = '" . $this->createUrl("site/loadLineChartData") . "'; " .
		"var loadCylinderChartData = '" . $this->createUrl("site/loadCylinderChartData") . "'; " .
		"var loadPieChartData = '" . $this->createUrl("site/loadPieChartData") . "'; ",
	CClientScript::POS_HEAD
);

$this->pageTitle = Yii::app()->name . '';
$this->breadcrumbs = [
	'',
];

$year = Yii::app()->user->year;

$logCount = count(Log::model()->findAll("school_fk = :school", [':school' => Yii::app()->user->school]));
?>

<div class="main">
	<div class="row-fluid">
		<div class="span12">
			<h1>Bem vindo ao Tag
				<!-- <img class="tag-logo"  alt="tag logo" style="width:65px;" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_navbar.svg" /></h1> -->
		</div>
	</div>
	<div class="tag-inner eggs">
		<div class="board-msg" version="<?php echo TAG_VERSION; ?>"><?php echo BOARD_MSG; ?></div>
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-scroll margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false" total="<?= $logCount ?>">
				<div class="aviso" hidden><?= $logCount ?></div>
					<div class="home-page-table-header">
						<h5>Atividades Recentes</h5>
					</div>
					<div class="widget-body logs in" style="height: auto;">
						<?= $html ?>
						<span class="t-button-primary load-more"> Carregar mais</span>
					</div>
				</div>
			</div>
			<div class="span2">
				<div>
					<div class="next-events widget widget-scroll widget-gray margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
						<!--  <div class="widget-head"><h5 class="heading glyphicons calendar"><i class="fa fa-bars"></i>Etapas da pré-matrícula</h5></div>
						<div class="widget-body" style="height: 385px;">
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
					</div> -->
					</div>
				</div>
			</div>
			<!--    <div class="row-fluid home-container">-->
			<!--        <div class="span6">-->
			<!--            <div class="widget widget-scroll widget-gray margin-bottom-none"-->
			<!--                 data-toggle="collapse-widget" data-scroll-height="223px"-->
			<!--                 data-collapse-closed="false" total="--><? //= $logCount 
																		?><!--">-->
			<!--                <div class="widget-head"><h5 class="heading glyphicons parents"><i></i>Alunos Matriculados</h5>-->
			<!--                </div>-->
			<!--                <div class="widget-body in" style="height: auto;">-->
			<!--                    <div id="pieChart"></div>-->
			<!--                </div>-->
			<!--            </div>-->
			<!--        </div>-->
			<!--        <div class="span6">-->
			<!--            <div class="widget widget-scroll widget-gray margin-bottom-none"-->
			<!--                 data-toggle="collapse-widget" data-scroll-height="223px"-->
			<!--                 data-collapse-closed="false" total="--><? //= $logCount 
																		?><!--">-->
			<!--                <div class="widget-head"><h5 class="heading glyphicons database_plus"><i></i>Base de Dados</h5>-->
			<!--                </div>-->
			<!--                <div class="widget-body in" style="height: auto;">-->
			<!--                    <div id="cylinderChart"></div>-->
			<!--                </div>-->
			<!--            </div>-->
			<!--        </div>-->
			<!--    </div>-->
			<!--    <div class="row-fluid lineChart-container">-->
			<!--        <div class="span12">-->
			<!--            <div class="widget widget-scroll widget-gray margin-bottom-none"-->
			<!--                 data-toggle="collapse-widget" data-scroll-height="223px"-->
			<!--                 data-collapse-closed="false" total="--><? //= $logCount 
																		?><!--">-->
			<!--                <div class="widget-head"><h5 class="heading glyphicons calendar"><i></i>Cadastros anuais</h5>-->
			<!--                </div>-->
			<!--                <div class="widget-body in" style="height: auto;">-->
			<!--                    <div id="lineChart"></div>-->
			<!--                </div>-->
			<!--            </div>-->
			<!--        </div>-->
			<!--    </div>-->
		</div>
	</div>
</div>
<script>
	$(".pre-enrollment-icon").on('click', function() {
		var str = $(this).attr('class');
		if (str.match(/circle-o/g) != null) {
			$(this).removeClass('fa-circle-o');
			$(this).addClass('fa-circle');
		} else {
			$(this).removeClass('fa-circle');
			$(this).addClass('fa-circle-o');
		}
	});
</script>