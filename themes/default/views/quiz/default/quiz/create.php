<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Create a new Quiz'));
    $title = Yii::t('default', 'Create a new Quiz');
    $this->menu = [
        ['label' => Yii::t('default', 'List Classroom'), 'url' => ['index'], 'description' => Yii::t('default', 'This action list all Classrooms, you can search, delete and update')],
    ];
    ?>
    <?php
        echo $this->renderPartial('//quiz/default/quiz/_form', ['quiz' => $quiz, 'quizQuestion' => $quizQuestion, 'title' => $title]);
    ?>
</div>
