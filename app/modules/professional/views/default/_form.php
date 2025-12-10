<?php
/* @var $this ProfessionalController */
/* @var $modelProfessional Professional */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php
	$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
	$themeUrl = Yii::app()->theme->baseUrl;
	$cs = Yii::app()->getClientScript();

	$cs->registerScriptFile($baseScriptUrl . '/common/js/professional.js?v='.TAG_VERSION, CClientScript::POS_END);

	$form = $this->beginWidget(
		'CActiveForm',
		array(
			'id' => 'professional-form',
			'enableAjaxValidation' => false,
		)
	);
	?>
	<div class="row-fluid hidden-print">
		<div class="span12">
			<h1>
				<?php echo $title; ?>
			</h1>
			<div class="tag-buttons-container buttons">
				<button class="t-button-primary pull-right save-professional" type="submit">
					<?= $modelProfessional->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
				</button>
			</div>
		</div>
	</div>

	<div class="tag-inner">
		<?php if (Yii::app()->user->hasFlash('success') && (!$modelProfessional->isNewRecord)): ?>
			<div class="alert alert-success">
				<?php echo Yii::app()->user->getFlash('success') ?>
			</div>
		<?php endif ?>
		<div class="widget widget-tabs border-bottom-none">
			<?php echo $form->errorSummary($modelProfessional); ?>
			<div class="alert alert-error professional-error no-show"></div>
			<div class="widget-body">
				<div class="t-tabs js-tab-control">
					<ul class="tab-professional t-tabs__list">
						<li id="tab-professional-identify" class="t-tabs__item active">
							<a class="t-tabs__link first" href="#professional-identify" data-toggle="tab">
								<span class="t-tabs__numeration">1</span>
								Dados Pessoais
							</a>
							<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
						</li>
						<li id="tab-professional-enrollment" class="t-tabs__item">
							<a class="t-tabs__link" href="#professional-enrollment" data-toggle="tab">
								<span class="t-tabs__numeration">2</span>
								Lotação
							</a>
							<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
						</li>
						<li id="tab-professional-attendance" class="t-tabs__item">
							<a class="t-tabs__link" href="#professional-attendance" data-toggle="tab">
								<span class="t-tabs__numeration">3</span>
								Atendimento
							</a>
						</li>
					</ul>
				</div>
				<div class="tab-content">
					<?php $this->renderPartial('form_tabs/_professional_identification', ['form' => $form, 'modelProfessional' => $modelProfessional, 'specialities' => $specialities]); ?>
					<?php $this->renderPartial('form_tabs/_professional_enrollment', ['form' => $form, 'modelProfessional' => $modelProfessional, 'specialities' => $specialities]); ?>
					<?php $this->renderPartial('form_tabs/_professional_attendance', ['form' => $form, 'modelAttendances' => $modelAttendances, 'modelAttendance' => $modelAttendance]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
</div>

<script type="text/javascript">
	// Aplica a máscara e validação do CPF
	$(document).ready(function() {
		$('.cpf-input').mask('999.999.999-99', {
			placeholder: '___.___.___-__'
		});
		$('.cpf-input').blur(function() {
			var cpf = $(this).val().replace(/[^0-9]+/g, '');

			if (cpf.length == 11) {
				var v = [];
				var sum = 0;

				// Validar CPF
				for (var i = 1; i <= 9; i++) {
					sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
				}

				var remainder = (sum * 10) % 11;

				if ((remainder === 10) || (remainder === 11)) {
					remainder = 0;
				}

				if (remainder !== parseInt(cpf.substring(9, 10))) {
					v.push(false);
				}

				sum = 0;

				for (var i = 1; i <= 10; i++) {
					sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
				}

				remainder = (sum * 10) % 11;

				if ((remainder === 10) || (remainder === 11)) {
					remainder = 0;
				}

				if (remainder !== parseInt(cpf.substring(10, 11))) {
					v.push(false);
				}

				if (v.length === 0) {
					// CPF válido
					$(".save-professional").removeAttr('disabled');
					$(this).css('border', '1px solid #ccc');
					$(this).css('color', 'black');
					$(this).removeClass('error');
					$(this).next('.error-message').remove();
				} else {
					// CPF inválido
					$(".save-professional").attr('disabled', 'disabled');
					$(this).css('border', '1px solid red');
					$(this).css('color', 'red');
					$(this).next('.error-message').remove();
					$(this).after('<div class="error-message">CPF inválido</div>');
				}
			} else {
				// CPF inválido
				$(".save-professional").attr('disabled', 'disabled');
				$(this).css('border', '1px solid red');
				$(this).css('color', 'red');
				$(this).next('.error-message').remove();
				$(this).after('<div class="error-message">CPF inválido</div>');
			}
		});
	});
</script>

<style>
	.error-message {
		color: red;
		font-size: 12px;
		margin-top: 5px;
	}
</style>