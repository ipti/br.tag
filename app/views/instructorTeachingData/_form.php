<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'instructor-teaching-data-form',
	'enableAjaxValidation'=>false,
)); ?>
        <div class="panelGroup form">
            <?php echo $form->errorSummary($model); ?>
            <div class="panelGroupHeader"><div class=""> <?php echo $title; ?>
</div></div>
            <div class="panelGroupBody">
                <div class="panelGroupAbout">
                     <?php echo Yii::t('default', 'Fields with * are required.')?></div>

                                    <div class="control-group">
                        <?php echo $form->labelEx($model,'register_type'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'register_type',array('size'=>2,'maxlength'=>2)); ?>
                            <?php echo $form->error($model,'register_type'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'school_inep_id_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'school_inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
                            <?php echo $form->error($model,'school_inep_id_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'inep_id'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'inep_id',array('size'=>12,'maxlength'=>12)); ?>
                            <?php echo $form->error($model,'inep_id'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'classroom_inep_id'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'classroom_inep_id',array('size'=>8,'maxlength'=>8)); ?>
                            <?php echo $form->error($model,'classroom_inep_id'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'classroom_id_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'classroom_id_fk'); ?>
                            <?php echo $form->error($model,'classroom_id_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'role'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'role'); ?>
                            <?php echo $form->error($model,'role'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'contract_type'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'contract_type'); ?>
                            <?php echo $form->error($model,'contract_type'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_1_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_1_fk'); ?>
                            <?php echo $form->error($model,'discipline_1_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_2_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_2_fk'); ?>
                            <?php echo $form->error($model,'discipline_2_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_3_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_3_fk'); ?>
                            <?php echo $form->error($model,'discipline_3_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_4_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_4_fk'); ?>
                            <?php echo $form->error($model,'discipline_4_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_5_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_5_fk'); ?>
                            <?php echo $form->error($model,'discipline_5_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_6_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_6_fk'); ?>
                            <?php echo $form->error($model,'discipline_6_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_7_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_7_fk'); ?>
                            <?php echo $form->error($model,'discipline_7_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_8_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_8_fk'); ?>
                            <?php echo $form->error($model,'discipline_8_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_9_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_9_fk'); ?>
                            <?php echo $form->error($model,'discipline_9_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_10_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_10_fk'); ?>
                            <?php echo $form->error($model,'discipline_10_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_11_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_11_fk'); ?>
                            <?php echo $form->error($model,'discipline_11_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_12_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_12_fk'); ?>
                            <?php echo $form->error($model,'discipline_12_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'discipline_13_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'discipline_13_fk'); ?>
                            <?php echo $form->error($model,'discipline_13_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'instructor_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'instructor_fk'); ?>
                            <?php echo $form->error($model,'instructor_fk'); ?>
                        </div>
                    </div>

                                    <div class="formField buttonWizardBar">
                    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
