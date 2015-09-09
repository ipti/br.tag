<?php
/* @var $content String Conteúdo da página. */

$themeUrl = Yii::app()->theme->baseUrl;
$homeUrl = Yii::app()->homeUrl;
$year = Yii::app()->user->year;
$city = "Boquim";
$headerDescription = CHtml::tag("span", [],
    yii::t("resultsmanagementModule.index", 'In this area it is possible to know some of the educational indicators of the schools in the city of {city}', ['{city}' => $city])
);

?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]>
<html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!-->
<html><!-- <![endif]-->
<head>
    <title><?= CHtml::encode($this->pageTitle); ?></title>

    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>

    <!-- Bootstrap -->
    <link href="themes/default/lib/css/bootstrap.css" rel="stylesheet"/>

    <!-- Glyphicons Font Icons -->
    <link href="themes/default/lib/fonts/glyphicons/css/glyphicons_social.css" rel="stylesheet"/>
    <link href="themes/default/lib/fonts/glyphicons/css/glyphicons_filetypes.css" rel="stylesheet"/>
    <link href="themes/default/lib/fonts/glyphicons/css/glyphicons_regular.css" rel="stylesheet"/>

    <!-- Font Awsome Icons -->
    <link href="themes/default/lib/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>

    <!-- Uniform Pretty Checkboxes -->
    <link href="themes/default/lib/js/plugins/forms/pixelmatrix-uniform/css/uniform.default.css" rel="stylesheet"/>

    <!-- PrettyPhoto -->
    <link href="themes/default/lib/js/plugins/gallery/prettyphoto/css/prettyPhoto.css" rel="stylesheet"/>

    <!--[if IE]><!-->
    <script src="themes/default/lib/js/plugins/other/excanvas/excanvas.js"></script>
    <!--<![endif]-->
    <!--[if lt IE 8]>
    <script src="themes/default/lib/js/plugins/other/json2.js"></script>
    <![endif]-->

    <!-- Bootstrap Extended -->
    <link href="/themes/default/lib/extend/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="/themes/default/lib/extend/jasny-bootstrap/css/jasny-bootstrap-responsive.min.css" rel="stylesheet">
    <link href="/themes/default/lib/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">
    <link href="/themes/default/lib/extend/bootstrap-select/bootstrap-select.css" rel="stylesheet"/>
    <link href="/themes/default/lib/extend/bootstrap-switch/static/stylesheets/bootstrap-switch.css" rel="stylesheet"/>

    <!-- Select2 Plugin -->
    <link href="themes/default/lib/js/plugins/forms/select2/select2.css" rel="stylesheet"/>

    <!-- DateTimePicker Plugin -->
    <link href="themes/default/lib/js/plugins/forms/bootstrap-datetimepicker/css/datetimepicker.css" rel="stylesheet"/>

    <!-- JQueryUI -->
    <link href="themes/default/lib/js/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css"
          rel="stylesheet"/>

    <!-- MiniColors ColorPicker Plugin -->
    <link href="themes/default/lib/js/plugins/color/jquery-miniColors/jquery.miniColors.css" rel="stylesheet"/>

    <!-- Notyfy Notifications Plugin -->
    <link href="themes/default/lib/js/plugins/notifications/notyfy/jquery.notyfy.css" rel="stylesheet"/>
    <link href="themes/default/lib/js/plugins/notifications/notyfy/themes/default.css" rel="stylesheet"/>

    <!-- Gritter Notifications Plugin -->
    <link href="themes/default/lib/js/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet"/>

    <!-- Easy-pie Plugin -->
    <link href="themes/default/lib/js/plugins/charts/easy-pie/jquery.easy-pie-chart.css" rel="stylesheet"/>

    <!-- Google Code Prettify Plugin -->
    <link href="themes/default/lib/js/plugins/other/google-code-prettify/prettify.css" rel="stylesheet"/>

    <!-- Main Theme Stylesheet :: CSS -->
    <link href="/themes/default/common/css/style.css" rel="stylesheet"/>

</head>

<body>

