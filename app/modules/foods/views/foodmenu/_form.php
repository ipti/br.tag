<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/menuComponents.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v='.TAG_VERSION, CClientScript::POS_END);
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'food-menu-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation' => false,
	)
	); ?>

	<div class="main form-content">
        <div class="row js-form-active">
            <div class="column">
                <h1>
                    <?php echo $title; ?>
                </h1>
            </div>
        </div>

		<div class="row js-form-active">
			<div class="alert alert-error js-menu-error hide column"></div>
		</div>
		<div class="t-tabs row">
			<div class="column">
				<ul class="tab-instructor t-tabs__list ">
					<li class="active t-tabs__item"><a data-toggle="tab" class="t-tabs__link" style="padding-left:0;">
							<span class="t-tabs__numeration">1</span>
							<?= $model->isNewRecord ? 'Criar Cardápio' : 'Salvar Cardápio' ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<h3 class="column">
				Informações do Cardápio
			</h3>
		</div>
		<?php if(!$model->isNewRecord): ?>
			<div class="row t-margin-medium--bottom">
                <div class="column clearleft--on-mobile t-buttons-container">
                        <a class="t-button-secondary" target="_blank" rel="noopener" href="<?php echo Yii::app()->createUrl('foods/reports/FoodMenuReport', array('id'=>$model->id)) ?>">
                            <span class="t-icon-printer"></span>imprimir cardápio
                        </a>
                </div>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="t-field-text column">
				<label for="menu_description" class="t-field-text__label--required">Nome</label>
				<input type="text" id="menu_description" name="Nome" class="t-field-text__input js-menu-name"
					required="required">

			</div>
			<div class="clear-margin--top column">
				<label for="public_target" class="t-field-select__label--required">Público Alvo</label>
				<select id="public_target" name="Público Alvo"
					class="select-search-on t-field-select__input js-public-target js-initialize-select2"
					required="required" placeholder="Select Classrom">
					<option value="">Selecione o Público Alvo</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="t-field-text column">
				<label for="menu_start_date" class="t-field-text__label--required">Data Inicial</label>
				<input type="text" id="menu_start_date" name="Data Inicial"
					class="t-field-text__input js-date date js-start-date" required="required">
			</div>
			<div class="t-field-text column">
				<label for="menu_final_date" class="t-field-text__label--required">Data Final</label>
				<input type="text" id="menu_final_date" name="Data Final"
					class="t-field-text__input js-date date js-final-date" required="required" />
			</div>
		</div>
		<div class="row">
			<div class="column">
			<label for="week" class="t-field-select__label">Semana</label>
				<select id="week" name="Semana"
					class="select-search-on t-field-select__input js-week js-initialize-select2">
					<option value="">Selecione a semana</option>
					<option value="1">1° semana</option>
					<option value="2">2° semana</option>
					<option value="3">3° semana</option>
					<option value="4">4° semana </option>
				</select>
			</div>
			<div class="t-field-text column">
				<label for="menu_observation" class="t-field-select__label">Observação</label>
				<input type="text" id="menu_observation" name="Observação" class="t-field-select__input js-observation">
			</div>
		</div>
        <div class="row">
        <div class="t-margin-none--top column">
                <label for="stages" class="t-field-select__label--required">Etapas de ensino</label>
                <?php
                    echo CHtml::dropDownList(
                        'Etapas',
                        $stages,
                        CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'),
                        array(
                            'class' => 't-field-select__input js-stage-select select-search-on select2-container t-multiselect multiselect',
                            'multiple' => 'multiple',
                            'prompt' => 'Selecione o estágio...',
                            'style' => 'width: 100%;',
                            "required"=>"required"
                        )
                    );
                ?>
            </div>
            <div class="column">
                <div class="t-field-checkbox t-margin-none--top">
                    <input class="t-field-checkbox__input js-include-saturday" type="checkbox" id="include-saturday" name="Sabado Letivo" style="margin-right:5px;">
                    <label class="t-field-checkbox__label" for="include-saturday">Incluir Sábado Letivo</label>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="column clearleft--on-mobile t-buttons-container">
				<a class="t-button-primary js-add-meal">
					<span class="t-icon-start"></span>
					Adicionar Refeição
				</a>
			</div>
		</div>
		<div class="t-tabs-secondary js-days-of-week-component row show t-margin-large--top">

		</div>
		<div class="row">
			<div class="column t-accordeon--header">
				<div class="mobile-row">
					<div class="column">
						Refeição
					</div>
					<div class="column">
						Nome
					</div>
				</div>
			</div>
		</div>
		<div class="row js-menu-meals-container">
			<div class="column">
				<div id="js-accordion" class="js-meals-component t-accordeon-primary"></div>
			</div>
		</div>
	</div>
	<div class="row buttons" style="width:165px;">
		<a class="t-button-primary js-save-menu show--desktop">
			<?= $model->isNewRecord ? 'Criar Cardápio' : 'Salvar Cardápio' ?>
		</a>
	</div>
	<div class="row t-buttons-container t-margin-large--top">
		<a class="t-button-primary column  t-margin-large--left t-margin-large--right js-save-menu show--tablet">
			<?= $model->isNewRecord ? 'Criar Cardápio' : 'Salvar Cardápio' ?>
		</a>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->
<style>
	.date[readonly] {
		cursor: pointer;
		background-color: white;
	}

  .alert-error b {
        font-weight: bold;
  }
  .t-field-select__input.measurement-unit  {
      width: 60px !important;
      margin: 0px;
  }
</style>
