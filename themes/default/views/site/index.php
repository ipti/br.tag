<?php
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/site/index.js?v=' . TAG_VERSION, CClientScript::POS_END);

/* @var $this SiteController */

$cs->registerScript(
    "vars",
    "var loadMoreLogs = '" . $this->createUrl("site/loadMoreLogs") . "'; " .
    "var loadMoreWarns = '" . $this->createUrl("site/loadMoreWarns") . "'; " .
    "var loadLineChartData = '" . $this->createUrl("site/loadLineChartData") . "'; " .
    "var loadCylinderChartData = '" . $this->createUrl("site/loadCylinderChartData") . "'; " .
    "var loadPieChartData = '" . $this->createUrl("site/loadPieChartData") . "'; " .
    "var loadWarns = '" . $this->createUrl("site/loadWarnsHtml") . "';",
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
        </div>
    </div>
    <div class="tag-inner eggs">
        <div class="board-msg" version="<?php echo TAG_VERSION; ?>"><?php echo BOARD_MSG; ?></div>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-scroll margin-bottom-none log-widget" data-toggle="collapse-widget"
                    data-scroll-height="223px" data-collapse-closed="false" total="<?= $logCount ?>">
                    <div class="aviso" hidden><?= $logCount ?></div>
                    <div class="home-page-table-header ">
                        <h5 class="t-margin-medium--left text-color--white">Atividades Recentes</h5>
                    </div>
                    <div class="widget-body logs in" style="height: auto;">
                        <?= $htmlLogs ?>
                        <span class="t-button-primary load-more info-list"> Carregar mais</span>
                    </div>
                </div>
            </div>
            <div class="span2">
                <div>
                    <div class="next-events widget widget-scroll widget-gray margin-bottom-none"
                        data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
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
            <?php if (!Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id) && !Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id)): ?>
                <div class="row-fluid">
                    <div class="span12">
                        <div id="warns">
                            <div class="widget-scroll margin-bottom-none warn-widget" data-toggle="collapse-widget"
                                data-scroll-height="223px" data-collapse-closed="false">
                                <div class="home-page-table-header ">
                                    <h5 class="t-margin-medium--left text-color--white">Cadastros Pendentes</h5>
                                </div>
                                <div class="widget-body warns in" style="height: auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
<script>
    $(".pre-enrollment-icon").on('click', function () {
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
