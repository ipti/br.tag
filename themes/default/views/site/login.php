<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$baseUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/bootstrap.min.css');
$cs->registerCssFile($baseUrl . '/css/responsive.min.css');
$cs->registerCssFile($baseUrl . '/css/template.css?v=1.0');
$cs->registerCssFile($themeUrl . '/css/template2.css');
$form = $this->beginWidget('CActiveForm', [
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => [
        'validateOnSubmit' => true,
    ],
]);
?>

<body class="login">
    <div class="colorful-bar">
        <span id="span-color-blue"></span>
        <span id="span-color-red"></span>
        <span id="span-color-green"></span>
        <span id="span-color-yellow"></span>
    </div>
<!-- Wrapper -->
<div id="login">
    <img src="<?php  echo Yii::app()->theme->baseUrl; ?>/img/tag-title.png" style="padding: 20px 20px;position: absolute;top: 0;left: 0;" />
    <div class="form-signin">
                    <form method="post" action="index.html?lang=en&amp;layout_type=fluid&amp;menu_position=menu-left&amp;style=style-light">
                        <h4 class="strong login-title"><?php echo INSTANCE ?></h4>
                        <p>Entre com as suas credenciais</p>
                        <label>Usuário</label>
                        <?php echo $form->textField($model, 'username', ['class' => 'input-block-level', 'placeholder' => 'Digite o usuário']); ?>
                        <?php echo $form->error($model, 'username'); ?>
                        <label >Senha</label>
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
                        $year = date('Y');
                        //botar condição do mês, a partir de novembro, mostrar o próximo ano.
                        $years = [];
                        if (date('m') >= 11) {
                            $year = $year + 1;
                        }
                        for ($i = $year; $i >= 2014; $i--) {
                            $years[$i] = $i;
                        }
                        echo $form->dropDownList($model, 'year', $years, ['class' => 'input-block-level select-search-off']);
                        // @done S1 - Alinhar o checkbox com os inputs
                        ?>
                        <div class="uniformjs"><label class="checkbox text-input" ><input type="checkbox" style="margin: 0px 6px 20px 0"value="remember-me">Lembrar-me</label></div>
                        <div class="row-fluid">
                            <div>
                                <?php echo CHtml::submitButton('Entrar', ['class' => 'submit-button-login']); ?>
                            </div>
                        </div>
                    </form>
        <?php if (!$rightbrowser) {?>
            <div style="text-align:center;color:#D21C1C; margin-top:5px;">Este site é melhor visualizado no Google Chrome. Você está utilizando o <?php echo $browser; ?></div>
        <?php }?>
    </div>
    <span class="iptilogo">TAG v.<?php echo TAG_VERSION ?><br>Yii v.<?php echo YII_VERSION ?><br>
        Uma tecnologia desenvolvida pelo</span>
    <img src="<?php  echo Yii::app()->theme->baseUrl; ?>/img/logo_ipti.png" style="padding: 20px 20px;height: 60px;position: absolute;bottom: 0;right: 0;" />

</div>
</body>
<?php $this->endWidget(); ?>
