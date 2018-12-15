<?= "<?php"?>
    /* @var $content String Conteúdo da página. */
    $themeUrl = Yii::app()->theme->baseUrl;
    $homeUrl = Yii::app()->controller->module->baseUrl;
    $baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]><html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]><html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
<head>
    <meta charset="UTF-8"/>
    <title><?= "<?="?> CHtml::encode($this->pageTitle); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">

    <link href="<?= "<?="?>$baseScriptUrl?>/lib/semantic/dist/semantic.min.css" rel="stylesheet" type="text/css">
    <link href="<?= "<?="?>$baseScriptUrl?>/common/css/error.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<?= "<?="?> $content ?>
<script type="text/javascript">var $baseScriptUrl = "<?= "<?="?>$baseScriptUrl?>"</script>
<script src="<?= "<?="?>$baseScriptUrl?>/lib/jquery/jquery-1.11.3.min.js"></script>
<script src="<?= "<?="?>$baseScriptUrl?>/lib/semantic/dist/semantic.min.js"></script>
<script src="<?= "<?="?>$baseScriptUrl?>/common/js/layout.js"></script>
</body>
</html>