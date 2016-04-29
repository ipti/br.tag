<?php
	/* @var $this TimesheetController
	 * @var $cs CClientScript
	 */

	$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

	$cs = Yii::app()->getClientScript();
	$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
	$cs->registerScriptFile($baseScriptUrl . '/common/js/curricularmatrix.js', CClientScript::POS_END);
	$cs->registerScript("vars", "var addMatrix = '" . $this->createUrl("addMatrix") . "';", CClientScript::POS_HEAD);
	$this->setPageTitle('TAG - ' . Yii::t('curricularMatrixModule.index', 'Curricular Matrix'));
?>
<?php if (Yii::app()->user->hasFlash('success')): ?>
	<div class="row-fluid">
		<div class="span12">
			<div class="alert alert-success">
				<?php echo Yii::app()->user->getFlash('success') ?>
			</div>
		</div>
	</div>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
	<div class="row-fluid">
		<div class="span12">
			<div class="alert alert-error">
				<?php echo Yii::app()->user->getFlash('error') ?>
			</div>
		</div>
	</div>
<?php endif ?>

<div class="row-fluid">
	<div class="span12">
		<h3 class="heading-mosaic"><?= yii::t('curricularMatrixModule.index', 'Curricular Matrix') ?></h3>
	</div>
</div>

<div class="innerLR home">
	<div class="row-fluid">
		<div class="span5">
			<?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Stage'), 'stages', ['class' => "control-label"]) ?>
			<div class="form-group ">
				<?= CHtml::dropDownList("stages", [], CHtml::listData(EdcensoStageVsModality::model()->findAll(), "id", "name"), [
					"multiple" => "multiple", "class" => "select-search-on span12"
				]) ?>
			</div>
		</div>
		<div class="span3">
			<?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Disciplines'), 'disciplines', ['class' => "control-label"]) ?>
			<div class="form-group ">
				<?= CHtml::dropDownList("disciplines", [], CHtml::listData(EdcensoDiscipline::model()->findAll(), "id", "name"), [
					"multiple" => "multiple", "class" => "select-search-on span12"
				]) ?>
			</div>
		</div>
		<div class="span1">
			<?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Workload'), 'workload', ['class' => "control-label"]) ?>
			<div class="form-group ">
				<?= CHtml::numberField("workload", "0", ["min" => "0", "max" => "9999", "class" => "span12"]) ?>
			</div>
		</div>
		<div class="span1">
			<?= CHtml::label(Yii::t('curricularMatrixModule.index', 'Credits'), 'credits', ['class' => "control-label"]) ?>
			<div class="form-group ">
				<?= CHtml::numberField("credits", "0", ["min" => "0", "max" => "99", "class" => "span12"]) ?>
			</div>
		</div>
		<div class="span2">
			<?= CHtml::label("&nbsp;", 'credits', ['class' => "control-label"]) ?>
			<div class="form-group ">
				<?= CHtml::button(Yii::t('curricularMatrixModule.index', 'Add'),["id"=>"add-matrix", "class" => "btn btn-primary"]) ?>
			</div>
		</div>
	</div>
	<hr>
</div>