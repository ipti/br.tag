<?php
/* @var $this TimesheetController
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
$cs->registerScriptFile($baseScriptUrl . '/common/js/timesheet.js', CClientScript::POS_END);
$cs->registerScript("vars",
	"var generateTimesheetURL = '" . $this->createUrl("generateTimesheet") . "'; ".
    "var changeSchedulesURL = '" . $this->createUrl("changeSchedules") . "'; " .
	"var getInstructorsUrl = '" . $this->createUrl("getInstructors") . "'; " .
	"var changeInstructorUrl = '" . $this->createUrl("changeInstructor") . "'; ", CClientScript::POS_HEAD);
$this->setPageTitle('TAG - ' . Yii::t('timesheetModule.timesheet', 'Timesheet'));
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
        <h3 class="heading-mosaic"><?= yii::t('timesheetModule.timesheet', 'Timesheet') ?></h3>
        <div class="buttons span9">
            <button data-toggle="modal" data-target="#add-instructors-disciplines-modal" class="btn btn-primary btn-icon glyphicons circle_plus">
                <i></i><?= yii::t('timesheetModule.instructors', "Add Disciplines") ?>
            </button>
            <a href="<?= yii::app()->createUrl("timesheet/timesheet/instructors") ?>"
               class="btn btn-primary btn-icon glyphicons nameplate">
                <i></i><?= yii::t('timesheetModule.timesheet', "Instructors") ?>
            </a>
        </div>
    </div>
</div>

<div class="innerLR home">
    <div class="row-fluid">
        <div class="span4">
            <?= CHtml::dropDownList('classroom_fk', "", CHtml::listData(Classroom::model()->findAllByAttributes(["school_inep_fk" => Yii::app()->user->school, "school_year" => Yii::app()->user->year]), 'id', 'name'), [
                "prompt" => yii::t("timesheetModule.timesheet", "Select a Classroom"),
                "class" => "select-search-on span12 classroom-id",
                "ajax" => [
                    "type" => "POST",
                    "url" => $this->createUrl("getTimesheet"),
                    "success" => "getTimesheet",
                    "data" => ["cid" => "js:this.value"],
                ]
            ]); ?>
        </div>
        <div class="span8 form-inline schedule-info display-hide">
            <div class="span6">
                <button class="btn btn-primary btn-icon glyphicons circle_plus btn-generate-timesheet">
                    <i></i><?= yii::t('timesheetModule.timesheet', "Generate timesheet") ?>
                </button>
            </div>
        </div>
    </div>
    <hr/>
    <div class="row-fluid table-container">
        <div class="span12">
            <span id="turn"></span>
            <table class="table-timesheet table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th class="schedule"><?= yii::t('timesheetModule.instructors', "Schedule"); ?></th>
                    <th week_day="1"><?= yii::t('timesheetModule.instructors', "Monday"); ?></th>
                    <th week_day="2"><?= yii::t('timesheetModule.instructors', "Tuesday"); ?></th>
                    <th week_day="3"><?= yii::t('timesheetModule.instructors', "Wednesday"); ?></th>
                    <th week_day="4"><?= yii::t('timesheetModule.instructors', "Thursday"); ?></th>
                    <th week_day="5"><?= yii::t('timesheetModule.instructors', "Friday"); ?></th>
                    <th week_day="6"><?= yii::t('timesheetModule.instructors', "Saturday"); ?></th>
                    <th week_day="0"><?= yii::t('timesheetModule.instructors', "Sunday"); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php for ($i = 0; $i < 10; $i++): ?>
                    <tr id="h<?= $i ?>">
                        <th><?= $i + 1 ?>º Horário</th>
                        <td week_day="1"></td>
                        <td week_day="2"></td>
                        <td week_day="3"></td>
                        <td week_day="4"></td>
                        <td week_day="5"></td>
                        <td week_day="6"></td>
                        <td week_day="0"></td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
    <br/>

    <div class="row-fluid table-container">
        <div class="span12 img-polaroid">

            <div class="row-fluid">
                <div class="span12">
                    <h4>Legenda</h4>
                </div>
            </div>
            <div class="span12">
                <i class="fa fa-exclamation-triangle darkgoldenrod"></i>
                <span>Instrutor possui n conflitos neste horário.</span>
            </div>
            <div class="span12">
                <i class="fa fa-times-circle darkred"></i>
                <span>Horário indisponível para o instrutor.</span>
            </div>
        </div>
    </div>
    <div class="alert alert-info display-hide">
        <span>A etapa desta turma não possui matriz curricular. Para criar uma, clique <a
                href="<?php echo yii::app()->createUrl('curricularmatrix') ?>">aqui</a>.</span>
    </div>
</div>

<!-- Modals -->

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
                            <?= CHtml::dropDownList("add-instructors-disciplines-ids", "", CHtml::listData(InstructorIdentification::model()->findAll(["order"=>"name"]), 'id', 'name'), [
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
                            <?= CHtml::link("+ " . Yii::t("timesheetModule.instructors", "new discipline/stage"), "#", [
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


<div class="modal fade" id="change-instructor-modal" tabindex="-1" role="dialog"
     aria-labelledby="<?= Yii::t("timesheetModule.timesheet", "Change Instructor") ?>">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
				        aria-label="<?= Yii::t("timesheetModule.timesheet", "Close") ?>">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"
				    id="myModalLabel"><?= Yii::t("timesheetModule.timesheet", "Change Instructor") ?></h4>
			</div>
			<div class="modal-body">
				<div class="row-fluid">
					<form id="change-instructor-form" method="POST">
						<div class=" span12">
							<input type="hidden" id="change-instructor-schedule"/>
							<?= CHtml::label(Yii::t("timesheetModule.timesheet", "Instructor"), "change-instructor-id", ['class' => 'control-label']); ?>
							<div class="span12">
								<?= CHtml::dropDownList("change-instructor-id", "",[], [
									"class" => "select-search-on span11"
								]) ?>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?= yii::t("timesheetModule.timesheet", "Cancel") ?>
				</button>
				<button type="button" class="btn btn-primary" id="change-instructor-button">
					<?= yii::t("timesheetModule.timesheet", "Change") ?>
				</button>
			</div>
		</div>
	</div>
</div>
