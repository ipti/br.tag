<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Create a new SchoolIdentification'));
    $title = Yii::t('default', 'Create a new SchoolIdentification');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on SchoolIdentification.');
    $this->menu = array(
        array(
            'label' => Yii::t('default', 'List SchoolIdentification'),
            'url' => array('index'),
            'description' => Yii::t('default', 'This action list all School Identifications, you can search, delete and update')
        ),
    );
    ?>
    <?php
    echo $this->renderPartial('_form', array(
        'modelSchoolIdentification' => $modelSchoolIdentification,
        'modelSchoolStructure' => $modelSchoolStructure,
        'modelManagerIdentification' => $modelManagerIdentification,
        'title' => $title,
    ));
    ?> 

</div>