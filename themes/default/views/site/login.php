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

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
));
?>

<body class="login">
<!-- Wrapper -->
<div id="login">
    <div class="taglogo">
        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_login.png" style="padding: 125px 50px;height: 100px;position: absolute;top: 0;" />
    </div>
    <div class="form-signin">
        <div class="row-fluid row-merge">
            <div class="offset5 span6 login-input">
                <div class="inner">
                    <form method="post" action="index.html?lang=en&amp;layout_type=fluid&amp;menu_position=menu-left&amp;style=style-light">
                        <h4 class="strong instance"><?php echo INSTANCE ?></h4>
                        <label class="strong">Usuário</label>
                        <?php echo $form->textField($model, 'username', array('class' => 'input-block-level')); ?>
                        <?php echo $form->error($model, 'username'); ?>
                        <label class="strong">Senha</label>
                        <?php echo $form->passwordField($model, 'password', array('class' => 'input-block-level')); ?>
                        <?php echo $form->error($model, 'password'); ?>

                        <label class="strong">Ano Letivo</label>
                        <?php
                        $rightbrowser = FALSE;
                        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE){
                            $browser = 'Microsoft Internet Explorer';
                        }elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) {
                            $browser = 'Google Chrome';
                            $rightbrowser = TRUE;
                        }elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) {
                            $browser = 'Mozilla Firefox';
                        }elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE) {
                            $browser = 'Opera';
                        }elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE) {
                            $browser = 'Apple Safari';
                        }else {
                            $browser = 'error'; //<-- Browser not found.
                        }
                        $year = date('Y');
                        //botar condição do mês, a partir de novembro, mostrar o próximo ano.
                        $years = array();
                        if(date('m')>=11){$year = $year+1;}
                        for ($i = $year; $i >= 2014; $i--) {
                            $years[$i] = $i;
                        }
                        echo $form->dropDownList($model, 'year', $years, array('class' => 'input-block-level select-search-off'));
                        // @done S1 - Alinhar o checkbox com os inputs
                        ?>
                        <div class="uniformjs"><label class="checkbox" ><input type="checkbox" style="margin: 4px 6px 0 0"value="remember-me">Lembrar-me</label></div>
                        <div class="row-fluid">
                            <div class="offset7 span5 center">
                                <?php echo CHtml::submitButton('Login', array('class' => 'btn btn-block btn-primary')); ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php if(!$rightbrowser){?>
            <div style="text-align:right;color:red">Este site é melhor visualizado no Google Chrome. Você está utilizando o <?php echo $browser; ?></div>
        <?php }?>
    </div>
    <span class="iptilogo">TAG v.<?php echo TAG_VERSION ?><br>Yii v.<?php echo YII_VERSION ?><br>
        Uma tecnologia desenvolvida pelo</span>
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo_ipti.png" style="padding: 20px 20px;height: 60px;position: absolute;bottom: 0;right: 0;" />

</div>
</body>
<?php $this->endWidget(); ?>
