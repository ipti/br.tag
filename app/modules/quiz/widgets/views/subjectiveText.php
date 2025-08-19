<div class="row-fluid">
    <div class="span12">
        <div class="widget widget-scroll margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
            <div class="widget-head white-background">
                <h4 class="heading glyphicons nameplate">
                    <i></i> <?= $model->question->description ?>
                </h4>
            </div> <!-- .widget-head -->
            <?php
                $answer = Answer::model()->findByAttributes(['quiz_id' => $model->quiz->id, 'question_id' => $model->question->id, 'student_id' => $model->student->id, 'seq' => 1]);
                                    
                if(is_null($answer)){
                    $answer = new Answer;
                }
            ?>
            <div class="widget-body in" style="height: auto;">
                <div class="control-group">                
                    <?php echo CHtml::label('Resposta:', $model->getIdentifier(), array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::textArea($model->getIdentifier(), $answer->value, array('maxlength' => 500)); ?>
                    </div>
                </div> <!-- .control-group -->
            </div> <!-- .widget-body --> 
        </div> <!-- .widget -->
    </div> <!-- .span12 -->
</div> <!-- .row-fluid -->
