<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update Question'));
    $title = Yii::t('default', 'Update Question');
    ?>
    <?php
        echo $this->renderPartial('//quiz/default/question/_form', array('question' => $question, 'title' => $title, 'option' => $option));
    ?>
</div>
