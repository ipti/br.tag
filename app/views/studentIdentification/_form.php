<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-identification-form',
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
                        <?php echo $form->labelEx($model,'name'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
                            <?php echo $form->error($model,'name'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'nis'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'nis',array('size'=>11,'maxlength'=>11)); ?>
                            <?php echo $form->error($model,'nis'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'birthday'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'birthday',array('size'=>10,'maxlength'=>10)); ?>
                            <?php echo $form->error($model,'birthday'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'sex'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'sex'); ?>
                            <?php echo $form->error($model,'sex'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'color_race'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'color_race'); ?>
                            <?php echo $form->error($model,'color_race'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'filiation'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'filiation'); ?>
                            <?php echo $form->error($model,'filiation'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'mother_name'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'mother_name',array('size'=>60,'maxlength'=>100)); ?>
                            <?php echo $form->error($model,'mother_name'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'father_name'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'father_name',array('size'=>60,'maxlength'=>100)); ?>
                            <?php echo $form->error($model,'father_name'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'nationality'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'nationality'); ?>
                            <?php echo $form->error($model,'nationality'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'edcenso_nation_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'edcenso_nation_fk'); ?>
                            <?php echo $form->error($model,'edcenso_nation_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'edcenso_uf_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'edcenso_uf_fk'); ?>
                            <?php echo $form->error($model,'edcenso_uf_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'edcenso_city_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'edcenso_city_fk'); ?>
                            <?php echo $form->error($model,'edcenso_city_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency'); ?>
                            <?php echo $form->error($model,'deficiency'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_blindness'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_blindness'); ?>
                            <?php echo $form->error($model,'deficiency_type_blindness'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_low_vision'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_low_vision'); ?>
                            <?php echo $form->error($model,'deficiency_type_low_vision'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_deafness'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_deafness'); ?>
                            <?php echo $form->error($model,'deficiency_type_deafness'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_disability_hearing'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_disability_hearing'); ?>
                            <?php echo $form->error($model,'deficiency_type_disability_hearing'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_deafblindness'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_deafblindness'); ?>
                            <?php echo $form->error($model,'deficiency_type_deafblindness'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_phisical_disability'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_phisical_disability'); ?>
                            <?php echo $form->error($model,'deficiency_type_phisical_disability'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_intelectual_disability'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_intelectual_disability'); ?>
                            <?php echo $form->error($model,'deficiency_type_intelectual_disability'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_multiple_disabilities'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_multiple_disabilities'); ?>
                            <?php echo $form->error($model,'deficiency_type_multiple_disabilities'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_autism'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_autism'); ?>
                            <?php echo $form->error($model,'deficiency_type_autism'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_aspenger_syndrome'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_aspenger_syndrome'); ?>
                            <?php echo $form->error($model,'deficiency_type_aspenger_syndrome'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_rett_syndrome'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_rett_syndrome'); ?>
                            <?php echo $form->error($model,'deficiency_type_rett_syndrome'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_childhood_disintegrative_disorder'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_childhood_disintegrative_disorder'); ?>
                            <?php echo $form->error($model,'deficiency_type_childhood_disintegrative_disorder'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'deficiency_type_gifted'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'deficiency_type_gifted'); ?>
                            <?php echo $form->error($model,'deficiency_type_gifted'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'resource_aid_lector'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'resource_aid_lector'); ?>
                            <?php echo $form->error($model,'resource_aid_lector'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'resource_aid_transcription'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'resource_aid_transcription'); ?>
                            <?php echo $form->error($model,'resource_aid_transcription'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'resource_interpreter_guide'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'resource_interpreter_guide'); ?>
                            <?php echo $form->error($model,'resource_interpreter_guide'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'resource_interpreter_libras'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'resource_interpreter_libras'); ?>
                            <?php echo $form->error($model,'resource_interpreter_libras'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'resource_lip_reading'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'resource_lip_reading'); ?>
                            <?php echo $form->error($model,'resource_lip_reading'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'resource_zoomed_test_16'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'resource_zoomed_test_16'); ?>
                            <?php echo $form->error($model,'resource_zoomed_test_16'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'resource_zoomed_test_20'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'resource_zoomed_test_20'); ?>
                            <?php echo $form->error($model,'resource_zoomed_test_20'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'resource_zoomed_test_24'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'resource_zoomed_test_24'); ?>
                            <?php echo $form->error($model,'resource_zoomed_test_24'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'resource_braille_test'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'resource_braille_test'); ?>
                            <?php echo $form->error($model,'resource_braille_test'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'resource_none'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'resource_none'); ?>
                            <?php echo $form->error($model,'resource_none'); ?>
                        </div>
                    </div>

                                    <div class="formField buttonWizardBar">
                    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
