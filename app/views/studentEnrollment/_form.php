<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-enrollment-form',
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
                        <?php echo $form->labelEx($model,'student_inep_id'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'student_inep_id',array('size'=>12,'maxlength'=>12)); ?>
                            <?php echo $form->error($model,'student_inep_id'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'student_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'student_fk'); ?>
                            <?php echo $form->error($model,'student_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'classroom_inep_id'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'classroom_inep_id',array('size'=>12,'maxlength'=>12)); ?>
                            <?php echo $form->error($model,'classroom_inep_id'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'classroom_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'classroom_fk'); ?>
                            <?php echo $form->error($model,'classroom_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'enrollment_id'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'enrollment_id',array('size'=>12,'maxlength'=>12)); ?>
                            <?php echo $form->error($model,'enrollment_id'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'unified_class'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'unified_class'); ?>
                            <?php echo $form->error($model,'unified_class'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'edcenso_stage_vs_modality_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'edcenso_stage_vs_modality_fk'); ?>
                            <?php echo $form->error($model,'edcenso_stage_vs_modality_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'another_scholarization_place'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'another_scholarization_place'); ?>
                            <?php echo $form->error($model,'another_scholarization_place'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'public_transport'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'public_transport'); ?>
                            <?php echo $form->error($model,'public_transport'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'transport_responsable_government'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'transport_responsable_government'); ?>
                            <?php echo $form->error($model,'transport_responsable_government'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_van'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_van'); ?>
                            <?php echo $form->error($model,'vehicle_type_van'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_microbus'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_microbus'); ?>
                            <?php echo $form->error($model,'vehicle_type_microbus'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_bus'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_bus'); ?>
                            <?php echo $form->error($model,'vehicle_type_bus'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_bike'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_bike'); ?>
                            <?php echo $form->error($model,'vehicle_type_bike'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_animal_vehicle'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_animal_vehicle'); ?>
                            <?php echo $form->error($model,'vehicle_type_animal_vehicle'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_other_vehicle'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_other_vehicle'); ?>
                            <?php echo $form->error($model,'vehicle_type_other_vehicle'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_waterway_boat_5'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_waterway_boat_5'); ?>
                            <?php echo $form->error($model,'vehicle_type_waterway_boat_5'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_waterway_boat_5_15'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_waterway_boat_5_15'); ?>
                            <?php echo $form->error($model,'vehicle_type_waterway_boat_5_15'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_waterway_boat_15_35'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_waterway_boat_15_35'); ?>
                            <?php echo $form->error($model,'vehicle_type_waterway_boat_15_35'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_waterway_boat_35'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_waterway_boat_35'); ?>
                            <?php echo $form->error($model,'vehicle_type_waterway_boat_35'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'vehicle_type_metro_or_train'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'vehicle_type_metro_or_train'); ?>
                            <?php echo $form->error($model,'vehicle_type_metro_or_train'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'student_entry_form'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'student_entry_form'); ?>
                            <?php echo $form->error($model,'student_entry_form'); ?>
                        </div>
                    </div>

                                    <div class="formField buttonWizardBar">
                    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
