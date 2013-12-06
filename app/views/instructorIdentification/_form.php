<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'instructor-identification-form',
    'enableAjaxValidation' => false,
        ));
?>
        <div class="panelGroup form">
            <?php echo $form->errorSummary($model); ?>
        <div class="panelGroupHeader"><div class=""> <?php echo $title; ?>
        </div></div>
        <div class="panelGroupBody">
        <div class="panelGroupAbout">
            <?php echo Yii::t('default', 'Fields with * are required.') ?></div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'Tipo de Registro'); ?>
            <?php echo $form->textField($model, 'register_type', array('size' => 2, 'maxlength' => 2,'readonly'=>'readonly')); ?>
            <?php echo $form->error($model, 'register_type'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'Código da Escola–INEP'); ?>
            <?php
            echo $form->DropDownList($model, 'school_inep_id_fk', CHtml::listData(
                            SchoolIdentification::model()->findAll(), 'inep_id', 'name'));
            ?>
        <?php echo $form->error($model, 'school_inep_id_fk'); ?>
            </div>

        <div class="formField">
        <?php echo $form->labelEx($model, 'Identificação única do Profissional escolar em sala de Aula(INEP)'); ?>
        <?php echo $form->textField($model, 'inep_id', array('size' => 12, 'maxlength' => 12), array('readonly' => 'readonly')); ?>
        <?php echo $form->error($model, 'inep_id'); ?>
        </div> 

        <div class="formField">
            <?php echo $form->labelEx($model, 'Nome'); ?>
            <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'nis'); ?>
            <?php echo $form->textField($model, 'nis', array('size' => 11, 'maxlength' => 11)); ?>
            <?php echo $form->error($model, 'nis'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'birthday_date'); ?>
            <?php echo $form->textField($model, 'birthday_date', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'birthday_date'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'sex'); ?>
            <?php echo $form->DropDownlist($model, 'sex', array(1 => 'Masculino', 2 => 'Feminino')); ?>
            <?php echo $form->error($model, 'sex'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'color_race'); ?>
            <?php
            echo $form->DropDownList($model, 'color_race', array(0 => "Não Declarada",
                1 => "Branca", 2 => "Preta", 3 => "Parda", 4 => "Amarela", 5 => "Indígena"));
            ?>
            <?php echo $form->error($model, 'color_race'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'mother_name'); ?>
            <?php echo $form->textField($model, 'mother_name', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'mother_name'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'nationality'); ?>
            <?php
            echo $form->DropDownList($model, 'nationality', array(1 => "Brasileira",
                2 => "Brasileira nascido no Exterior ou Naturalizado", 3 => "Estrangeira"));
            ?>
            <?php echo $form->error($model, 'nationality'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'edcenso_nation_fk'); ?>
            <?php echo $form->DropDownList($model, 'edcenso_nation_fk', CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name ASC')), 'id', 'name'),
                    array('options'=> array(76 => array('selected'=>true))
                        ,'disabled'=>'true')); ?>

            
            <?php //echo $form->textField($model,'edcenso_nation_fk');  ?>
            <?php echo $form->error($model, 'edcenso_nation_fk'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'edcenso_uf_fk'); ?>
            <?php
            echo $form->DropDownList($model, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(), 'id', 'name'), array(
                'prompt' => 'Select State',
                'ajax' => array(
                    'type' => 'POST',
                    'url' => CController::createUrl('instructorIdentification/getcities'),
                    'update' => '#InstructorIdentification_edcenso_city_fk',
                // 'data'=>array('edcenso_uf_fk'=>'js:this.value'),
                    )));
            ?>                    
            <?php echo $form->error($model, 'edcenso_uf_fk'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'edcenso_city_fk'); ?>
            <?php echo $form->DropDownList($model, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $model->edcenso_uf_fk)), 'id', 'name')); ?>                    
            <?php echo $form->error($model, 'edcenso_city_fk'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'deficiency'); ?>
            <?php echo CHtml::activeCheckBox($model,'deficiency'); ?>

            <?php //echo $form->DropDownList($model, 'deficiency', array(0 => "Não", 1 => "Sim")); ?>
            <?php echo $form->error($model, 'deficiency'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'deficiency_type_blindness'); ?>
            <?php echo CHtml::activeCheckBox($model,'deficiency_type_blindness', array('readonly'=>true)); ?>
            <?php //echo $form->DropDownList($model, 'deficiency_type_blindness', array(0 => "Não", 1 => "Sim"),array('disabled'=>'disabled')); ?>
            <?php echo $form->error($model, 'deficiency_type_blindness'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'deficiency_type_low_vision'); ?>
            <?php echo CHtml::activeCheckBox($model,'deficiency_type_low_vision', array('readonly'=>true)); ?>
            <?php // echo $form->DropDownList($model, 'deficiency_type_low_vision', array(0 => "Não", 1 => "Sim"),array('disabled'=>'disabled')); ?>
            <?php echo $form->error($model, 'deficiency_type_low_vision'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'deficiency_type_deafness'); ?>
            <?php echo CHtml::activeCheckBox($model,'deficiency_type_deafness', array('readonly'=>true)); ?>
            <?php //echo $form->DropDownList($model, 'deficiency_type_deafness', array(0 => "Não", 1 => "Sim"),array('disabled'=>'disabled')); ?>
            <?php echo $form->error($model, 'deficiency_type_deafness'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'deficiency_type_disability_hearing'); ?>
            <?php echo CHtml::activeCheckBox($model,'deficiency_type_disability_hearing', array('readonly'=>true)); ?>
            <?php //echo $form->DropDownList($model, 'deficiency_type_disability_hearing', array(0 => "Não", 1 => "Sim"),array('disabled'=>'disabled')); ?>
            <?php echo $form->error($model, 'deficiency_type_disability_hearing'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'deficiency_type_deafblindness'); ?>
            <?php echo CHtml::activeCheckBox($model,'deficiency_type_deafblindness', array('readonly'=>true)); ?>
            <?php // echo $form->DropDownList($model, 'deficiency_type_deafblindness', array(0 => "Não", 1 => "Sim"),array('disabled'=>'disabled')); ?>
            <?php echo $form->error($model, 'deficiency_type_deafblindness'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'deficiency_type_phisical_disability'); ?>
            <?php echo CHtml::activeCheckBox($model,'deficiency_type_phisical_disability', array('readonly'=>true)); ?>
            <?php //echo $form->DropDownList($model, 'deficiency_type_phisical_disability', array(0 => "Não", 1 => "Sim"),array('disabled'=>'disabled')); ?>
            <?php echo $form->error($model, 'deficiency_type_phisical_disability'); ?>
        </div>

        <div class="formField">
            <?php echo $form->labelEx($model, 'deficiency_type_intelectual_disability'); ?>
            <?php echo CHtml::activeCheckBox($model,'deficiency_type_intelectual_disability', array('readonly'=>true)); ?>
            <?php //echo $form->DropDownList($model, 'deficiency_type_intelectual_disability', array(0 => "Não", 1 => "Sim"),array('disabled'=>'disabled')); ?>
            <?php echo $form->error($model, 'deficiency_type_intelectual_disability'); ?>
        </div>

        <div class="formField">
            <?php //echo $form->labelEx($model, 'deficiency_type_multiple_disabilities'); ?>
            <?php //echo $form->DropDownList('', array(0 => "Não", 1 => "Sim"),array('disabled'=>'disabled')); ?>
