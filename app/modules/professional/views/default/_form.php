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
    [
        'id' => 'professional-form',
        'enableAjaxValidation' => false,
    ]
);
?>
	<div class="row hidden-print">
		<div class="column">
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
			<div class="t-tabs">
				<ul class="t-tabs__list">
					<li id="tab-professional-identify" class="active t-tabs__item">
						<a href="#professional-identify" data-toggle="tab" class="t-tabs__link">
							<span class="t-tabs__numeration">1</span>
							Dados de Identificação
						</a>
						<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
					</li>
					<?php if (!$modelProfessional->isNewRecord): ?>
						<li id="tab-professional-attendance" class="t-tabs__item">
							<a href="#professional-attendance" data-toggle="tab" class="t-tabs__link">
								<span class="t-tabs__numeration">2</span>
								Atendimentos
							</a>
							<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
						</li>
						<li id="tab-professional-allocation" class="t-tabs__item">
							<a href="#professional-allocation" data-toggle="tab" class="t-tabs__link">
								<span class="t-tabs__numeration">3</span>
								Lotação
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="widget-body">
				<div class="tab-content">
					<div class="tab-pane active" id="professional-identify">
						<div>
							<h3>Dados de Identificação</h3>
						</div>
						<div class="full">
							<div class="row">
								<div class="column is-half clearleft">
									<div class="hide" id="id_professional"><?php echo $modelProfessional->id_professional; ?></div>
									<div class="t-field-text">
										<?php echo $form->label($modelProfessional, 'name', ['class' => 't-field-text__label']); ?>
										<?php echo $form->textField($modelProfessional, 'name', ['class' => 't-field-text__input', 'maxlength' => 100]); ?>
										<?php echo $form->error($modelProfessional, 'name'); ?>
									</div>
									<div class="t-field-text">
										<?php echo $form->label($modelProfessional, 'cpf_professional', ['class' => 't-field-text__label']); ?>
										<?php echo $form->textField($modelProfessional, 'cpf_professional', ['class' => 't-field-text__input cpf-input', 'maxlength' => 100]); ?>
										<?php echo $form->error($modelProfessional, 'cpf_professional'); ?>
									</div>
								</div>
								<div class="column is-half">
									<div class="t-field-text">
										<?php echo $form->label($modelProfessional, 'speciality', ['class' => 't-field-text__label']); ?>
										<?php echo $form->textField($modelProfessional, 'speciality', ['class' => 't-field-text__input', 'size' => 50]); ?>
										<?php echo $form->error($modelProfessional, 'speciality'); ?>
									</div>
									<div class="t-field-checkbox" style="margin-top: 20px;">
										<?php echo $form->checkBox($modelProfessional, 'fundeb', ['value' => 1, 'uncheckValue' => 0]); ?>
										<?php echo $form->label($modelProfessional, 'fundeb', ['class' => 'control-label', 'style' => 'display: inline; margin-left: 5px;']); ?>
										<?php echo $form->error($modelProfessional, 'fundeb'); ?>
									</div>
								</div>
							</div>
						</div>

						<?php if (!$modelProfessional->isNewRecord): ?>
						<div class="row t-margin-large--top">
							<div class="column clearleft">
								<h3>Resumo</h3>
								<div class="t-stat-cards">
									<div class="t-stat-card t-stat-card--info">
										<div class="t-stat-card__value"><?php echo $totalAttendancesMonth; ?></div>
										<div class="t-stat-card__label">Atendimentos este mês</div>
									</div>
									<div class="t-stat-card t-stat-card--success">
										<div class="t-stat-card__value"><?php echo $totalAllocations; ?></div>
										<div class="t-stat-card__label">Lotações registradas</div>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>

					</div>
					<?php if (!$modelProfessional->isNewRecord): ?>
						<div class="tab-pane" id="professional-attendance">
							<div class="tag-inner">
								<div class="tag-buttons-container buttons" style="margin-bottom: 20px;">
									<a href="#" class="t-button-primary new-attendance-button" id="new-attendance-button">Adicionar Atendimento</a>
								</div>
								<div class="attendance-container">
									<div class="form-attendance" style="display: none; background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
										<div><h3>Atendimento</h3></div>
										<p style="font-size: 12px; color: #666; margin-bottom: 15px;">* Se a data não for escolhida, mas o local do atendimento for informado, a data registrada será a atual.</p>
										<div class="row">
											<div class="column is-one-quarter clearleft">
												<div class="t-field-text">
													<?php echo $form->label($modelAttendance, 'date', ['class' => 't-field-text__label']); ?>
													<?php
													$this->widget('zii.widgets.jui.CJuiDatePicker', [
														'model' => $modelAttendance,
														'attribute' => 'date',
														'options' => [
															'dateFormat' => 'dd/mm/yy',
															'changeYear' => true,
															'changeMonth' => true,
															'yearRange' => '2000:' . date('Y'),
															'showOn' => 'focus',
															'maxDate' => 0
														],
														'htmlOptions' => [
															'readonly' => 'readonly',
															'class' => 't-field-text__input',
															'style' => 'cursor: pointer;',
															'placeholder' => 'Escolha a data'
														],
													]);
													?>
													<?php echo CHtml::link('Limpar', '#', [
														'style' => 'font-size: 12px; margin-top: 4px; display: block;',
														'onclick' => '$("#' . CHtml::activeId($modelAttendance, 'date') . '").datepicker("setDate", null); return false;',
													]); ?>
													<?php echo $form->error($modelAttendance, 'date'); ?>
												</div>
											</div>
											<div class="column is-three-quarters">
												<div class="t-field-text">
													<?php echo $form->label($modelAttendance, 'local', ['class' => 't-field-text__label']); ?>
													<?php echo $form->textField($modelAttendance, 'local', ['class' => 't-field-text__input', 'placeholder' => 'Informe o local do atendimento']); ?>
													<?php echo $form->error($modelAttendance, 'local'); ?>
												</div>
											</div>
										</div>
									</div>
									<div id="attendances" class="widget widget-scroll margin-bottom-none table-responsive">
										<h3>Atendimentos</h3>
										<?php
										DataTableGridView::show($this, [
											'id' => 'professional-attendance-grid',
											'dataProvider' => $attendanceProvider,
											'columns' => [
												[
													'name' => 'date',
													'value' => 'date("d/m/Y", strtotime($data->date))',
													'headerHtmlOptions' => ['style' => 'min-width: 100px;'],
												],
												[
													'name' => 'local',
													'headerHtmlOptions' => ['style' => 'min-width: 200px;'],
												],
												[
													'header' => 'Ações',
													'type' => 'raw',
													'value' => 'CHtml::tag("button", [
														"type" => "button",
														"class" => "t-button-content",
														"value" => $data->id_attendance,
														"onclick" => "deleteAttendance(this)",
														"title" => "Excluir"
													], CHtml::image(Yii::app()->theme->baseUrl . "/img/deletar.svg", "Excluir", ["style" => "width: 16px;"]))',
													'headerHtmlOptions' => ['style' => 'text-align: center; width: 80px;'],
													'htmlOptions' => ['style' => 'text-align: center;'],
												],
											],
										]);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="professional-allocation">
							<?php echo $this->renderPartial('_allocation', [
								'modelProfessional' => $modelProfessional,
								'allocationProvider' => $allocationProvider,
								'allocationModel'    => $allocationModel,
								'schools'            => $schools,
							]); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>

