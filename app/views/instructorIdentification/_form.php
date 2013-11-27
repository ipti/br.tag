<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'instructor-identification-form',
	'enableAjaxValidation'=>false,
)); ?>
        <div class="panelGroup form">
            <?php echo $form->errorSummary($model); ?>
            <div class="panelGroupHeader"><div class=""> <?php echo $title; ?>
</div></div>
            <div class="panelGroupBody">
                <div class="panelGroupAbout">
                     <?php echo Yii::t('default', 'Fields with * are required.')?></div>

                                    <div class="formField">
                        <?php echo $form->labelEx($model,'Tipo de Registro'); ?>
                        <?php echo $form->textField($model,'register_type',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo $form->error($model,'register_type'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'Código da Escola
–
INEP'); ?>
                        <?php echo $form->textField($model,'school_inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($model,'school_inep_id_fk'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'Identificação única do Profissional escolar em sala de Aula(INEP)'); ?>
                        <?php echo $form->textField($model,'inep_id',array('size'=>12,'maxlength'=>12)); ?>
                        <?php echo $form->error($model,'inep_id'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'Nome'); ?>
                        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
                        <?php echo $form->error($model,'name'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'email'); ?>
                        <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
                        <?php echo $form->error($model,'email'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'nis'); ?>
                        <?php echo $form->textField($model,'nis',array('size'=>11,'maxlength'=>11)); ?>
                        <?php echo $form->error($model,'nis'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'birthday_date'); ?>
                        <?php echo $form->textField($model,'birthday_date',array('size'=>10,'maxlength'=>10)); ?>
                        <?php echo $form->error($model,'birthday_date'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'sex'); ?>
                        <?php //echo $form->textField($model,'sex'); ?>
                        <?php echo $form->DropDownlist($model,'sex', array(1=>'Masculino', 2=>'Feminino')); ?>
                        <?php echo $form->error($model,'sex'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'color_race'); ?>
                        <?php echo $form->DropDownList($model,'color_race', array(0=>"Não Declarada",
1=>"Branca", 2=>"Preta",3=>"Parda",4=>"Amarela", 5=>"Indígena")); ?>
                        <?php echo $form->error($model,'color_race'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'mother_name'); ?>
                        <?php echo $form->textField($model,'mother_name',array('size'=>60,'maxlength'=>100)); ?>
                        <?php echo $form->error($model,'mother_name'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'nationality'); ?>
                        <?php echo $form->DropDownList($model,'nationality',array(1=>"Brasileira",
                            2=>"Brasileira nascido no Exterior ou Naturalizado",3=>"Estrangeira")); ?>
                        <?php echo $form->error($model,'nationality'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'edcenso_nation_fk'); ?>
                        <?php echo $form->DropDownList($model,'edcenso_nation_fk',CHtml::listData(EdcensoNation::model()->findAll(),'id','name')) ?>
                        <?php //echo $form->textField($model,'edcenso_nation_fk'); ?>
                        <?php echo $form->error($model,'edcenso_nation_fk'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'edcenso_uf_fk'); ?>
                        <?php echo $form->DropDownList($model,'edcenso_uf_fk',CHtml::listData(EdcensoUf::model()->findAll(),'id','name'),
                           array(
                             'prompt'=>'SELECT STATE',
                             'ajax'=>array(
                                'type' => 'POST',
                                'url' => CController::createUrl('instructorIdentification/getcities'),
                                'update' => '#InstructorIdentification_edcenso_city_fk',
                               // 'data'=>array('edcenso_uf_fk'=>'js:this.value'),
                                ))); ?>                    
                        <?php echo $form->error($model,'edcenso_uf_fk'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'edcenso_city_fk'); ?>
                        <?php echo $form->DropDownList($model,'edcenso_city_fk',CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk'=>$model->edcenso_uf_fk)),'id','name')); ?>                    
                        <?php echo $form->error($model,'edcenso_city_fk'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'deficiency'); ?>
                        <?php echo $form->DropDownList($model,'deficiency',array(0=>"Não", 1=>"Sim")); ?>
                        <?php echo $form->error($model,'deficiency'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'deficiency_type_blindness'); ?>
                        <?php echo $form->DropDownList($model,'deficiency_type_blindness',array(0=>"Não", 1=>"Sim")); ?>
                        <?php echo $form->error($model,'deficiency_type_blindness'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'deficiency_type_low_vision'); ?>
                        <?php echo $form->DropDownList($model,'deficiency_type_low_vision',array(0=>"Não", 1=>"Sim")); ?>
                        <?php echo $form->error($model,'deficiency_type_low_vision'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'deficiency_type_deafness'); ?>
                        <?php echo $form->DropDownList($model,'deficiency_type_deafness',array(0=>"Não", 1=>"Sim")); ?>
                        <?php echo $form->error($model,'deficiency_type_deafness'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'deficiency_type_disability_hearing'); ?>
                        <?php echo $form->DropDownList($model,'deficiency_type_disability_hearing',array(0=>"Não", 1=>"Sim")); ?>
                        <?php echo $form->error($model,'deficiency_type_disability_hearing'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'deficiency_type_deafblindness'); ?>
                        <?php echo $form->DropDownList($model,'deficiency_type_deafblindness',array(0=>"Não", 1=>"Sim")); ?>
                        <?php echo $form->error($model,'deficiency_type_deafblindness'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'deficiency_type_phisical_disability'); ?>
                        <?php echo $form->DropDownList($model,'deficiency_type_phisical_disability',array(0=>"Não", 1=>"Sim")); ?>
                        <?php echo $form->error($model,'deficiency_type_phisical_disability'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'deficiency_type_intelectual_disability'); ?>
                        <?php echo $form->DropDownList($model,'deficiency_type_intelectual_disability',array(0=>"Não", 1=>"Sim")); ?>
                        <?php echo $form->error($model,'deficiency_type_intelectual_disability'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'deficiency_type_multiple_disabilities'); ?>
                        <?php echo $form->DropDownList($model,'deficiency_type_multiple_disabilities',array(0=>"Não", 1=>"Sim")); ?>
                        <?php echo $form->error($model,'deficiency_type_multiple_disabilities'); ?>
                    </div>

                                    <div class="formField buttonWizardBar">
                    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
