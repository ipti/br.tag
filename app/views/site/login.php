<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
$baseUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/bootstrap.min.css');
$cs->registerCssFile($baseUrl . '/css/responsive.min.css');
$cs->registerCssFile($baseUrl . '/css/template.min.css');

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
                            <label class="strong">Usuário</label>
                            <?php echo $form->textField($model, 'username', array('class' => 'input-block-level')); ?>
                            <?php echo $form->error($model, 'username'); ?>
                            <label class="strong">Senha</label>
                            <?php echo $form->passwordField($model, 'password', array('class' => 'input-block-level')); ?>
                            <?php echo $form->error($model, 'password'); ?>

                            <label class="strong">Ano Letivo</label>
                            <?php
                            $year = date('Y');
                            $years = array();
                            for ($i = $year; $i >= 2013; $i--) {
                                $years[$i] = $i;
                            }
                            echo $form->dropDownList($model, 'year', $years, array('class' => 'input-block-level select-search-off'));
                            // @done S1 - Alinhar o checkbox com os inputs
                            ?>
                            <div class="uniformjs"><label class="checkbox" ><input type="checkbox" style="margin: 4px 4px 0 0"value="remember-me">Lembrar-me</label></div>
                            <div class="row-fluid">
                                <div class="offset7 span5 center">
                                    <?php echo CHtml::submitButton('Login', array('class' => 'btn btn-block btn-primary')); ?>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <span class="iptilogo">Uma tecnologia desenvolvida pelo</span>
        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo_ipti.png" style="padding: 20px 20px;height: 60px;position: absolute;bottom: 0;right: 0;" />
    </div>    
</body>
</body>
<?php $this->endWidget(); ?>
