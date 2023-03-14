<?php
/* @var $this ProvisionAcountsController */
/* @var $model ProvisionAcounts */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    // $baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($themeUrl . '/css/template2.css');
    // $cs->registerScriptFile($baseScriptUrl . '/common/js/provisionacounts.js?v=1.1', CClientScript::POS_END);

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
                                            <?php echo $form->textField($model, 'cod_unidade_gestora', array('size' => 30, 'maxlength' => 30)); ?>
                                            <?php echo $form->error($model, 'cod_unidade_gestora'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'name_unidade_gestora', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($model, 'name_unidade_gestora', array('size' => 150, 'maxlength' => 150)); ?>
                                            <?php echo $form->error($model, 'name_unidade_gestora'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'cpf_responsavel', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($model, 'cpf_responsavel', array('size' => 14, 'maxlength' => 14, 'placeholder'=>'___.___.___-__')); ?>
                                            <?php echo $form->error($model, 'cpf_responsavel'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'cpf_gestor', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->textField($model, 'cpf_gestor', array('size' => 14, 'maxlength' => 14,'placeholder'=>'___.___.___-__')); ?>
                                            <?php echo $form->error($model, 'cpf_gestor'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'mes_referencia', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->DropDownList($model, 'mes_referencia', array(
                                                '01' => 'Janeiro',
                                                '02' => 'Fevereiro',
                                                '03' => 'Março',
                                                '04' => 'Abril',
                                                '05' => 'Maio',
                                                '06' => 'Junho',
                                                '07' => 'Julho',
                                                '08' => 'Agosto',
                                                '09' => 'Setembro',
                                                '10' => 'Outubro',
                                                '11' => 'Novembro',
                                                '12' => 'Dezembro'
                                            )); ?>
                                            <?php echo $form->error($model, 'mes_referencia'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'ano_referencia', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php 
                                            $anos = array();
                                            for ($i = date('Y'); $i >= 2014; $i--) {
                                                $anos[$i] = $i;
                                            }
                                            echo $form->DropDownList($model,'ano_referencia',$anos); ?>
                                            <?php echo $form->error($model, 'ano_referencia'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'versao_xml', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->DropDownList($model, 'versao_xml', array(
                                                '1.0' => '1.0',
                                                '1.1' => '1.1',
                                                '1.2' => '1.2',
                                            )); ?>
                                            <?php echo $form->error($model, 'versao_xml'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'dia_inicio_prest_contas', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->dateField($model, 'dia_inicio_prest_contas', array('size' => 60, 'maxlength' => 100)); ?>
                                            <?php echo $form->error($model, 'dia_inicio_prest_contas'); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <div class="controls">
                                            <?php echo $form->labelEx($model, 'dia_final_prest_contas', array('class' => 'control-label')); ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $form->dateField($model, 'dia_inicio_prest_contas', array('size' => 60, 'maxlength' => 100)); ?>
                                            <?php echo $form->error($model, 'dia_final_prest_contas'); ?>
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