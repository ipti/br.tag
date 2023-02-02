<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update Quiz'));
    $title = Yii::t('default', 'Update Quiz');
    ?>
    <?php
        echo $this->renderPartial('//quiz/default/quiz/_form', ['quiz' => $quiz, 'quizQuestion' => $quizQuestion, 'title' => $title]);
    ?>
</div>