<!--                <select id="dt_multiple_disabilities" disabled="disabled">
                   <option value="0">Não</option> 
                   <option value="1">Sim</option> 
                </select>-->
                <?php echo $form->hiddenField($model, 'deficiency_type_multiple_disabilities'); ?>
                <?php echo $form->error($model, 'deficiency_type_multiple_disabilities'); ?>
        </div>

        <div class="formField buttonWizardBar">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'), array('class' => 'buttonLink button')); ?>
        </div>
            <?php $this->endWidget(); ?>
    </div>
</div>

<script type="text/javascript">
    var form = '#InstructorIdentification_';
    $(document).ready(function(){
        $(form+'birthday_date').mask("99/99/9999");
        
        $(form +'name,'+form +'mother_name').on('focusout', function(){
            $(this).val($(this).val().toUpperCase());
            var isValidate = validateNamePerson(this.value);
              if(!isValidate[0]){
                  $(this).attr('value','');
              }
          })
           $(form+'email').on('focusout', function(){
            $(this).val($(this).val().toUpperCase());
            if(!validateEmail($(this).val())) 
                $(this).attr('value','');
        });
        
          $(form+'birthday_date').on('focusout', function(){
            $(this).val($(this).val().toUpperCase());
            if(!validadeBirthdayPerson(this.value)) 
                $(this).attr('value','');
        });
        
        $(form+'nationality').on('change', function(){
            if($(this).val() == 3) { // ESTRANGEIRO
                $(form+'edcenso_nation_fk').removeAttr('disabled');
            }else{
                $(form+'edcenso_nation_fk').val(76);
                $(form+'edcenso_nation_fk').add().attr('disabled','disabled');
            }
            
            if($(this).val() != 1) {
                $(form+'edcenso_uf_fk').add().attr('disabled','disabled');
                $(form+'edcenso_uf_fk').val('');
                $(form+'edcenso_city_fk').add().attr('disabled','disabled');
                $(form+'edcenso_city_fk').val('');
            }else{
                $(form+'edcenso_uf_fk').removeAttr('disabled'); 
                $(form+'edcenso_city_fk').removeAttr('disabled');
            }
                
        });
        
        $(form+'deficiency').on('change', function(){
            if($(this).val()==1) { 
                $(form+'deficiency_type_blindness').removeAttr('readonly');
                $(form+'deficiency_type_low_vision').removeAttr('readonly');
                $(form+'deficiency_type_deafness').removeAttr('readonly');
                $(form+'deficiency_type_disability_hearing').removeAttr('readonly');
                $(form+'deficiency_type_deafblindness').removeAttr('readonly');
                $(form+'deficiency_type_phisical_disability').removeAttr('readonly');
                $(form+'deficiency_type_intelectual_disability').removeAttr('readonly');
                
            }
        }); 
        
       $(form+'deficiency_type_blindness').on('change', function(){
          if(($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1) 
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_deafness').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_phisical_disability').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
      ) {
            $(form+'deficiency_type_multiple_disabilities').val(1);
      }else{
            $(form+'deficiency_type_multiple_disabilities').val(0);
      }         
               
            if($(this).val() == 1) { // 
                $(form+'deficiency_type_low_vision').val(0);
                $(form+'deficiency_type_low_vision').add().attr('readonly','readonly');
                $(form+'deficiency_type_deafness').val(0);
                $(form+'deficiency_type_deafness').add().attr('readonly','readonly');
                $(form+'deficiency_type_deafblindness').val(0);
                $(form+'deficiency_type_deafblindness').add().attr('readonly','readonly');
                
            }else{
                $(form+'deficiency_type_low_vision').removeAttr('readonly');
                $(form+'deficiency_type_deafness').removeAttr('readonly');
                $(form+'deficiency_type_deafblindness').removeAttr('readonly'); 
                
                            
            }
        });
        
        $(form+'deficiency_type_low_vision').on('change', function(){
            if(($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1) 
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_deafness').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_phisical_disability').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
      ) {
            $(form+'deficiency_type_multiple_disabilities').val(1);
      }else{
            $(form+'deficiency_type_multiple_disabilities').val(0);
      }   
            if($(this).val() == 1) { // 
                $(form+'deficiency_type_blindness').val(0);
                $(form+'deficiency_type_blindness').add().attr('readonly','readonly');
                $(form+'deficiency_type_deafblindness').val(0);
                $(form+'deficiency_type_deafblindness').add().attr('readonly','readonly');  
            }else{
                $(form+'deficiency_type_blindness').removeAttr('readonly');
                $(form+'deficiency_type_deafblindness').removeAttr('readonly');
                           
            }
        });
        
        $(form+'deficiency_type_deafness').on('change', function(){
            if(($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1) 
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_deafness').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_phisical_disability').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
      ) {
            $(form+'deficiency_type_multiple_disabilities').val(1);
      }else{
            $(form+'deficiency_type_multiple_disabilities').val(0);
      }   
            if($(this).val() == 1) { // 
                $(form+'deficiency_type_blindness').val(0);
                $(form+'deficiency_type_blindness').add().attr('readonly','readonly');
                $(form+'deficiency_type_disability_hearing').val(0);
                $(form+'deficiency_type_disability_hearing').add().attr('readonly','readonly');
                $(form+'deficiency_type_deafblindness').val(0);
                $(form+'deficiency_type_deafblindness').add().attr('readonly','readonly');   
            }else{
                $(form+'deficiency_type_blindness').removeAttr('readonly');
                $(form+'deficiency_type_disability_hearing').removeAttr('readonly');      
                $(form+'deficiency_type_deafblindness').removeAttr('readonly');
                
            }
        });
        
           $(form+'deficiency_type_disability_hearing').on('change', function(){
            if(($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1) 
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_deafness').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_phisical_disability').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
      ) {
            $(form+'deficiency_type_multiple_disabilities').val(1);
      }else{
            $(form+'deficiency_type_multiple_disabilities').val(0);
      }      
            if($(this).val() == 1) { // 
                $(form+'deficiency_type_deafness').val(0);
                $(form+'deficiency_type_deafness').add().attr('readonly','readonly');
                $(form+'deficiency_type_disability_hearing').val(0);
                $(form+'deficiency_type_disability_hearing').add().attr('readonly','readonly');
                $(form+'deficiency_type_deafblindness').val(0);
                $(form+'deficiency_type_deafblindness').add().attr('readonly','readonly');
                $(form+'deficiency_type_blindness').val(0);
                $(form+'deficiency_type_blindness').add().attr('readonly','readonly'); 
               
            }else{
                $(form+'deficiency_type_deafness').removeAttr('readonly');    
                $(form+'deficiency_type_deafblindness').removeAttr('readonly');
                $(form+'deficiency_type_deafness').removeAttr('readonly');    
                $(form+'deficiency_type_deafblindness').removeAttr('readonly');
                
                
                
            }
        }); 
            $(form+'deficiency_type_deafblindness').on('change', function(){
            if(($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1) 
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_deafness').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_phisical_disability').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
      ) {
            $(form+'deficiency_type_multiple_disabilities').val(1);
      }else{
            $(form+'deficiency_type_multiple_disabilities').val(0);
      }       
            
            if($(this).val() == 1) { // 
                $(form+'deficiency_type_blindness').val(0);
                $(form+'deficiency_type_blindness').add().attr('readonly','readonly');
                $(form+'deficiency_type_low_vision').val(0);
                $(form+'deficiency_type_low_vision').add().attr('readonly','readonly');
                $(form+'deficiency_type_deafness').val(0);
                $(form+'deficiency_type_deafness').add().attr('readonly','readonly');
                $(form+'deficiency_type_disability_hearing').val(0);
                $(form+'deficiency_type_disability_hearing').add().attr('readonly','readonly');  
            }else{
                $(form+'deficiency_type_blindness').removeAttr('readonly');    
                $(form+'deficiency_type_low_vision').removeAttr('readonly'); 
                $(form+'deficiency_type_deafness').removeAttr('readonly');    
                $(form+'deficiency_type_disability_hearing').removeAttr('readonly');
               
            }
        });
    
       $(form+'deficiency_type_phisical_disability').on('change', function(){
           if(($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1) 
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_deafness').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_phisical_disability').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
      ) {
            $(form+'deficiency_type_multiple_disabilities').val(1);
      }else{
            $(form+'deficiency_type_multiple_disabilities').val(0);
      }    
       });
       
          $(form+'deficiency_type_intelectual_disability').on('change', function(){
              if(($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1) 
            || ($(form+'deficiency_type_blindness').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_disability_hearing').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_phisical_disability').val()==1)
            || ($(form+'deficiency_type_deafblindness').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_deafness').val()==1)
            || ($(form+'deficiency_type_low_vision').val()==1 && $(form+'deficiency_type_disability_hearing').val()==1)
            || ($(form+'deficiency_type_phisical_disability').val()==1 && $(form+'deficiency_type_intelectual_disability').val()==1)
      ) {
            $(form+'deficiency_type_multiple_disabilities').val(1);
      }else{
            $(form+'deficiency_type_multiple_disabilities').val(0);
      }    
          });
    
        
//        $('#dt_multiple_disabilities').on('change', function(){
//            $(form+'deficiency_type_multiple_disabilities').val($(this).val());
//        });
//        
//          $(form+'deficiency_type_multiple_disabilities').on('change', function(){
//            $('#dt_multiple_disabilities').val($(this).val());
//        });
        
        
      
        
        
        
    });
    
    
 
    
</script>

