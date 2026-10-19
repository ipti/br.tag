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
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/site/login.js?v=' . TAG_VERSION, CClientScript::POS_END);

$buildCommit = defined('TAG_BUILD_COMMIT') ? TAG_BUILD_COMMIT : '';

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

    <?php
    /*  if ((bool)getenv('MAINTENANCE_MODE')):
         $this->renderPartial("_maintenance_mode");
     else: */
    ?>
    <div id="login">
        <img alt="logo negativa" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/fadedlogo.svg" class="fadedlogo" />
        <img alt="tag" src="<?= Yii::app()->theme->baseUrl; ?>/img/tag-title.png"
            style="padding: 20px 20px;position: absolute;top: 0;left: 0;" />
        <div class="login-form">
            <div class="form-signin">
                <?php if (Yii::app()->user->hasFlash('success')): ?>
                    <div class="alert alert-success">
                        <?= Yii::app()->user->getFlash('success') ?>
                    </div>
                <?php endif; ?>
                <form method="post"
                    action="index.html?lang=en&amp;layout_type=fluid&amp;menu_position=menu-left&amp;style=style-light">
                    <h4 class="strong login-title"><?php echo INSTANCE ?></h4>
                    <p>Entre com as suas credenciais</p>
                    <label for="LoginForm_username">Usuário</label>
                    <?php echo $form->textField($model, 'username', array('class' => 'input-block-level', 'placeholder' => 'Digite o usuário')); ?>
                    <?php echo $form->error($model, 'username'); ?>
                    <label for="LoginForm_password">Senha</label>
                    <span class="t-icon-eye" id="showPassword"
                        style="position:absolute;right:6px;margin-top:10px;cursor:pointer;font-size:20px;"></span>
                    <?php echo $form->passwordField($model, 'password', array('class' => 'input-block-level', 'placeholder' => 'Digite sua senha')); ?>
                    <?php echo $form->error($model, 'password'); ?>
                    <label for="LoginForm_year">Ano Letivo</label>
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

                    echo $form->dropDownList($model, 'year', $years, array(
                        'class' => 'input-block-level select-search-off',
                        'style' => 'height: 44px',
                        'options' => array(
                            date("Y") => array('selected' => true) // Define o ano atual como selecionado
                        ),
                    ));
                    // @done S1 - Alinhar o checkbox com os inputs
                    ?>
                    <div class="uniformjs">
                        <label class="checkbox text-input" for="LoginForm_rememberMe">
                            <?php echo $form->checkBox($model, 'rememberMe', array("style" => "margin: 0px 6px 20px 0")); ?>
                            Mantenha-me conectado
                        </label>
                    </div>
                    <div class="row-fluid">
                        <div>
                            <?php echo CHtml::submitButton('Entrar', array('class' => 'submit-button-login')); ?>
                        </div>
                    </div>
                </form>
                <?php if (!$rightbrowser) { ?>
                    <div style="text-align:center;color:#D21C1C; margin-top:5px;">Este site é melhor visualizado no Google
                        Chrome. Você está utilizando o <?php echo $browser; ?></div>
                <?php } ?>
            </div>
            <div class="login-footer">
                <div class="login-versao">
                    <span>TAG v.<?php echo CHtml::encode(TAG_VERSION); ?></span>
                    <?php if ($buildCommit !== ''): ?>
                        <div>Build <?php echo CHtml::encode($buildCommit); ?></div>
                    <?php endif; ?>
                </div>
                <div class="login-desenvolvido">
                    <span>Uma tecnologia desenvolvida pelo</span>
                </div>
                <div class="login-link">
                    <a id="link" rel="noopener" href="https://www.thehumanproject.org.br/" target="_blank">
                        The Human project
                    </a>
                </div>
            </div>
        </div>

    </div>



</body>
<?php $this->endWidget(); ?>
