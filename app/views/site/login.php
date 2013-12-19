<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
$baseUrl = Yii::app()->theme->baseUrl; 
  $cs = Yii::app()->getClientScript();
  $cs->registerCssFile($baseUrl.'/css/bootstrap.min.css');
  $cs->registerCssFile($baseUrl.'/css/responsive.min.css');
  $cs->registerCssFile($baseUrl.'/css/template.min.css');
  
  $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
));
?>

<body class="login">
        

<!-- Wrapper -->
<div id="login">

	<!-- Box -->
	<div class="form-signin">
		<h3>Login</h3>
		
		<!-- Row -->
		<div class="row-fluid row-merge">
		
			<!-- Column -->
			<div class="span12">
				<div class="inner">
				
					<!-- Form -->
					<form method="post" action="index.html?lang=en&amp;layout_type=fluid&amp;menu_position=menu-left&amp;style=style-light">
						<label class="strong">Login</label>
						<?php echo $form->textField($model,'username', array('class' => 'input-block-level')); ?>
                                                <?php echo $form->error($model,'username'); ?>
						<label class="strong">Senha<a class="password" href="">esqueceu sua senha?</a></label>
						<?php echo $form->passwordField($model,'password', array('class' => 'input-block-level')); ?>
                                                <?php echo $form->error($model,'password'); ?>
						<div class="uniformjs"><label class="checkbox"><input type="checkbox" value="remember-me">Lembrar-me</label></div>
						<div class="row-fluid">
							<div class="span12 center">
								<?php echo CHtml::submitButton('Login', array('class' => 'btn btn-block btn-primary')); ?>
							</div>
						</div>
					</form>
					<!-- // Form END -->
					
				</div>
			</div>
			<!-- // Column END -->
			
			<!-- Column -->
			
			<!-- // Column END -->
			
		</div>
		<!-- // Row END -->
	</div>
	<!-- // Box END -->
	
</div>    

</body>
</body>
<?php $this->endWidget(); ?>
