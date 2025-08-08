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
$cs->registerCssFile(Yii::app()->baseUrl . '/sass/css/main.css?v=' . TAG_VERSION);
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/site/login.js?v=' . TAG_VERSION, CClientScript::POS_END);
$form = $this->beginWidget('CActiveForm', [
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => [
        'validateOnSubmit' => true,
    ],
]);
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
            <div class="form-signin">
                <form method="post" action="index.html?lang=en&amp;layout_type=fluid&amp;menu_position=menu-left&amp;style=style-light">
                    <h4 class="strong login-title"><?php echo INSTANCE ?></h4>
                    <p>Entre com as suas credenciais</p>
                    <label>Usuário</label>
                    <?php echo $form->textField($model, 'username', ['class' => 'input-block-level', 'placeholder' => 'Digite o usuário']); ?>
                    <?php echo $form->error($model, 'username'); ?>
                    <label>Senha</label>
                    <span class="t-icon-eye" id="showPassword" style="position:absolute;right:6px;margin-top:10px;cursor:pointer;font-size:20px;"></span>
                    <?php echo $form->passwordField($model, 'password', ['class' => 'input-block-level', 'placeholder' => 'Digite sua senha']); ?>
                    <?php echo $form->error($model, 'password'); ?>
                    <label>Ano Letivo</label>
                    <?php
                    $rightbrowser = false;
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
    $browser = 'Microsoft Internet Explorer';
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
    $browser = 'Google Chrome';
    $rightbrowser = true;
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false) {
    $browser = 'Mozilla Firefox';
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false) {
    $browser = 'Opera';
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false) {
    $browser = 'Apple Safari';
} else {
    $browser = 'error'; //<-- Browser not found.
}

echo $form->dropDownList($model, 'year', $years, ['class' => 'input-block-level select-search-off', 'style' => 'height: 44px', 'options' => [
    date('Y') => ['selected' => true] // Define o ano atual como selecionado
], ]);
// @done S1 - Alinhar o checkbox com os inputs
?>
                    <div class="uniformjs">
                        <label class="checkbox text-input">
                            <?php echo $form->checkBox($model, 'rememberMe', ['style' => 'margin: 0px 6px 20px 0']); ?>
                            Mantenha-me conectado
                            <!-- <input type="checkbox" style="margin:  0px 6px 20px 0" value="remember-me">Mantenha-me conectado -->
                        </label>
                    </div>
                    <div class="row-fluid">
                        <div>
                            <?php echo CHtml::submitButton('Entrar', ['class' => 'submit-button-login']); ?>
                        </div>
                    </div>
                </form>
                <?php if (!$rightbrowser) { ?>
                    <div style="text-align:center;color:#D21C1C; margin-top:5px;">Este site é melhor visualizado no Google Chrome. Você está utilizando o <?php echo $browser; ?></div>
                <?php } ?>
            </div>
            <div class="login-footer">
                <div class="login-versao">
                    <span class="">TAG v.<?php echo TAG_VERSION ?>
                </div>
                <div class="login-desenvolvido">
                    <span>Uma tecnologia desenvolvida pelo</span>
                </div>
                <div class="login-link">
                    <a  id="link" rel="noopener" href="https://www.ipti.org.br/" target="_blank">Instituto de Pesquisas em Tecnologia e Inovação</a>
                    <!-- <img alt="logo ipti" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo_ipti.png" /> -->
                </div>
            </div>
        </div>
        <!-- <span class="iptilogo">TAG v.<?php echo TAG_VERSION ?> -->
        <!-- <br>Yii v.<?php echo YII_VERSION ?> -->
        <!-- <br>
            <span>Uma tecnologia desenvolvida pelo</span>
            <br> -->
    </div>
</body>
<?php $this->endWidget(); ?>
