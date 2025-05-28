<?php
$baseUrl = Yii::app()->theme->baseUrl;
$themeUrl = Yii::app()->theme;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/bootstrap.min.css');
$cs->registerCssFile($baseUrl . '/css/responsive.min.css');
$cs->registerCssFile($baseUrl . '/css/template.css?v=1.0');
$cs->registerCssFile($baseUrl . '/css/template2.css');
$cs->registerCssFile($baseUrl . "/css/select2.css");
$cs->registerCssFile(Yii::app()->baseUrl . "/sass/css/main.css?v=" . TAG_VERSION);
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt" xml:lang="pt"><!-- <![endif]-->

<head>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />


    <!-- Hotjar Tracking Code for https://demo.tag.ong.br -->
    <script>
        (function(h, o, t, j, a, r) {
            h.hj = h.hj || function() {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {
                hjid: 3615212,
                hjsv: 6
            };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>
      <!-- // Main Container Fluid END -->
      <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.mask.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-datepicker.pt-BR.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common.js?v=<?= TAG_VERSION ?>"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/util.js?v=<?= TAG_VERSION ?>"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2-locale-pt-BR.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.qrcode.min.js"
        type="text/javascript"></script>
    <script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/purify.min.js'></script>



</head>

<body>
    <div class="colorful-bar">
        <span id="span-color-blue"></span>
        <span id="span-color-red"></span>
        <span id="span-color-green"></span>
        <span id="span-color-yellow"></span>
    </div>
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag-title.png" style="padding: 20px 20px;position: absolute;top: 0;left: 0;" alt="Logo do TAG" />
    <?php echo $content; ?>
</body>

</html>
