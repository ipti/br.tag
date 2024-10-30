<?php
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
            <div class="buttons span9">
            </div>
        </div>
    </div>

<div class="tag-inner">
    <div class="row wrap filter-bar margin-bottom-none">
        <div>
        <div>
            <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 'control-label required small-label')); ?>
                <?= CHtml::dropDownList('classroom_fk', "", CHtml::listData(Classroom::model()->findAll("school_inep_fk = :school_inep_fk and school_year = :school_year order by name", ["school_inep_fk" => Yii::app()->user->school, "school_year" => Yii::app()->user->year]), 'id', 'name'), ["prompt" => yii::t("timesheetModule.timesheet", "Select a Classroom"), "class" => "select-search-on control-input classroom-id"]); ?>
            </div>

            <?php echo CHtml::label(yii::t('default', 'Instructor') . " *", 'substituteInstructor', array('class' => 'control-label required', 'style' => 'width: 80px;')); ?>
            <select class="select-search-on control-input frequency-input" id="substituteInstructor">
                <option>Selecione o professor</option>
                <?php foreach ($instructors as $instructor) : ?>
                    <option value="<?= $instructor["id"] ?>"> <?= $instructor["name"] ?> </option>
                <?php endforeach; ?>
            </select>

            <div class="t-field-select">
                <?php echo CHtml::label(yii::t('default', 'Month') . "/Ano",
                    'month', array('class' => 't-field-select__label--required')); ?>
                <select
                    class="select-search-on t-field-select__input js-load-frequency"
                    id="month"
                    style="min-width: 185px;">
                </select>
            </div>
        </div>
    </div>
</div>

</div>

