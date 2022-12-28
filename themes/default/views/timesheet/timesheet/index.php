<?php
/* @var $this TimesheetController
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$themeUrl = Yii::app()->theme->baseUrl;
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.1');
$cs->registerScriptFile($baseScriptUrl . '/common/js/timesheet.js?v=1.4', CClientScript::POS_END);
$cs->registerScript("vars",
    "var getTimesheetURL = '" . $this->createUrl("getTimesheet") . "'; " .
    "var removeScheduleURL = '" . $this->createUrl("removeSchedule") . "'; " .
    "var addScheduleURL = '" . $this->createUrl("addSchedule") . "'; " .
    "var generateTimesheetURL = '" . $this->createUrl("generateTimesheet") . "'; " .
    "var changeSchedulesURL = '" . $this->createUrl("changeSchedules") . "'; " .
    "var getInstructorsUrl = '" . $this->createUrl("getInstructors") . "'; " .
    "var changeInstructorUrl = '" . $this->createUrl("changeInstructor") . "'; ", CClientScript::POS_HEAD);
    $cs->registerCssFile($themeUrl . '/css/template2.css');
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
        </div>
    </div>
</div>

<div class="innerLR home">
    <div class="filter-bar margin-bottom-none">
        <div>
            <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 'control-label required')); ?>
            <?= CHtml::dropDownList('classroom_fk', "", CHtml::listData(Classroom::model()->findAll("school_inep_fk = :school_inep_fk and school_year = :school_year order by name", ["school_inep_fk" => Yii::app()->user->school, "school_year" => Yii::app()->user->year]), 'id', 'name'), ["prompt" => yii::t("timesheetModule.timesheet", "Select a Classroom"), "class" => "select-search-on classroom-id"]); ?>
        </div>
        <div class="schedule-info display-hide">
            <button class="tag-button small-button wide-button  btn-generate-timesheet">
                <i></i><?= yii::t('timesheetModule.timesheet', "Generate automatic timesheet") ?>
            </button>
        </div>
        <i class="loading-timesheet fa fa-spin fa-spinner"></i>
    </div>
    <hr/>
    <div class="loading-alert alert alert-warning display-hide"></div>
    <div class="row-fluid table-container">
        <div class="span12">
            <span id="turn"></span>
            <div class="checkbox replicate-actions-container">
                <input type="checkbox" class="replicate-actions-checkbox replicate-actions" checked> Replicar alterações
                para todas as semanas
                subsequentes
            </div>
            <div class="workloads-container">
                <i class="fa fa-chevron-right workloads-activator"></i>
                <i class="fa fa-exclamation-triangle workloads-overflow"></i>
                <div class="workloads">
                    <div class="workloads-title">Carga Horária</div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="tables-timesheet">
                <?php $lastMonthWeek = 1; ?>
                <?php for ($month = 1; $month <= 12; $month++): ?>
                    <div class="table-responsive">
                        <table month="<?= $month ?>" days-count="<?= $daysPerMonth[$month]["daysCount"] ?>"
                               first-day-weekday="<?= $daysPerMonth[$month]["weekDayOfTheFirstDay"] ?>"
                               class="table-timesheet table-month table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="table-title"
                                    colspan="<?= $daysPerMonth[$month]["daysCount"] + 1 ?>"><?= yii::t('timesheetModule.index', $daysPerMonth[$month]["monthName"]) ?></th>
                            </tr>
                            <tr class="calendar-icons">
                                <th></th>
                                <?php for ($day = 1; $day <= $daysPerMonth[$month]["daysCount"]; $day++): ?>
                                    <th icon-month="<?= $month ?>" icon-day="<?= $day ?>"></th>
                                <?php endfor; ?>
                            </tr>
                            <tr class="dayname-row">
                                <th></th>
                                <?php $weekDayCount = $daysPerMonth[$month]["weekDayOfTheFirstDay"]; ?>
                                <?php for ($day = 1; $day <= $daysPerMonth[$month]["daysCount"]; $day++): ?>
                                    <th><?= $dayNameFirstLetter[$weekDayCount] ?></th>
                                    <?php $weekDayCount = $weekDayCount == 6 ? 0 : ++$weekDayCount; ?>
                                <?php endfor; ?>
                            </tr>
                            <tr class="day-row">
                                <th class="schedule"><?= yii::t('timesheetModule.instructors', "Schedule"); ?></th>
                                <?php for ($day = 1; $day <= $daysPerMonth[$month]["daysCount"]; $day++): ?>
                                    <th><?= $day ?></th>
                                <?php endfor; ?>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <br/>

    <div class="row-fluid table-container">
        <div class="span12 img-polaroid">

            <div class="row-fluid">
                <div class="span12">
                    <div class="calendar-subtitles-title">
                        <h4>Legenda</h4>
                    </div>
                </div>
            </div>
            <?php
            $html = '';
            foreach ($calendarTypes as $calendarType) {
                /**@var $type CalendarEventType */
                $html .= '<div class="span3 calendar-subtitles calendar-' . $calendarType->color . '">'
                    . '<i class="fa ' . $calendarType->icon . '"></i>&nbsp;'
                    . '<span>' . yii::t('timesheetModule.timesheet', $calendarType->name) . '</span>'
                    . '</div>';


            }
            echo $html; ?>
        </div>
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
                            <?= CHtml::dropDownList("add-instructors-disciplines-ids", "", CHtml::listData(InstructorIdentification::model()->findAll(["order" => "name"]), 'id', 'name'), [
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

            <div class="modal-footer modal-container-buttons">
                <button type="button" class="tag-button-light small-button" data-dismiss="modal">
                    <?= yii::t("timesheetModule.instructors", "Cancel") ?>
                </button>
                <button type="button" class="tag-button small-button" id="add-instructors-disciplines-button">
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
                                <?= CHtml::dropDownList("change-instructor-id", "", [], [
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

<div class="modal fade" id="generateAnotherTimesheet" tabindex="-1" role="dialog"
     aria-labelledby="Generate Another Timesheet">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"
                    id="myModalLabel"><?= yii::t("timesheetModule.index", "Generate Another Timesheet") ?></h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="row-fluid">
                        <b>Tem certeza</b> que deseja gerar outro quadro de horário? <b>Os dados atuais serão
                            perdidos!</b>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= yii::t("timesheetModule.index", "Cancel") ?></button>
                    <button type="button" class="btn btn-primary confirm-timesheet-generation"
                            data-dismiss="modal"><?= yii::t("timesheetModule.index", "Confirm") ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addSchedule" tabindex="-1" role="dialog"
     aria-labelledby="Add Schedule">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="cancel-add-schedule close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"
                    id="myModalLabel"><?= yii::t("timesheetModule.index", "Add Schedule") ?></h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="add-schedule-alert alert alert-error">Selecione uma disciplina.</div>
                    <input type="hidden" class="add-schedule-month">
                    <input type="hidden" class="add-schedule-day">
                    <input type="hidden" class="add-schedule-weekday">
                    <input type="hidden" class="add-schedule-schedule">
                    <div class="modal-add-schedule-discipline-container">
                        <select class="modal-add-schedule-discipline"></select>
                    </div>
                    <div class="checkbox modal-replicate-actions-container">
                        <input type="checkbox" class="replicate-actions-checkbox modal-replicate-actions" checked>
                        Replicar alterações para todas
                        as semanas
                        subsequentes
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-add-schedule btn btn-default"
                            data-dismiss="modal"><?= yii::t("timesheetModule.index", "Cancel") ?></button>
                    <button type="button"
                            class="btn btn-primary btn-add-schedule"><?= yii::t("timesheetModule.index", "Add") ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

