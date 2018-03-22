<div class="control-group">                
    <?php echo CHtml::label($model->question->description, $model->getIdentifier(), array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo CHtml::textField($model->getIdentifier(), $model->answer->value, array('size' => 60, 'maxlength' => 150)); ?>
    </div>
</div> <!-- .control-group -->