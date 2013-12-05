<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-documents-and-address-form',
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
                        <?php echo $form->labelEx($model,'student_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'student_fk'); ?>
                            <?php echo $form->error($model,'student_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'rg_number'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'rg_number',array('size'=>20,'maxlength'=>20)); ?>
                            <?php echo $form->error($model,'rg_number'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'rg_number_complement'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'rg_number_complement',array('size'=>4,'maxlength'=>4)); ?>
                            <?php echo $form->error($model,'rg_number_complement'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'rg_number_edcenso_organ_id_emitter_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'rg_number_edcenso_organ_id_emitter_fk'); ?>
                            <?php echo $form->error($model,'rg_number_edcenso_organ_id_emitter_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'rg_number_edcenso_uf_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'rg_number_edcenso_uf_fk'); ?>
                            <?php echo $form->error($model,'rg_number_edcenso_uf_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'rg_number_expediction_date'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'rg_number_expediction_date',array('size'=>10,'maxlength'=>10)); ?>
                            <?php echo $form->error($model,'rg_number_expediction_date'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'civil_certification'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'civil_certification'); ?>
                            <?php echo $form->error($model,'civil_certification'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'civil_certification_type'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'civil_certification_type'); ?>
                            <?php echo $form->error($model,'civil_certification_type'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'civil_certification_term_number'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'civil_certification_term_number',array('size'=>8,'maxlength'=>8)); ?>
                            <?php echo $form->error($model,'civil_certification_term_number'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'civil_certification_sheet'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'civil_certification_sheet',array('size'=>4,'maxlength'=>4)); ?>
                            <?php echo $form->error($model,'civil_certification_sheet'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'civil_certification_book'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'civil_certification_book',array('size'=>8,'maxlength'=>8)); ?>
                            <?php echo $form->error($model,'civil_certification_book'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'civil_certification_date'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'civil_certification_date',array('size'=>10,'maxlength'=>10)); ?>
                            <?php echo $form->error($model,'civil_certification_date'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'notary_office_uf_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'notary_office_uf_fk'); ?>
                            <?php echo $form->error($model,'notary_office_uf_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'notary_office_city_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'notary_office_city_fk'); ?>
                            <?php echo $form->error($model,'notary_office_city_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'edcenso_notary_office_fk'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'edcenso_notary_office_fk'); ?>
                            <?php echo $form->error($model,'edcenso_notary_office_fk'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'civil_register_enrollment_number'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'civil_register_enrollment_number',array('size'=>32,'maxlength'=>32)); ?>
                            <?php echo $form->error($model,'civil_register_enrollment_number'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'cpf'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'cpf',array('size'=>11,'maxlength'=>11)); ?>
                            <?php echo $form->error($model,'cpf'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'foreign_document_or_passport'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'foreign_document_or_passport',array('size'=>20,'maxlength'=>20)); ?>
                            <?php echo $form->error($model,'foreign_document_or_passport'); ?>
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
                        <?php echo $form->labelEx($model,'document_failure_lack'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'document_failure_lack'); ?>
                            <?php echo $form->error($model,'document_failure_lack'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'residence_zone'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'residence_zone'); ?>
                            <?php echo $form->error($model,'residence_zone'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'cep'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'cep',array('size'=>8,'maxlength'=>8)); ?>
                            <?php echo $form->error($model,'cep'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'address'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
                            <?php echo $form->error($model,'address'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'number'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'number',array('size'=>10,'maxlength'=>10)); ?>
                            <?php echo $form->error($model,'number'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'complement'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'complement',array('size'=>20,'maxlength'=>20)); ?>
                            <?php echo $form->error($model,'complement'); ?>
                        </div>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'neighborhood'); ?>
                        <div class="controls">
                            <?php echo $form->textField($model,'neighborhood',array('size'=>50,'maxlength'=>50)); ?>
                            <?php echo $form->error($model,'neighborhood'); ?>
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

                                    <div class="formField buttonWizardBar">
                    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
