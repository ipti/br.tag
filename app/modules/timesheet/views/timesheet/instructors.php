<?php
	/* @var $this TimesheetController
	 * @var $cs CClientScript
	 */

	$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

	$cs = Yii::app()->getClientScript();
	$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
	$cs->registerScriptFile($baseScriptUrl . '/common/js/instructors.js', CClientScript::POS_END);
	$cs->registerScript("vars", "
	var getInstructorsDisciplinesURL = '" . $this->createUrl('timesheet/getInstructorDisciplines') . "';
	var loadUnavailability = '" . $this->createUrl("loadUnavailability") . "';", CClientScript::POS_HEAD);

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
			<button data-toggle="modal" data-target="#add-instructors-unavailability-modal"
			        class="btn btn-primary btn-icon glyphicons circle_plus">
				<i></i><?= yii::t('timesheetModule.instructors', "Add Unavailability") ?>
			</button>
			<button data-toggle="modal" data-target="#add-instructors-disciplines-modal"
			        class="btn btn-primary btn-icon glyphicons circle_plus">
				<i></i><?= yii::t('timesheetModule.instructors', "Add Disciplines") ?>
			</button>
			<a href="<?= yii::app()->createUrl("timesheet/timesheet/index") ?>"
			   class="btn btn-primary btn-icon glyphicons calendar">
				<i></i><?= yii::t('timesheetModule.instructors', "Timesheets") ?>
			</a>
		</div>
	</div>
</div>

<div class="innerLR home">
	<div class="row-fluid">
		<div class="span4">
			<?= CHtml::dropDownList('instructor_fk', "", CHtml::listData(InstructorSchool::model()->findAllByAttributes(["school_fk" => Yii::app()->user->school]), 'id', 'instructorFk.name'), [
				"prompt" => yii::t("timesheetModule.instructors", "Select a Instructor"),
				"class" => "select-search-on span12",
			]); ?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<table class="table-unavailability table table-bordered">
				<tr>
					<th class="schedule"><?= yii::t('timesheetModule.instructors', "Schedule"); ?></th>
					<th week_day="0"><?= yii::t('timesheetModule.instructors', "Sunday"); ?></th>
					<th week_day="1"><?= yii::t('timesheetModule.instructors', "Monday"); ?></th>
					<th week_day="2"><?= yii::t('timesheetModule.instructors', "Tuesday"); ?></th>
					<th week_day="3"><?= yii::t('timesheetModule.instructors', "Wednesday"); ?></th>
					<th week_day="4"><?= yii::t('timesheetModule.instructors', "Thursday"); ?></th>
					<th week_day="5"><?= yii::t('timesheetModule.instructors', "Friday"); ?></th>
					<th week_day="6"><?= yii::t('timesheetModule.instructors', "Saturday"); ?></th>
				</tr>
				<?php for ($i = 7; $i < 24; $i++): ?>
					<tr id="h<?= $i ?>">
						<th><?= $i ?>:00 - <?= $i + 1 ?>:00</th>
						<td week_day="0"></td>
						<td week_day="1"></td>
						<td week_day="2"></td>
						<td week_day="3"></td>
						<td week_day="4"></td>
						<td week_day="5"></td>
						<td week_day="6"></td>
					</tr>
				<?php endfor; ?>
			</table>
		</div>
	</div>
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
					<form id="add-instructors-form" method="POST"
					      action="<?= $this->createUrl('timesheet/addInstructors') ?>">
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


<div class="modal fade" id="add-instructors-unavailability-modal" tabindex="-1" role="dialog"
     aria-labelledby="<?= Yii::t("timesheetModule.instructors", "Add Instructors Unavailability") ?>">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
				        aria-label="<?= Yii::t("timesheetModule.instructors", "Close") ?>">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					<?= Yii::t("timesheetModule.instructors", "Add Instructors Unavailability") ?>
				</h4>
			</div>
			<div class="modal-body">
				<form id="add-instructors-unavailability-form" method="POST"
				      action="<?= $this->createUrl('timesheet/addInstructorsUnavailability') ?>">
					<div class="row-fluid">
						<div class=" span12">
							<?= CHtml::label(Yii::t("timesheetModule.instructors", "Instructors"), "add-instructors-unavailability-ids", ['class' => 'control-label']); ?>
							<?= CHtml::dropDownList("add-instructors-unavailability-ids", "", CHtml::listData(InstructorSchool::model()->findAll(), 'id', 'name'), [
								"class" => "select-search-on span12", "multiple" => "multiple"
							]) ?>
						</div>
					</div>
					<br>

					<div class="row-fluid" id="add-instructors-unavailability-times">
						<div class=" span12">
							<?= CHtml::label(Yii::t("timesheetModule.instructors", "Unavailability"), "add-instructors-unavailability-times", ['class' => 'control-label']); ?>
						</div>

						<div class="row-fluid">
							<div class=" span4">
								<?= CHtml::label(Yii::t("timesheetModule.instructors", "Initial Hour"), "", ['class' => 'control-label']); ?>
							</div>
							<div class=" span4">
								<?= CHtml::label(Yii::t("timesheetModule.instructors", "Final Hour"), "", ['class' => 'control-label']); ?>
							</div>
							<div class=" span3">
								<?= CHtml::label(Yii::t("timesheetModule.instructors", "Week Day"), "", ['class' => 'control-label']); ?>
							</div>
						</div>
						<div class="row-fluid add-instructors-unavailability-times"
						     id="add-instructors-unavailability-times_0">
							<div class=" span4">
								<?= CHtml::timeField("add-instructors-unavailability-initial[0]", "", [
									"class" => "span12"
								]) ?>
							</div>
							<div class=" span4">
								<?= CHtml::timeField("add-instructors-unavailability-final[0]", "", [
									"class" => "span12"
								]) ?>
							</div>
							<div class=" span3">
								<?= CHtml::dropDownList("add-instructors-unavailability-week-day[0]", "", Schedule::weekDays(), [
									"class" => "select-search-off span12"
								]) ?>
							</div>
						</div>
						<div class=" span12">
							<?= CHtml::link("+ ".Yii::t("timesheetModule.instructors", "new unavailability"), "#", [
								"id" => "add-unavailability", 'class' => 'control-label'
							]); ?>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?= yii::t("timesheetModule.instructors", "Cancel") ?>
				</button>
				<button type="button" class="btn btn-primary" id="add-instructors-unavailability-button">
					<?= yii::t("timesheetModule.instructors", "Add") ?>
				</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="add-instructors-disciplines-modal" tabindex="-1" role="dialog"
     aria-labelledby="<?= Yii::t("timesheetModule.instructors", "Add Instructors Disciplines") ?>">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
				        aria-label="<?= Yii::t("timesheetModule.instructors", "Close") ?>">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					<?= Yii::t("timesheetModule.instructors", "Add Instructors Disciplines") ?>
				</h4>
			</div>
			<div class="modal-body">
				<form id="add-instructors-disciplines-form" method="POST"
				      action="<?= $this->createUrl('timesheet/addInstructorsDisciplines') ?>">
					<div class="row-fluid">
						<div class=" span12">
							<?= CHtml::label(Yii::t("timesheetModule.instructors", "Instructors"), "add-instructors-unavailability-ids", ['class' => 'control-label']); ?>
							<?= CHtml::dropDownList("add-instructors-disciplines-ids", "", CHtml::listData(InstructorSchool::model()->findAll(), 'id', 'name'), [
								"class" => "select-search-on span12", "multiple" => "multiple"
							]) ?>
						</div>
					</div>
					<br>

					<div class="row-fluid" id="add-instructors-disciplines">
						<div class="row-fluid">
							<div class=" span6">
								<?= CHtml::label(Yii::t("timesheetModule.instructors", "Stages"), "", ['class' => 'control-label']); ?>
							</div>
							<div class=" span5">
								<?= CHtml::label(Yii::t("timesheetModule.instructors", "Disciplines"), "", ['class' => 'control-label']); ?>
							</div>
						</div>
						<div class="row-fluid add-instructors-disciplines" id="add-instructors-disciplines_0">
							<div class=" span6">
								<?= CHtml::dropDownList("add-instructors-disciplines-stage[0]", "", CHtml::listData(EdcensoStageVsModality::getAll(), 'id', 'name'), [
									"class" => "select-search-on span12", "multiple" => "multiple"
								]) ?>
							</div>
							<div class=" span5">
								<?= CHtml::dropDownList("add-instructors-disciplines-discipline[0]", "", CHtml::listData(EdcensoDiscipline::model()->findAll(), 'id', 'name'), [
									"class" => "select-search-on span12", "multiple" => "multiple"
								]) ?>
							</div>
						</div>
						<div class=" span12">
							<?= CHtml::link(Yii::t("timesheetModule.instructors", "+ new discipline/stage"), "#", [
								"id" => "add-discipline", 'class' => 'control-label'
							]); ?>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?= yii::t("timesheetModule.instructors", "Cancel") ?>
				</button>
				<button type="button" class="btn btn-primary" id="add-instructors-disciplines-button">
					<?= yii::t("timesheetModule.instructors", "Add") ?>
				</button>
			</div>
		</div>
	</div>
</div>