<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Add New Student'));
    $title = Yii::t('default', 'Add New Student');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentIdentification.');
    $this->menu = array(
        array('label' => Yii::t('default', 'List StudentIdentification'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all Student Identifications, you can search, delete and update')),
    );
    ?>
    <?php
    echo $this->renderPartial('_form', array(
        'modelStudentIdentification' => $modelStudentIdentification,
        'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress,
        'modelStudentRestrictions' => $modelStudentRestrictions,
        'modelStudentDisorder' => $modelStudentDisorder,
        'modelEnrollment' => $modelEnrollment,
        'vaccines' => $vaccines,
        'studentVaccinesSaves' => $studentVaccinesSaves,
        'title' => $title
    ));
    ?>
</div>
