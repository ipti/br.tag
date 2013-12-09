<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'instructor-documents-and-address-form',
	'enableAjaxValidation'=>false,
)); ?>
        <div class="panelGroup form">
            <?php echo $form->errorSummary($model); ?>
             <?php echo $error[0]; ?>
            <div class="panelGroupHeader"><div class=""> <?php echo $title; ?>
</div></div>
            <div class="panelGroupBody">
                <div class="panelGroupAbout">
                     <?php echo Yii::t('default', 'Fields with * are required.')?></div>

                                    <div class="formField">
                        <?php echo $form->labelEx($model,'register_type'); ?>
                        <?php echo $form->textField($model,'register_type',array('size'=>2,'maxlength'=>2,'readonly'=>'readonly')); ?>
                        <?php echo $form->error($model,'register_type'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'school_inep_id_fk'); ?>
                        <?php echo $form->textField($model,'school_inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($model,'school_inep_id_fk'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'inep_id'); ?>
                        <?php echo $form->textField($model,'inep_id',array('size'=>12,'maxlength'=>12)); ?>
                        <?php echo $form->error($model,'inep_id'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'cpf'); ?>
                        <?php echo $form->textField($model,'cpf',array('size'=>11,'maxlength'=>11)); ?>
                        <?php echo $form->error($model,'cpf'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'area_of_residence'); ?>
                        <?php echo $form->DropDownlist($model,'area_of_residence', array(1=>'URBANA', 2=>'RURAL')); ?>                    
                        <?php echo $form->error($model,'area_of_residence'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'cep'); ?>
                        <?php echo $form->textField($model,'cep',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($model,'cep'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'address'); ?>
                        <?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
                        <?php echo $form->error($model,'address'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'address_number'); ?>
                        <?php echo $form->textField($model,'address_number',array('size'=>10,'maxlength'=>10)); ?>
                        <?php echo $form->error($model,'address_number'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'complement'); ?>
                        <?php echo $form->textField($model,'complement',array('size'=>20,'maxlength'=>20)); ?>
                        <?php echo $form->error($model,'complement'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'neighborhood'); ?>
                        <?php echo $form->textField($model,'neighborhood',array('size'=>50,'maxlength'=>50)); ?>
                        <?php echo $form->error($model,'neighborhood'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'edcenso_uf_fk'); ?>
                        <?php echo $form->DropDownList($model,'edcenso_uf_fk',CHtml::listData(EdcensoUf::model()->findAll(),'id','name'),
                           array(
                             'prompt'=>'Select State',
                             'ajax'=>array(
                                'type' => 'POST',
                                'url' => CController::createUrl('instructorIdentification/getcities'),
                                'update' => '#InstructorDocumentsAndAddress_edcenso_city_fk',
                               // 'data'=>array('edcenso_uf_fk'=>'js:this.value'),
                                ))); ?>                    
                        <?php echo $form->error($model,'edcenso_uf_fk'); ?>
                    </div>

                                        <div class="formField">
                        <?php echo $form->labelEx($model,'edcenso_city_fk'); ?>
                        <?php echo $form->DropDownList($model,'edcenso_city_fk',CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk'=>$model->edcenso_uf_fk)),'id','name')); ?>                    
                        <?php echo $form->error($model,'edcenso_city_fk'); ?>
                    </div> 

                                    <div class="formField buttonWizardBar">
                    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>

<script type="text/javascript">
   var form = '#InstructorDocumentsAndAddress_';
    $(document).ready(function(){ 
       $(form+'cpf').on('change',function(){
           var isValidate = validateCpf(this.value);
              if(isValidate){
                  $(this).attr('value','');
              }
       });
       
         $(form+'cep').focusout(function() { 
            if(!validateCEP($(this).val())){
               $(this).attr('value',''); 
               $(form+'address').add().attr('disabled','disabled');
               $(form+'address_number').add().attr('disabled','disabled');
               $(form+'neighborhood').add().attr('disabled','disabled');
               $(form+'complement').add().attr('disabled','disabled');
               $(form+'edcenso_uf_fk').add().attr('disabled','disabled');
               $(form+'edcenso_city_fk').add().attr('disabled','disabled');
            }else{
                $(form+'address').val(null);
                $(form+'address').removeAttr('disabled');
                $(form+'address_number').val(null);
                $(form+'address_number').removeAttr('disabled');
                $(form+'neighborhood').val(null);
                $(form+'neighborhood').removeAttr('disabled');
                $(form+'complement').val(null);
                $(form+'complement').removeAttr('disabled');
                $(form+'edcenso_uf_fk').val(null);
                $(form+'edcenso_uf_fk').removeAttr('disabled');
                $(form+'edcenso_city_fk').val(null);
                $(form+'edcenso_city_fk').removeAttr('disabled');  
                
            } 
                
        });
        
          $(form+'address').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateInstructorAddress($(this).val())) 
                $(this).attr('value','');
        });
         $(form+'address_number').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateInstructorAddressNumber($(this).val())) 
                $(this).attr('value','');
        });
         $(form+'neighborhood').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateInstructorAddressNeighborhood($(this).val())) 
                $(this).attr('value','');
        });
        $(form+'complement').focusout(function() { 
            $(this).val($(this).val().toUpperCase());
            if(!validateInstructorAddressComplement($(this).val())) 
                $(this).attr('value','');
        });
      
//     
//edcenso_uf_fk
//edcenso_city_fk
       
        });
</script>
    
 
