<?php
    $this->setPageTitle('TAG - ' . Yii::t('timesheetModule.timesheet', 'Assign Substitute Instructor'));

    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
?>

<div class="main">

<div class="tag-inner">
    <div class="row wrap filter-bar margin-bottom-none">
        <div>
            <?php echo CHtml::label(yii::t('default', 'Instructor') . " *", 'substituteInstructor', array('class' => 'control-label required', 'style' => 'width: 80px;')); ?>

            <select class="select-search-on control-input frequency-input" id="substituteInstructor">
                <option>Selecione o professor</option>
                <?php foreach ($instructors as $instructor) : ?>
                    <option value="<?= $instructor["id"] ?>"> <?= $instructor["name"] ?> </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

</div>

