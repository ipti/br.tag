    <?php
    /* @var $this DefaultController */
    /* @var $model EdcensoStageVsModality */
    /* @var $form CActiveForm */

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'edcenso-stage-vs-modality-form',
        'enableAjaxValidation'=>false,
    ));
    ?>

    <div class="form">
        <div class="mobile-row ">
            <div class="column clearleft">
                <h1><?php echo $model->isNewRecord ? 'Criar Etapa' : 'Atualizar Etapa ' . $model->id?></h1>
            </div>
            <div class="column clearfix align-items--center justify-content--end show--desktop">
                <button class="t-button-primary" type="submit">
                    <?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                </button>
            </div>
        </div>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <div class="column is-two-fifths clearfix">
                <div class="t-field-text">
                    <?php echo $form->labelEx($model,'name', array('class' => 't-field-text__label--required')); ?>
                    <?php echo $form->textField($model,'name', array('size'=>60,'maxlength'=>100, 'class' => 't-field-text__input')); ?>
                    <?php echo $form->error($model,'name'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="column is-two-fifths clearfix">
                <div class="t-field-text">
                    <?php echo $form->labelEx($model,'stage', array('class' => 't-field-text__label--required')); ?>
                    <?php echo $form->textField($model,'stage', array('class' => 't-field-text__input')); ?>
                    <?php echo $form->error($model,'stage'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="column is-two-fifths clearfix">
                <div class="t-field-text">
                    <?php echo $form->labelEx($model,'alias', array('class' => 't-field-text__label--required')); ?>
                    <?php echo $form->textField($model,'alias', array('size'=>15, 'class' => 't-field-text__input')); ?>
                    <?php echo $form->error($model,'alias'); ?>
                </div>
            </div>
        </div>

        <div class="row show--tablet">
            <div class="column clearfix">
                <div class="t-buttons-container">
                    <a class="t-button-primary" type="submit">
                        <?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                    </a>
                </div>
            </div>
        </div>


    <?php $this->endWidget(); ?>

    </div>
