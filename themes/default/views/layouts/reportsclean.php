<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta charset="UTF-8" />
        <?php
        $themeUrl = Yii::app()->theme->baseUrl;
        $baseUrl  = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($themeUrl . '/css/bootstrap.min.css');
        $cs->registerCssFile($themeUrl . '/css/template.css?v=' . TAG_VERSION);
        $cs->registerCssFile($themeUrl . '/css/print.css', 'print');
        $cs->registerCssFile($themeUrl . '/css/font-awesome.min.css');
        $cs->registerCssFile($themeUrl . '/css/glyphicons.min.css');
        $cs->registerCssFile($themeUrl . '/css/select2.css');
        $cs->registerCssFile($themeUrl . '/css/jquery-ui-1.9.2.custom.min.css');
        $cs->registerCssFile($baseUrl  . '/css/form.css?v=' . TAG_VERSION);
        $cs->registerCssFile($baseUrl  . '/sass/css/main.css?v=' . TAG_VERSION);
        ?>
    </head>
    <body>
            <?php echo $content; ?>
    </body>
</html>
