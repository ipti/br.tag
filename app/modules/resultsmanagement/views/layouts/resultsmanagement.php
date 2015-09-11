<?php
/* @var $content String Conteúdo da página. */

$themeUrl = Yii::app()->theme->baseUrl;
$homeUrl = Yii::app()->homeUrl;
$year = Yii::app()->user->year;
$city = "Boquim";
$headerDescription = CHtml::tag("span", [], yii::t("resultsmanagementModule.index", 'In this area it is possible to know some of the educational indicators of the schools in the city of {city}', ['{city}' => $city]));

/* @var $schools SchoolIdentification[] */
$schools = SchoolIdentification::model()->findAll();
$schoolsCount = count($schools);
$schoolsActive = 0;
$schoolsInactive = 0;
$schoolsExtinct = 0;
foreach($schools as $school){
    if($school->situation == 1) $schoolsActive++;
    else if($school->situation == 2) $schoolsInactive++;
    else if($school->situation == 3) $schoolsExtinct++;
}

$enrollments = StudentEnrollment::model()->findAllBySql("select student_enrollment.* from student_enrollment JOIN classroom ON classroom.id = student_enrollment.classroom_fk
where classroom.school_year = :year",["year"=>$year]);
$enrollmentsCount = count($enrollments);
$enrollmentsUrban = 0;
$enrollmentsRural = 0;
$enrollmentsRegular = 0;
$enrollmentsAditional = 0;
foreach($schools as $school){
    if($school->situation == 1) $schoolsActive++;
    else if($school->situation == 2) $schoolsInactive++;
    else if($school->situation == 3) $schoolsExtinct++;
}

?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]><html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]><html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
<head>
    <title><?= CHtml::encode($this->pageTitle); ?></title>

    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>

    <link href="themes/default/lib/css/bootstrap.css" rel="stylesheet"/>
    <link href="themes/default/lib/fonts/glyphicons/css/glyphicons_social.css" rel="stylesheet"/>
    <link href="themes/default/lib/fonts/glyphicons/css/glyphicons_filetypes.css" rel="stylesheet"/>
    <link href="themes/default/lib/fonts/glyphicons/css/glyphicons_regular.css" rel="stylesheet"/>
    <link href="themes/default/lib/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="themes/default/lib/js/plugins/gallery/prettyphoto/css/prettyPhoto.css" rel="stylesheet"/>
    <link href="/themes/default/lib/extend/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="/themes/default/lib/extend/jasny-bootstrap/css/jasny-bootstrap-responsive.min.css" rel="stylesheet">
    <link href="/themes/default/lib/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">
    <link href="/themes/default/lib/extend/bootstrap-select/bootstrap-select.css" rel="stylesheet"/>
    <link href="/themes/default/lib/extend/bootstrap-switch/static/stylesheets/bootstrap-switch.css" rel="stylesheet"/>
    <link href="themes/default/lib/js/plugins/forms/select2/select2.css" rel="stylesheet"/>
    <link href="themes/default/lib/js/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet"/>

    <link href="/themes/default/common/css/style.css" rel="stylesheet"/>

</head>

<body>

<div class="container fluid menu-left">
    <div class="navbar main hidden-print">
        <a href="/" class="appbrand pull-left">
            <img src="themes/default/img/tag_logo.png"/>
<!--            <span><span>Ano atual: --><?//=$year?><!--</span></span>-->
        </a>
    </div>

    <div id="wrapper">
        <div id="content">
            <div class="header-block">
                <h2><?= yii::t('resultsmanagementModule.layout', "Results Management") ?>
                    <div class="separator bottom"></div>
                    <div class="border-header header-description">
                        <?= $headerDescription ?>
                    </div>
                </h2>
            </div>
            <div class="separator bottom"></div>
            <div class="separator-line"></div>
            <div class="innerLR content-block">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-8">
                        <?= $content ?>
                    </div>
                    <div class="col-md-4">
                        <div class="widget">
                            <div class="widget-head">
                                <h4 class="heading">
                                    <?= yii::t('resultsmanagementModule.layout', 'City Informations') ?>
                                </h4>
                            </div>
                            <div class="widget-body">
                                <div class="panel-group" id="accordion">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapseEnrollment">
                                            <?= yii::t('resultsmanagementModule.layout', 'Enrollments') ?>
                                        </a>
                                    </h4>
                                    <div id="collapseEnrollment"  class="collapse panel-content">
                                        <hr/>
                                        <h5><span><?= yii::t('resultsmanagementModule.layout', 'TOTAL SCHOOLS')?>: </span><?=$schoolsCount?></h5>
                                        <span><?= yii::t('resultsmanagementModule.layout', 'Working : {active} | Not working: {inactive} | Extinct: {extinct}',
                                                ['{active}' => $schoolsActive, "{inactive}"=>$schoolsInactive, "{extinct}"=>$schoolsExtinct])?></span>
                                        <h5><span><?= yii::t('resultsmanagementModule.layout', 'TOTAL ENROLLMENTS')?>: </span><?=$enrollmentsCount?></h5>
                                        <div class="panel-box">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <span><?=yii::t('resultsmanagementModule.layout',"URBAN AREA")?>: <?=$enrollmentsUrban?></span>
                                                    <br>
                                                    <span><?=yii::t('resultsmanagementModule.layout',"RURAL AREA")?>: <?=$enrollmentsRural?></span>
                                                </div>
                                                <div class="col-md-7">
                                                    <span><?=yii::t('resultsmanagementModule.layout',"REGULAR EDUCATION")?>: <?=$enrollmentsRegular?></span>
                                                    <br>
                                                    <span><?=yii::t('resultsmanagementModule.layout',"ADITIONAL ACTV.")?>:  <?=$enrollmentsAditional?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator bottom"></div>
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapseService">
                                            <?= yii::t('resultsmanagementModule.layout', 'Services') ?>
                                        </a>
                                    </h4>

                                    <div id="collapseService" class="collapse panel-content">
                                        <hr/>
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                        richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor
                                        brunch.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<script src="themes/default/lib/js/plugins/system/jquery.min.js"></script>
<script src="themes/default/lib/js/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="themes/default/lib/js/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<script src="themes/default/lib/js/plugins/system/modernizr.js"></script>
<script src="/themes/default/lib/js/bootstrap.min.js"></script>
<script src="themes/default/lib/js/plugins/other/jquery-slimScroll/jquery.slimscroll.min.js"></script>
<script src="themes/default/lib/js/plugins/other/holder/holder.js"></script>
<script src="themes/default/lib/js/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>
<script src="themes/default/lib/js/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>

<script>    var basePath = '/themes/default/common/';</script>

<script src="/themes/default/lib/extend/bootstrap-select/bootstrap-select.js"></script>
<script src="/themes/default/lib/extend/bootstrap-switch/static/js/bootstrap-switch.js"></script>
<script src="/themes/default/lib/extend/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"></script>
<script src="/themes/default/lib/extend/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script src="/themes/default/lib/extend/jasny-bootstrap/js/bootstrap-fileupload.js"></script>
<script src="/themes/default/lib/extend/bootbox.js"></script>
<script src="/themes/default/lib/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js"></script>
<script src="/themes/default/lib/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjWiKq1o5qX_LEokFDPUkIin3ckXpmWY0&callback=initMap"></script>

<script src="themes/default/lib/js/jquery.select2.js"></script>
<script src="themes/default/lib/js/jquery.select2.pt-br.js"></script>
</body>
</html>
