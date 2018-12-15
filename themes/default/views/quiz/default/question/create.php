<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Create a new Question'));
    $title = Yii::t('default', 'Create a new Question');
    $this->menu = array(
        array('label' => Yii::t('default', 'List Question'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all Questions, you can search, delete and update')),
    );
    ?>
    <?php
        echo $this->renderPartial('//quiz/default/question/_form', array('question' => $question, 'title' => $title, 'option' => $option));
    ?>
</div>
