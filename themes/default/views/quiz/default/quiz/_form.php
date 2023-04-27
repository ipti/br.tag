<?php
/* @var $this calendar.defaultController
 * @var $modelCalendar Calendar
 * @var $modelEvent CalendarEvent
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.0');
$cs->registerScriptFile($baseScriptUrl . '/common/js/quiz.js', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/template2.css');
$cs->registerCssFile($baseUrl . 'sass/css/main.css');

$this->setPageTitle('TAG - ' . Yii::t('default', 'Quiz'));


$form = $this->beginWidget('CActiveForm', array(
    'id' => 'quiz-form',
    'enableAjaxValidation' => false,
));
?>

<div class="row-fluid  hidden-print">
    <div class="span12">
        <h1><?php echo $title; ?></h1>
        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . ($quiz->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('id' => 'save_button', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'button'));
            ?>
            <?php
            if (!$quiz->isNewRecord) {
                echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Delete'), array('id' => 'delete_button', 'class' => 'btn btn-icon btn-primary last glyphicons delete', 'type' => 'button'));
            }
            ?>
        </div>
    </div>
</div>

<div class="tag-inner">
    <?php if (Yii::app()->user->hasFlash('success') && (!$quiz->isNewRecord)) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>

    <?php if (Yii::app()->user->hasFlash('error') && (!$quiz->isNewRecord)) : ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>

    <div class="widget widget-tabs border-bottom-none">
        <div class="t-tabs js-tab-control">
            <ul class=" tab-student t-tabs__list tab-classroom">
                <li id="tab-quiz" class="t-tabs__item active">
                    <a class="t-tabs__link" href="#quiz" data-toggle="tab">
                        <span class="t-tabs__numeration">1</span>
                        <?php echo Yii::t('default', 'Quiz') ?>
                    </a>
                </li>
                <?php if (!$quiz->isNewRecord) : ?>
                    <li id="tab-question" class="t-tabs__item">
                        <a class="t-tabs__link" href="#question" data-toggle="tab">
                            <span class="t-tabs__numeration">2</span>
                            <?php echo Yii::t('default', 'Question') ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content form-content">
                <div class="tab-pane active" id="quiz">
                    <div class="row">
                        <div class="column">
                            <div class="t-field-text">
                                <?php echo $form->labelEx($quiz, 'name', array('class' => 'control-label t-field-text__label--required')); ?>
                                <?php echo $form->textField($quiz, 'name', array('size' => 60, 'maxlength' => 150, 'class' => 't-field-text__input',)); ?>
                                <?php echo $form->error($quiz, 'name'); ?>
                            </div>
                            
                            <div class="t-field-select" id="modality">
                                <?php echo $form->labelEx($quiz, 'status', array('class' => 'control-label t-field-text__label--required')); ?>
                                <?php
                                echo $form->DropDownList($quiz, 'status', array(
                                    null => 'Selecione o status',
                                    '1' => 'Ativo',
                                    '0' => 'Inativo'
                                ), array('class' => 'select-search-off t-field-select__input'));
                                ?>
                                <?php echo $form->error($quiz, 'status'); ?>
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-text">
                                <?php echo $form->labelEx($quiz, 'init_date', array('class' => 'control-label required')); ?>
                                <?= $form->dateField($quiz, "init_date", array('class' => 't-field-text__input')) ?>
                                <?php echo $form->error($quiz, 'init_date'); ?>
                            </div>
                            <div class="t-field-text">
                                <?php echo $form->labelEx($quiz, 'final_date', array('class' => 'control-label required')); ?>
                                <?= $form->dateField($quiz, "final_date", array('class' => 't-field-text__input')) ?>
                                <?php echo $form->error($quiz, 'final_date'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
                            <div class="control-group hide-responsive">
                                <?php echo $form->labelEx($quiz, 'description', array('class' => 'control-label')); ?>
                                <?= $form->textArea($quiz, "description", array('rows' => 5, 'cols' => 110)) ?>
                                <?php echo $form->error($quiz, 'description'); ?>
                            </div>
                        </div>
                        <div class="column">
                        </div>
                    </div>
                </div>
                <?php if (!$quiz->isNewRecord) : ?>
                    <div class="tab-pane" id="question">
                        <div class="row">
                            <div class="column helper">
                                <div class="t-field-select ">
                                    <?php echo CHtml::label('Questão', 'id', array('class' => 'control-label t-field-text__label required')); ?>
                                    <?php
                                    $questions = Question::model()->findAll();
                                    echo $form->dropDownList(
                                        $quizQuestion,
                                        'question_id',
                                        CHtml::listData(
                                            $questions,
                                            'id',
                                            'description'
                                        ),
                                        array("prompt" => "Selecione uma questão", 'class' => 'select-search-on t-field-select__input')
                                    ); ?>
                                    <?php echo $form->hiddenField($quizQuestion, 'quiz_id', array('size' => 60, 'maxlength' => 45, 'value' => $quiz->id)); ?>
                                </div> <!-- .control-group -->
                                <div class="control-group">
                                    <button id="save_quiz_question_button" class="btn btn-icon btn-primary last glyphicons circle_ok" type="button" name="yt0"><i></i>Salvar</button>
                                </div>
                            </div>

                            <div class="column">
                                <table class="grade-table table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="15%">Nº</th>
                                            <th width="55%">Opção</th>
                                            <th width="30%">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="container_quiz_question"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $form = $this->endWidget();



$dataQuizQuestion = [];

$query = Yii::app()->db->createCommand()
    ->select('qq.quiz_id, qq.question_id, description')
    ->from('quiz_question qq')
    ->join('question qu', 'qq.question_id=qu.id')
    ->where('qq.quiz_id=:quizId', array(':quizId' => $quiz->id))
    ->queryAll();

foreach ($query as $value) {
    $dataQuizQuestion[] = $value;
}



$script = "
    var dataQuizQuestion = " . json_encode($dataQuizQuestion) . ";
    QuizQuestion.init();";

$cs->registerScript('quizQuestion', $script, CClientScript::POS_END);

?>