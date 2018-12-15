<?php
    $baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
    $cs->registerScriptFile($baseScriptUrl . '/common/js/quiz.js', CClientScript::POS_END);
?>
<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Quiz Answer'));
    $title = Yii::t('default', 'Create a new Quiz');
    $this->widget('quiz.widgets.QuizWidget', array('quizId' => $quizId, 'studentId' => $studentId ));
    ?>
</div>
