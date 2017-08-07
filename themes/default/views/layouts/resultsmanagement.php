<?php
/* @var $content String Conteúdo da página. */

$themeUrl = Yii::app()->theme->baseUrl;
$homeUrl = Yii::app()->controller->module->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

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
    <link href="<?=$baseScriptUrl?>/lib/fonts/glyphicons/css/glyphicons_social.css" rel="stylesheet"/>
    <link href="<?=$baseScriptUrl?>/lib/fonts/glyphicons/css/glyphicons_filetypes.css" rel="stylesheet"/>
    <link href="<?=$baseScriptUrl?>/lib/fonts/glyphicons/css/glyphicons_regular.css" rel="stylesheet"/>

    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />

    <link href="<?=$baseScriptUrl?>/common/css/style.css" rel="stylesheet"/>
    <script type="text/javascript">var $baseScriptUrl = "<?=$baseScriptUrl?>";</script>
</head>

<body>

<div class="container fluid menu-left">
    <div class="navbar main hidden-print">
        <a href="<?="/"?>" class="appbrand pull-left">
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
                        <?php if(!Yii::app()->user->hardfoot){$this->widget('resultsmanagement.components.sideInfoWidget');}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script src="<?=$baseScriptUrl?>/common/js/resultsLayout.js"></script>

<script src="<?=$baseScriptUrl?>/lib/js/jquery.select2.pt-br.js"></script>
</body>
</html>