<div class="container fluid menu-left">

    <div class="navbar main hidden-print">
        <a href="/" class="appbrand pull-left">
            <img src="themes/default/img/tag_logo.png"/>
            <!--<span><span>Ano atual: --><? //=$year?><!--</span></span>-->
        </a>
    </div>

    <div id="wrapper">
        <!-- Content -->
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
                                    <div id="collapseEnrollment" class="collapse" style="height: auto;">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                        richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard
                                        dolor brunch.
                                    </div>
                                    <div class="separator bottom"></div>
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapseService">
                                            <?= yii::t('resultsmanagementModule.layout', 'Services') ?>
                                        </a>
                                    </h4>

                                    <div id="collapseService" class="collapse" style="height: auto;">
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
        <!-- // Content END -->

    </div>
    <div class="clearfix"></div>
    <!-- // Sidebar menu & content wrapper END -->

</div>
<!-- // Main Container Fluid END -->


<!-- JQuery -->
<script src="themes/default/lib/js/plugins/system/jquery.min.js"></script>
<script src="themes/default/lib/js/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="themes/default/lib/js/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<script src="themes/default/lib/js/plugins/system/modernizr.js"></script>
<script src="/themes/default/lib/js/bootstrap.min.js"></script>

<!-- SlimScroll Plugin -->
<script src="themes/default/lib/js/plugins/other/jquery-slimScroll/jquery.slimscroll.min.js"></script>


<!-- Holder Plugin -->
<script src="themes/default/lib/js/plugins/other/holder/holder.js"></script>

<!-- Uniform Forms Plugin -->
<script src="themes/default/lib/js/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

<!-- PrettyPhoto -->
<script src="themes/default/lib/js/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>

<!-- Global -->
<script>
    var basePath = '/themes/default/common/';
</script>

<!-- Bootstrap Extended -->
<script src="/themes/default/lib/extend/bootstrap-select/bootstrap-select.js"></script>
<script src="/themes/default/lib/extend/bootstrap-switch/static/js/bootstrap-switch.js"></script>
<script src="/themes/default/lib/extend/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"></script>
<script src="/themes/default/lib/extend/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script src="/themes/default/lib/extend/jasny-bootstrap/js/bootstrap-fileupload.js"></script>
<script src="/themes/default/lib/extend/bootbox.js"></script>
<script src="/themes/default/lib/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js"></script>
<script src="/themes/default/lib/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script>

<!-- Google Code Prettify -->
<script src="themes/default/lib/js/plugins/other/google-code-prettify/prettify.js"></script>

<!-- Gritter Notifications Plugin -->
<script src="themes/default/lib/js/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>

<!-- Notyfy Notifications Plugin -->
<script src="themes/default/lib/js/plugins/notifications/notyfy/jquery.notyfy.js"></script>

<!-- MiniColors Plugin -->
<script src="themes/default/lib/js/plugins/color/jquery-miniColors/jquery.miniColors.js"></script>

<!-- DateTimePicker Plugin -->
<script src="themes/default/lib/js/plugins/forms/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<!-- Cookie Plugin -->
<script src="themes/default/lib/js/plugins/system/jquery.cookie.js"></script>

<!-- Colors -->
<script>
    var primaryColor = '#e25f39',
        dangerColor = '#bd362f',
        successColor = '#609450',
        warningColor = '#ab7a4b',
        inverseColor = '#45484d';
</script>


<!-- Easy-pie Plugin -->
<script src="themes/default/lib/js/plugins/charts/easy-pie/jquery.easypiechart.js"></script>

<!-- Sparkline Charts Plugin -->
<script src="themes/default/lib/js/plugins/charts/sparkline/jquery.sparkline.min.js"></script>

<!-- Ba-Resize Plugin -->
<script src="themes/default/lib/js/plugins/other/jquery.ba-resize.js"></script>


<!-- Google JSAPI -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<!--  Flot Charts Plugin -->
<script src="themes/default/lib/js/plugins/charts/flot/jquery.flot.js"></script>
<script src="themes/default/lib/js/plugins/charts/flot/jquery.flot.pie.js"></script>
<script src="themes/default/lib/js/plugins/charts/flot/jquery.flot.tooltip.js"></script>
<script src="themes/default/lib/js/plugins/charts/flot/jquery.flot.selection.js"></script>
<script src="themes/default/lib/js/plugins/charts/flot/jquery.flot.resize.js"></script>
<script src="themes/default/lib/js/plugins/charts/flot/jquery.flot.orderBars.js"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjWiKq1o5qX_LEokFDPUkIin3ckXpmWY0&callback=initMap">
</script>

<script src="themes/default/lib/js/jquery.select2.js"></script>
<script src="themes/default/lib/js/jquery.select2.pt-br.js"></script>
</body>
</html>
