<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Quiz Answer'));
    $title = Yii::t('default', 'Create a new Quiz');
    $this->widget('quiz.widgets.QuizWidget', array('quizId' => $quizId, 'studentId' => $studentId ));
    ?>
</div>
