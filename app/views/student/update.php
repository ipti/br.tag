<div id="mainPage" class="main">
    <?php
    $this->breadcrumbs = array(
        Yii::t('default', 'Student Identifications') => array('index'),
        $modelStudentIdentification->name,
    );

    $title = Yii::t('default', 'Update StudentIdentification');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentIdentification.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new StudentIdentification'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new StudentIdentification')),
        array('label' => Yii::t('default', 'List StudentIdentification'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all Student Identifications, you can search, delete and update')),
    );
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php echo $this->renderPartial('_form', array('modelStudentIdentification' => $modelStudentIdentification, 'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress, 'title' => $title)); ?>        </div>
        <div class="columntwo">
        </div>
    </div>
</div>