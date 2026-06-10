<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Create a new Question Group'));
    $title = Yii::t('default', 'Create a new Question Group');
    $this->menu = array(
        array('label' => Yii::t('default', 'List Question Group'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all Question Groups, you can search, delete and update')),
    );
    ?>
    <?php
        echo $this->renderPartial('//quiz/default/questiongroup/_form', array('questionGroup' => $questionGroup, 'title' => $title));
    ?>
</div>
