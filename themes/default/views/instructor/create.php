<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default','Add New Teacher'));
    $title = Yii::t('default', 'Add New Teacher');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorIdentification, DocumentsAndAddress, 
                 InstructorVariableData and InstructorTeachingData.');
    $this->menu = array(
        array('label' => Yii::t('default', 'List InstructorIdentification, DocumentsAndAddress, 
                 InstructorVariableData and InstructorTeachingData'), 
            'url' => array('index'), 
            'description' => Yii::t('default', 'This action list all Instructor Identifications, you can search, delete and update')),
    );
    ?>
    <?php
    echo $this->renderPartial('_form', array(
        'modelInstructorIdentification' => $modelInstructorIdentification,
        'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress,
        'modelInstructorVariableData' => $modelInstructorVariableData,
        'error' => $error,
        'title' => $title));
    ?> 
</div>