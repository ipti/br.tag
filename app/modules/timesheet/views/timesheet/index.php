<?php
/* @var $this TimesheetController
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
$cs->registerScriptFile($baseScriptUrl . '/common/js/timesheet.js', CClientScript::POS_END);
$cs->registerScript("vars", "var generateTimesheetURL = '" . $this->createUrl("generateTimesheet") . "';", CClientScript::POS_HEAD);
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
    <div class="row-fluid">
        <div class="span12">
            <div class="span12"><span id="turn"></span></div>
            <table class="table-timesheet table table-bordered">
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
                <?php for ($i = 0; $i < 10; $i++): ?>
                    <tr id="h<?= $i ?>">
                        <th><?= $i+1 ?>º Horário</th>
                        <td week_day="1"></td>
                        <td week_day="2"></td>
                        <td week_day="3"></td>
                        <td week_day="4"></td>
                        <td week_day="5"></td>
                        <td week_day="6"></td>
                        <td week_day="0"></td>
                    </tr>
                <?php endfor; ?>
            </table>
        </div>
    </div>
</div>