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
$this->setPageTitle('TAG - ' . Yii::t('default', 'Quiz'));

$form = $this->beginWidget('CActiveForm', [
    'id' => 'quiz-form',
    'enableAjaxValidation' => false,
]);
?>

<div class="row-fluid  hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?></h3>  
        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . ($quiz->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), ['id' => 'save_button', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'button']);
            ?>
            <?php
                if (!$quiz->isNewRecord) {
                    echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Delete'), ['id' => 'delete_button', 'class' => 'btn btn-icon btn-primary last glyphicons delete', 'type' => 'button']);
                }
            ?>
        </div>
    </div>
</div>

<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success') && (!$quiz->isNewRecord)): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>

    <?php if (Yii::app()->user->hasFlash('error') && (!$quiz->isNewRecord)): ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>
    
    <div class="widget widget-tabs border-bottom-none">
        <div class="widget-head  hidden-print">
            <ul class="tab-classroom">
                <li id="tab-quiz" class="active" ><a class="glyphicons adress_book" href="#quiz" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Quiz') ?></a></li>
                <?php if (!$quiz->isNewRecord): ?>
                    <li id="tab-question"><a class="glyphicons book" href="#question" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Question') ?></a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                    
                <div class="tab-pane active" id="quiz">
                        <div class="row-fluid">
                            <div class=" span5">
                                <div class="control-group">                
                                    <?php echo $form->labelEx($quiz, 'name', ['class' => 'control-label']); ?>
                                    <div class="controls">
                                        <?php echo $form->textField($quiz, 'name', ['size' => 60, 'maxlength' => 150]); ?>
                                        <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Quiz Name'); ?>"><i></i></span>
                                        <?php echo $form->error($quiz, 'name'); ?>
                                    </div>
                                </div> 
                                <!-- .control-group -->
                                <div class="control-group" id="modality">
                                    <?php echo $form->labelEx($quiz, 'status', ['class' => 'control-label required']); ?>
                                    <div class="controls">
                                        <?php
                                        echo $form->DropDownList($quiz, 'status', [
                                            null => 'Selecione o status',
                                            '1' => 'Ativo',
                                            '0' => 'Inativo'], ['class' => 'select-search-off']);
                                        ?>
                                        <?php echo $form->error($quiz, 'status'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class=" span5">
                                <div class="control-group">                
                                    <?php echo $form->labelEx($quiz, 'init_date', ['class' => 'control-label']); ?>
                                    <div class="controls">
                                        <?= $form->dateField($quiz, 'init_date') ?>
                                        <?php echo $form->error($quiz, 'init_date'); ?>
                                    </div>
                                </div> <!-- .control-group -->
                                <div class="control-group">
                                    <?php echo $form->labelEx($quiz, 'final_date', ['class' => 'control-label required']); ?>
                                    <div class="controls">
                                        <?= $form->dateField($quiz, 'final_date') ?>
                                        <?php echo $form->error($quiz, 'final_date'); ?>
                                    </div>
                                </div> <!-- .control-group -->
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="span12">
							<div class="control-group">                
                                    <?php echo $form->labelEx($quiz, 'description', ['class' => 'control-label']); ?>
                                    <div class="controls">
                                        <?= $form->textArea($quiz, 'description', ['rows' => 5, 'cols' => 110]) ?>
                                        <?php echo $form->error($quiz, 'description'); ?>
                                    </div>
                                </div> <!-- .control-group -->

                            </div>
                        </div>
                    </div>
                    <?php if (!$quiz->isNewRecord): ?>
                    <div class="tab-pane" id="question">
                        <div class="row-fluid">
                            <div class="span5">
                                <div class="control-group">                
                                    <?php echo CHtml::label('Questão', 'id', ['class' => 'control-label']); ?>
                                    <div class="controls">
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
                                            ['prompt' => 'Selecione uma questão', 'class' => 'select-search-on']
                                        ); ?>
                                    </div>
                                    <?php echo $form->hiddenField($quizQuestion, 'quiz_id', ['size' => 60, 'maxlength' => 45, 'value' => $quiz->id]); ?>
                                </div> <!-- .control-group -->
								<div class="control-group">
									<div class="controls">
										<button id="save_quiz_question_button" class="btn btn-icon btn-primary last glyphicons circle_ok" type="button" name="yt0"><i></i>Salvar</button>
									</div>
								</div>
                            </div>

                            <div class="span6">
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
	
</div>

<?php $form = $this->endWidget();

$dataQuizQuestion = [];

$query = Yii::app()->db->createCommand()
    ->select('qq.quiz_id, qq.question_id, description')
    ->from('quiz_question qq')
    ->join('question qu', 'qq.question_id=qu.id')
    ->where('qq.quiz_id=:quizId', [':quizId' => $quiz->id])
    ->queryAll();

foreach ($query as $value) {
    $dataQuizQuestion[] = $value;
}

$script = '
    var dataQuizQuestion = ' . json_encode($dataQuizQuestion) . ';
    QuizQuestion.init();';

$cs->registerScript('quizQuestion', $script, CClientScript::POS_END);

?>