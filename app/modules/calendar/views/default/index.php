<?php
/* @var $this calendar.defaultController
 * @var $modelCalendar Calendar
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
//$cs->registerScriptFile($baseScriptUrl . '/common/js/lunch.js', CClientScript::POS_END);

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
        <h3 class="heading-mosaic"><?= yii::t('calendarModule.index', 'Calendar') ?></h3>

        <div class="buttons">
            <button data-toggle="modal" data-target="#myNewCalendar"
                    class="btn btn-primary btn-icon glyphicons circle_plus">
                <i></i><?= yii::t('calendarModule.index', "New Calendar") ?></button>
        </div>
    </div>
</div>
<div class="innerLR home">
    <div class="row-fluid">
        <div class="span12">
            teste
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myNewCalendar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= yii::t("calendarModule.index", "New Calendar") ?></h4>
            </div>
            <?php
            /* @var $form CActiveForm */
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'createCalendar',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                'htmlOptions' => array(
                    'class' => 'form-vertical',
                ),
            ));
            ?>
            <div class="modal-body">
                <div class="row-fluid">

                    <div class=" span12">
                        <?= $form->label($modelCalendar, "school_year", array('class' => 'control-label')); ?>
                        <div class="span12">
                            <?= $form->textField($modelCalendar, "school_year", ['class' => 'span11']) ?>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class=" span6">
                        <?= $form->label($modelCalendar, "start_date", array('class' => 'control-label')); ?>
                        <div class="span12">
                            <?= $form->dateField($modelCalendar, "start_date") ?>
                        </div>
                    </div>
                    <div class=" span6">
                        <?= $form->label($modelCalendar, "end_date", array('class' => 'control-label')); ?>
                        <div class="span12">
                            <?= $form->dateField($modelCalendar, "end_date") ?>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class=" span6">
                        <div class="span12">
                            <?= $form->checkBox($modelCalendar, "actual") ?> <?= $form->label($modelCalendar, "actual"); ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= yii::t("calendarModule.index", "Cancel") ?></button>
                <?= CHtml::button(yii::t("calendarModule.index", "Save"), array('class' => 'btn btn-primary', 'submit' => array('/calendar/default/create'))); ?>
            </div>
            <?php
            $this->endWidget();
            ?>
        </div>
    </div>
</div>