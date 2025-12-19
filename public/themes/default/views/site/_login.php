<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$baseUrl = Yii::app()->theme->baseUrl;
$themeUrl = Yii::app()->theme;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/bootstrap.min.css');
$cs->registerCssFile($baseUrl . '/css/responsive.min.css');
$cs->registerCssFile($baseUrl . '/css/template.css?v=1.0');
$cs->registerCssFile($baseUrl . '/css/template2.css');
$cs->registerCssFile(Yii::app()->baseUrl . "/sass/css/main.css?v=" . TAG_VERSION);
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/site/login.js?v='.TAG_VERSION, CClientScript::POS_END);
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
));
?>

<body class="login" style="overflow-x: hidden;">
    <div class="colorful-bar">
        <span id="span-color-blue"></span>
        <span id="span-color-red"></span>
        <span id="span-color-green"></span>
        <span id="span-color-yellow"></span>
    </div>
    <!-- Wrapper -->
    <div id="login">
        <!-- <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag-title.png" style="padding: 20px 20px;position: absolute;top: 0;right: 0;" /> -->
        <img alt="logo negativa" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/fadedlogo.svg" class="fadedlogo" />
        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag-title.png" style="padding: 20px 20px;position: absolute;top: 0;left: 0;" />
        <div class="login-form">
                <h1>Caro usuário, o Tag se encontra em manutenção</h1>
                <h2 style="text-align:center">Tempo estimado de retorno 12:30h</h2>

        </div>

    </div>
</body>
<?php $this->endWidget(); ?>
