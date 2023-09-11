<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update User'));
    $title = Yii::t('default', 'Update User');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on User.');
    $this->menu = array(
        array('label' => Yii::t('default', 'List User'),
            'url' => array('index'),
            'description' => Yii::t('default', 'This action list all User, you can search, delete and update')),
    );
    ?>
    <?php
    echo $this->renderPartial('_form', array(
        'model' => $model,
        'title' => $title,
        'actual_role' => $actual_role,
        'userSchools' => $userSchools,
        'instructors' => $instructors
    ));
    ?>
</div>
