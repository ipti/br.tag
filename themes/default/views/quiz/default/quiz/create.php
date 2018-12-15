<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Create a new Quiz'));
    $title = Yii::t('default', 'Create a new Quiz');
    $this->menu = array(
        array('label' => Yii::t('default', 'List Classroom'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all Classrooms, you can search, delete and update')),
    );
    ?>
    <?php
        echo $this->renderPartial('//quiz/default/quiz/_form', array('quiz' => $quiz, 'quizQuestion' => $quizQuestion, 'title' => $title));
    ?>
</div>
