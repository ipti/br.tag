<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update InstructorIdentification'));
    $title = Yii::t('default', 'Update InstructorIdentification');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorIdentification.');
    $this->menu = [
        ['label' => Yii::t('default', 'Create a new InstructorIdentification'),
            'url' => ['create'],
            'description' => Yii::t('default', 'This action create a new InstructorIdentification')],
        ['label' => Yii::t('default', 'List InstructorIdentification'),
            'url' => ['index'],
            'description' => Yii::t('default', 'This action list all Instructor Identifications, you can search, delete and update')],
    ];
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php
            echo $this->renderPartial('_form', ['modelInstructorIdentification' => $modelInstructorIdentification,
                'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress, 'modelInstructorVariableData' => $modelInstructorVariableData,
                'title' => $title, 'error' => $error]);
            ?>        </div>
        <div class="columntwo">
        </div>
    </div>
</div>
