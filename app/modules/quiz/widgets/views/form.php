<?php echo CHtml::beginForm(null, 'post', array('id'=> 'answer-form')); ?>

<div class="row-fluid  hidden-print">
    <div class="span12">
        <h1><?= $quiz->name?></h1>  
        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Save'), array('id' => 'save_answer_button', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'button'));
            ?>
        </div>
    </div>
</div>

<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>

    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">
        <div class="widget-head  hidden-print">
            <ul class="tab-classroom">
                <?php
                    $active = true;
                    foreach($groups as $group){?>
                    <li id="tab-<?= $group->id ?>"  <?php if($active){ echo 'class="active"'; $active = false;} ?> >
                        <a class="glyphicons adress_book" href="#group-<?= $group->id ?>" data-toggle="tab">
                            <i></i><?php echo Yii::t('default', $group->name) ?>
                        </a>
                    </li>
                <?php
                    }
                ?>
            </ul>
        </div> <!-- .widget-head -->

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <?php 
                    reset($groups);
                    $active = true;
                    foreach($groups as $group){ ?>
                        <div class="tab-pane <?php if($active){ echo 'active'; $active = false;} ?>" id="group-<?= $group->id ?>">
                            <?php
                                foreach($questions[$group->id] as $formQuestion){
                                    $this->widget('quiz.widgets.QuestionWidget', array('model' => $formQuestion));
                                }
                            ?>
                        </div>  <!-- .tab-pane -->
                <?php  } ?>
            </div>  <!-- .tab-content -->
        </div>  <!-- .widget-body -->
        
        
    </div> <!-- .widget .widget-tabs -->
</div> <!-- .innerLR -->

<?php echo CHtml::endForm(); ?>