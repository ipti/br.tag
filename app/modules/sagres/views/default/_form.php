<?php
/* @var $this ProvisionAcountsController */
/* @var $model ProvisionAcounts */
/* @var $form CActiveForm */

$modulePath = Yii::app()->getModule('sagres')->getBasePath();
$baseUrl = Yii::app()->getAssetManager()->publish($modulePath . '/resources/form/validations.js');
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl, CClientScript::POS_END);

?>

<div class="form">

    <?php
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    

    $form = $this->beginWidget(
        'CActiveForm',
        array(
            'id' => 'provision-acounts-form',
            'enableAjaxValidation' => false,
        )
    );
    ?>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <h1>
                <?php echo $title; ?>
            </h1>
            <div class="tag-buttons-container buttons">
                <button class="t-button-primary pull-right save-provision-acounts" type="submit">
                    <?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('success') && (!$model->isNewRecord)) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div class="widget widget-tabs border-bottom-none">
            <?php echo $form->errorSummary($model); ?>
            <div class="alert alert-error provision-acounts-error no-show"></div>
            <div class="widget-body form-horizontal">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="separator">
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'cod_unidade_gestora', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($model, 'cod_unidade_gestora', array('size' => 30, 'maxlength' => 30, 'placeholder' => 'Digite o Código da Unidade Gestora')); ?>
                                            <?php echo $form->error($model, 'cod_unidade_gestora'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'name_unidade_gestora', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($model, 'name_unidade_gestora', array('size' => 150, 'maxlength' => 150, 'placeholder' => 'Digite o Nome da Unidade Gestora')); ?>
                                            <?php echo $form->error($model, 'name_unidade_gestora'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'cpf_responsavel', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($model, 'cpf_responsavel', array('size' => 14, 'maxlength' => 14, 'id' => 'input_responsible_cpf')); ?>
                                            <?php echo $form->error($model, 'cpf_responsavel'); ?>
                                        </div>
                                    </div>


                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'cpf_gestor', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($model, 'cpf_gestor', array('size' => 14, 'maxlength' => 14,'id' => 'input_manager_cpf')); ?>
                                            <?php echo $form->error($model, 'cpf_gestor'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->endWidget(); ?>