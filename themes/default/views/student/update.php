<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update StudentIdentification'));
    $title = Yii::t('default', 'Update StudentIdentification');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentIdentification.');
    $this->menu = [
        ['label' => Yii::t('default', 'Create a new StudentIdentification'), 'url' => ['create'], 'description' => Yii::t('default', 'This action create a new StudentIdentification')],
        ['label' => Yii::t('default', 'List StudentIdentification'), 'url' => ['index'], 'description' => Yii::t('default', 'This action list all Student Identifications, you can search, delete and update')],
    ];
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php
            echo $this->renderPartial('_form', [
                'modelStudentIdentification' => $modelStudentIdentification,
                'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress,
                'modelEnrollment' => $modelEnrollment,
                'vaccines' => $vaccines,
                'studentVaccinesSaves' => $studentVaccinesSaves,
                'title' => $modelStudentIdentification->name]);
            ?>        
        </div>
        <div class="columntwo">
        </div>
    </div>
</div>