<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
<?php
$baseUrl = Yii::app()->theme->baseUrl.'/common/';
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . 'bootstrap/css/bootstrap3.min.css');
$cs->registerCssFile($baseUrl . 'theme/css/style-light.css');
$cs->registerCssFile($baseUrl . 'theme/skins/css/blue-gray.css');
$cs->registerCssFile($baseUrl . 'theme/fonts/glyphicons/css/glyphicons_social.css');
$cs->registerCssFile($baseUrl . 'theme/fonts/glyphicons/css/glyphicons_regular.css');
$cs->registerScriptFile($baseUrl . 'theme/scripts/plugins/system/jquery.min.js');
$cs->registerScriptFile($baseUrl . 'theme/scripts/plugins/system/less.min.js');
$cs->registerScriptFile($baseUrl . 'bootstrap/js/bootstrap3.min.js');


?>
    <head>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <body class="dashboard">
        <?php echo $content; ?>
    </body>
</html>
