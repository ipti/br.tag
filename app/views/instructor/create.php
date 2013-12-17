<div id="mainPage" class="main">
    <?php
    $this->breadcrumbs = array(
        'Instructor Identifications' => array('index'),
        'Create',
    );
    $title = Yii::t('default', 'Create a new InstructorIdentification, DocumentsAndAddress, 
                 InstructorVariableData and InstructorTeachingData');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorIdentification, DocumentsAndAddress, 
                 InstructorVariableData and InstructorTeachingData.');
    $this->menu = array(
        array('label' => Yii::t('default', 'List InstructorIdentification, DocumentsAndAddress, 
                 InstructorVariableData and InstructorTeachingData'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all Instructor Identifications, you can search, delete and update')),
    );
    ?>
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php
            echo $this->renderPartial('_form', array(
                'modelInstructorIdentification' => $modelInstructorIdentification,
                'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress,
                'modelInstructorVariableData' => $modelInstructorVariableData,
                'modelInstructorTeachingData' => $modelInstructorTeachingData,
                'error' => $error,
                'title' => $title));
            ?>        </div>
        <!--        <div class="columntwo">
<?php // echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc));  ?>        </div>
            </div>-->
    </div>