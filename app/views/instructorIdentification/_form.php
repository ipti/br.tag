<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'instructor-identification-form',
	'enableAjaxValidation'=>false,
)); ?>
    
<div class="heading-buttons">
    <?php echo $form->errorSummary($model); ?>
    <h3><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.')?></span></h3>
    <div class="buttons pull-right">
        <button type="button" class="btn btn-icon btn-default glyphicons unshare"><i></i>Voltar</button>
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'btn btn-icon btn-primary glyphicons circle_ok')); ?>
    </div>
    <div class="clearfix"></div>
</div>
            
<div class="innerLR">

    <div class="widget widget-tabs border-bottom-none">

        <div class="widget-head">
                <ul>
                    <li class="active"><a class="glyphicons edit" href="#school-indentify" data-toggle="tab"><i></i>Identificação</a></li>
                    <li><a class="glyphicons settings" href="#school-structure" data-toggle="tab"><i></i>Documentos e endereços</a></li>
                    <li><a class="glyphicons imac" href="#school-equipament" data-toggle="tab"><i></i>Dados variáveis</a></li>
                    <li><a class="glyphicons parents" href="#school-humans" data-toggle="tab"><i></i>Dados de docência</a></li>
                </ul>
        </div>

        <div class="widget-body form-horizontal">

        <div class="tab-content">

            <!-- Tab content -->
            <div class="tab-pane active" id="school-indentify">
                <div class="row-fluid">
                    <div class=" span6">

                        <div class="separator"></div>

                        <div class="control-group">
                            <?php //echo $form->labelEx($model,'Tipo de Registro',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php //echo $form->textField($model,'register_type',array('size'=>2,'maxlength'=>2)); ?>
                                <?php //echo $form->error($model,'register_type'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'Código da Escola – INEP',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->textField($model,'school_inep_id_fk',array('size'=>8,'maxlength'=>8,)); ?>
                                <?php echo $form->error($model,'school_inep_id_fk'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'Identificação única do Profissional escolar em sala de Aula(INEP)',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->textField($model,'inep_id',array('size'=>12,'maxlength'=>12)); ?>
                                <?php echo $form->error($model,'inep_id'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'Nome',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
                                <?php echo $form->error($model,'name'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'email',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
                                <?php echo $form->error($model,'email'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'nis',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->textField($model,'nis',array('size'=>11,'maxlength'=>11)); ?>
                                <?php echo $form->error($model,'nis'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'birthday_date',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->textField($model,'birthday_date',array('size'=>10,'maxlength'=>10)); ?>
                                <?php echo $form->error($model,'birthday_date'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'sex',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownlist($model,'sex', array(1=>'Masculino', 2=>'Feminino')); ?>
                                <?php echo $form->error($model,'sex'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'color_race',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'color_race', array(0=>"Não Declarada",
                                1=>"Branca", 2=>"Preta",3=>"Parda",4=>"Amarela", 5=>"Indígena")); ?>
                                <?php echo $form->error($model,'color_race'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'mother_name',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->textField($model,'mother_name',array('size'=>60,'maxlength'=>100)); ?>
                                <?php echo $form->error($model,'mother_name'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'nationality',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'nationality',array(1=>"Brasileira",
                                    2=>"Brasileira nascido no Exterior ou Naturalizado",3=>"Estrangeira")); ?>
                                <?php echo $form->error($model,'nationality'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'edcenso_nation_fk',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'edcenso_nation_fk',CHtml::listData(EdcensoNation::model()->findAll(),'id','name')) ?>
                                <?php //echo $form->textField($model,'edcenso_nation_fk'); ?>
                                <?php echo $form->error($model,'edcenso_nation_fk'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'edcenso_uf_fk',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'edcenso_uf_fk',CHtml::listData(EdcensoUf::model()->findAll(),'id','name'),
                                   array(
                                     'prompt'=>'SELECT STATE',
                                     'ajax'=>array(
                                        'type' => 'POST',
                                        'url' => CController::createUrl('instructorIdentification/getcities'),
                                        'update' => '#InstructorIdentification_edcenso_city_fk',
                                       // 'data'=>array('edcenso_uf_fk'=>'js:this.value'),
                                        ))); ?>                    
                                <?php echo $form->error($model,'edcenso_uf_fk',array('class'=>'control-label')); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'edcenso_city_fk',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'edcenso_city_fk',CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk'=>$model->edcenso_uf_fk)),'id','name')); ?>                    
                                <?php echo $form->error($model,'edcenso_city_fk'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'deficiency',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->radioButton($model,'deficiency',array('value'=>'0')) . 'Não'; ?>
                                <?php echo $form->radioButton($model,'deficiency',array('value'=>'1')) . 'Sim'; ?>
                                <?php echo $form->error($model,'deficiency'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'deficiency_type_blindness',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'deficiency_type_blindness',array(0=>"Não", 1=>"Sim")); ?>
                                <?php echo $form->error($model,'deficiency_type_blindness'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'deficiency_type_low_vision',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'deficiency_type_low_vision',array(0=>"Não", 1=>"Sim")); ?>
                                <?php echo $form->error($model,'deficiency_type_low_vision'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'deficiency_type_deafness',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'deficiency_type_deafness',array(0=>"Não", 1=>"Sim")); ?>
                                <?php echo $form->error($model,'deficiency_type_deafness'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'deficiency_type_disability_hearing',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'deficiency_type_disability_hearing',array(0=>"Não", 1=>"Sim")); ?>
                                <?php echo $form->error($model,'deficiency_type_disability_hearing'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'deficiency_type_deafblindness',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'deficiency_type_deafblindness',array(0=>"Não", 1=>"Sim")); ?>
                                <?php echo $form->error($model,'deficiency_type_deafblindness'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'deficiency_type_phisical_disability',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'deficiency_type_phisical_disability',array(0=>"Não", 1=>"Sim")); ?>
                                <?php echo $form->error($model,'deficiency_type_phisical_disability'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'deficiency_type_intelectual_disability',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'deficiency_type_intelectual_disability',array(0=>"Não", 1=>"Sim")); ?>
                                <?php echo $form->error($model,'deficiency_type_intelectual_disability'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <?php echo $form->labelEx($model,'deficiency_type_multiple_disabilities',array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->DropDownList($model,'deficiency_type_multiple_disabilities',array(0=>"Não", 1=>"Sim")); ?>
                                <?php echo $form->error($model,'deficiency_type_multiple_disabilities'); ?>
                            </div>
                        </div>

                        <div class="control-group buttonWizardBar">
                        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
