<?php
/* @var $content String Conteúdo da página. */

$themeUrl = Yii::app()->theme->baseUrl;
$homeUrl = Yii::app()->homeUrl;
$year = Yii::app()->user->year;

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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link href="/themes/default/lib/fonts/glyphicons/css/glyphicons_social.css" rel="stylesheet"/>
    <link href="/themes/default/lib/fonts/glyphicons/css/glyphicons_filetypes.css" rel="stylesheet"/>
    <link href="/themes/default/lib/fonts/glyphicons/css/glyphicons_regular.css" rel="stylesheet"/>

    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />

    <link href="/themes/default/common/css/style.css" rel="stylesheet"/>

</head>

<body>

<div class="container fluid menu-left">
    <div class="navbar main hidden-print">
        <a href="/" class="appbrand pull-left">
            <img src="/themes/default/img/tag_logo.png"/>
<!--            <span><span>Ano atual: --><?//=$year?><!--</span></span>-->
        </a>
    </div>

    <div id="wrapper">
        <div id="content">
            <div class="header-block">
                <h2><?= yii::t('resultsmanagementModule.layout', "Results Management") ?>
                    <div class="separator bottom"></div>
                    <div class="border-header header-description">
                        <?= $this->headerDescription ?>
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
                        <?php $this->widget('resultsmanagement.components.sideInfoWidget')?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<<<<<<< HEAD
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
=======
<script src="/themes/default/lib/js/plugins/system/jquery.min.js"></script>
<script src="/themes/default/lib/js/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/themes/default/lib/js/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<script src="/themes/default/lib/js/plugins/system/modernizr.js"></script>
<script src="/themes/default/lib/js/bootstrap.min.js"></script>
<script src="/themes/default/lib/js/plugins/other/jquery-slimScroll/jquery.slimscroll.min.js"></script>
<script src="/themes/default/lib/js/plugins/other/holder/holder.js"></script>
<script src="/themes/default/lib/js/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>
<script src="/themes/default/lib/js/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>

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
<script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/data.json"></script>
<script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>
>>>>>>> f16149821d9c55eac2a2ed1dc1e7ccd6d2305007

<script src="/themes/default/lib/js/jquery.select2.pt-br.js"></script>
</body>
</html>
