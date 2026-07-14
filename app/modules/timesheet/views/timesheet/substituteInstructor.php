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
        <div class="row justify-content--space-between">
            <div class="column clearleft is-four-fifths row">
                <!-- Classroom -->
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

                <!-- Instructor -->
                <div class="t-field-select instructors-field hide">
                    <?php echo CHtml::label(yii::t('default', 'Instructor'), 'instructor', array('class' => 't-field-select__label--required')); ?>
                    <select
                        class="select-search-on t-field-select__input frequency-input"
                        style="min-width: 185px"
                        id="instructor">
                    </select>
                </div>

                <!-- Month -->
                <div class="t-field-select month-field hide">
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

                <!-- Discipline -->
                <div class="t-field-select disciplines-field hide">
                    <?php echo CHtml::label(yii::t('default', 'Discipline'), 'discipline', array('class' => 't-field-select__label'));?>
                    <select
                        class="select-search-on t-field-select__input"
                        style="min-width: 185px;"
                        id="disciplines">
                    </select>
                </div>

                <div id="js-loading-div" class="row hide">
                    <img
                        style="margin: 10px 20px;"
                        height="30px" width="30px"
                        src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif"
                        alt="TAG Loading"
                    >
                </div>
            </div>
        </div>
    </div>

    <div id="frequency-container" class="table-responsive frequecy-container">
    </div>
</div>

