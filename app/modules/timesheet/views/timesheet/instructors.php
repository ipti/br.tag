<?php
	/* @var $this TimesheetController
	 * @var $cs CClientScript
	 */

	$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

	$cs = Yii::app()->getClientScript();
	$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
	$cs->registerScriptFile($baseScriptUrl . '/common/js/instructors.js', CClientScript::POS_END);
	$cs->registerScript("vars", "var getInstructorsDiscipinesURL = '" . $this->createUrl('timesheet/getInstructorDisciplines') . "';", CClientScript::POS_HEAD);

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
		<h3 class="heading-mosaic"><?= yii::t('timesheetModule.instructors', 'Instructors') ?></h3>

		<div class="buttons span9">
			<button data-toggle="modal" data-target="#add-instructors-modal"
			        class="btn btn-primary btn-icon glyphicons circle_plus">
				<i></i><?= yii::t('timesheetModule.instructors', "Add Instructors") ?>
			</button>
			<a href="<?= yii::app()->createUrl("timesheet/timesheet/index") ?>"
			   class="btn btn-primary btn-icon glyphicons calendar">
				<i></i><?= yii::t('timesheetModule.instructors', "Timesheets") ?>
			</a>
		</div>
	</div>
</div>

<div class="innerLR home">
	<?= CHtml::dropDownList('instructor_fk', "", CHtml::listData(InstructorSchool::model()->findAllByAttributes(["school_fk" => Yii::app()->user->school]), 'id', 'instructorFk.name'), [
		"prompt" => yii::t("timesheetModule.instructors", "Select a Instructor"), "class" => "select-search-on",
	]); ?>

	<?= CHtml::dropDownList('disciplines_fk', "", CHtml::listData(EdcensoDiscipline::model()->findAll(['order' => 'name']), 'id', 'name'), [
		"prompt" => yii::t("timesheetModule.instructors", "Select a Discipline"), "class" => "select-search-on",
	]); ?>

	<?= CHtml::dropDownList('stage_vs_modality_fk', "", CHtml::listData(EdcensoStageVsModality::model()->findAll(['order' => 'name']), 'id', 'name'), [
		"prompt" => yii::t("timesheetModule.instructors", "Select a Stage"), "class" => "select-search-on",
	]); ?>
</div>


<!-- Modals -->

<div class="modal fade" id="add-instructors-modal" tabindex="-1" role="dialog"
     aria-labelledby="<?= Yii::t("timesheetModule.instructors", "Add Instructors") ?>">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
				        aria-label="<?= Yii::t("timesheetModule.instructors", "Close") ?>">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"
				    id="myModalLabel"><?= Yii::t("timesheetModule.instructors", "Add Instructors") ?></h4>
			</div>
			<div class="modal-body">
				<div class="row-fluid">
					<form id="add-instructors-form" method="POST" action="<?= $this->createUrl('timesheet/addInstructors') ?>">
						<div class=" span12">
							<?= CHtml::label(Yii::t("timesheetModule.instructors", "Instructors"), "add-instructors-ids", ['class' => 'control-label']); ?>
							<div class="span12">
								<?= CHtml::dropDownList("add-instructors-ids", "", CHtml::listData(InstructorIdentification::model()->findAll(['order' => 'name']), 'id', 'name'), [
									"class" => "select-search-on span11", "multiple" => "multiple"
								]) ?>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?= yii::t("timesheetModule.instructors", "Cancel") ?>
				</button>
				<button type="button" class="btn btn-primary" id="add-instructors-button">
					<?= yii::t("timesheetModule.instructors", "Add") ?>
				</button>
			</div>
		</div>
	</div>
</div>