<script type="text/javascript">
    // Aplica a máscara e validação do CPF
    $(document).ready(function(){
        $('.cpf-input').mask('999.999.999-99', {placeholder: '___.___.___-__'});
        $('.cpf-input').blur(function(){
            var cpf = $(this).val().replace(/[^0-9]+/g,'');

            if(cpf.length == 11){
                var v = [];
                var sum = 0;

                // Validar CPF
                for (var i = 1; i <= 9; i++) {
                    sum += parseInt(cpf.substring(i-1, i)) * (11 - i);
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
                    sum += parseInt(cpf.substring(i-1, i)) * (12 - i);
                }

                remainder = (sum * 10) % 11;

                if ((remainder === 10) || (remainder === 11)) {
                    remainder = 0;
                }

                if (remainder !== parseInt(cpf.substring(10, 11))) {
                    v.push(false);
                }

                if(v.length === 0){
                    // CPF válido
					$(".save-professional").removeAttr('disabled');
					$(this).css('border', '1px solid #ccc');
					$(this).css('color', 'black');
                    $(this).removeClass('error');
                    $(this).next('.error-message').remove();
                }else{
                    // CPF inválido
					$(".save-professional").attr('disabled', 'disabled');
					$(this).css('border', '1px solid red');
					$(this).css('color', 'red');
                    $(this).next('.error-message').remove();
                    $(this).after('<div class="error-message">CPF inválido</div>');
                }
            }else{
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
