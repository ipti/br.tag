<?php

/**
 * @var TimesheetController $this TimesheetController
 * @var CClient $cs CClientScript
 */

    $baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseScriptUrl . '/common/js/substituteInstructors.js', CClientScript::POS_END);


    $this->setPageTitle('TAG - ' . Yii::t('timesheetModule.timesheet', 'Assign Substitute Instructor'));

    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
?>

<div class="main">

    <?php if (Yii::app()->user->hasFlash('success')) : ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
            </div>
        </div>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('error')) : ?>
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
            <h1><?= yii::t('timesheetModule.timesheet', 'Assign Substitute Instructor') ?></h1>
        </div>
    </div>

    <div class="tag-inner">
        <div class="row margin-bottom-none">
                <div class="t-field-select">
                    <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 'control-label required small-label')); ?>
                    <?=
                        CHtml::dropDownList(
                            'classroom_fk', "",
                            CHtml::listData(Classroom::model()->findAll(
                                "school_inep_fk = :school_inep_fk and school_year = :school_year order by name",
                                ["school_inep_fk" => Yii::app()->user->school, "school_year" => Yii::app()->user->year]), 'id', 'name'),
                            [
                                "prompt" => yii::t("timesheetModule.timesheet", "Select a Classroom"),
                                "class" => "select-search-on t-field-select__input classroom-id",
                                "id" => "classrooms"
                            ]);
                    ?>
                </div>

                <div class="t-field-select">
                    <?php echo CHtml::label(yii::t('default', 'Instructor'), 'instructor', array('class' => 't-field-select__label--required')); ?>
                    <select class="select-search-on t-field-select__input frequency-input" id="instructor">
                        <option>Selecione o professor</option>
                        <?php foreach ($instructors as $instructor) : ?>
                            <option value="<?= $instructor["id"] ?>"> <?= $instructor["name"] ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="t-field-select">
                    <?php echo CHtml::label(yii::t('default', 'Month') . "/Ano",
                        'month', array('class' => 't-field-select__label--required')); ?>
                    <?=
                        CHtml::dropDownList('month', '', array(
                            1 => 'Janeiro',
                            2 => 'Fevereiro',
                            3 => 'Março',
                            4 => 'Abril',
                            5 => 'Maio',
                            6 => 'Junho',
                            7 => 'Julho',
                            8 => 'Agosto',
                            9 => 'Setembro',
                            10 => 'Outubro',
                            11 => 'Novembro',
                            12 => 'Dezembro'
                        ), array(
                            'key' => 'id',
                            'class' => 'select-search-on t-field-select__input',
                            'prompt' => 'Seleciona o mês',
                            'id' => 'month'
                        ));
                    ?>
                </div>
        </div>
    </div>

    <div id="frequency-container" class="table-responsive frequecy-container">
    </div>
</div>

