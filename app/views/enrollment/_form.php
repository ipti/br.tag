<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'student-enrollment-form',
    'enableAjaxValidation' => false,
        ));
?>

<div class="heading-buttons">
    <?php echo $form->errorSummary($model); ?>
    <h3><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></span></h3>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>

<div class="innerLR">

    <div class="widget widget-tabs border-bottom-none">

        <div class="widget-head">
            <ul>
                <li class="active"><a class="glyphicons edit" href="#enrollment" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Enrollment') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">

            <div class="tab-content">

                <!-- Tab content -->
                <div class="tab-pane active" id="classroom">
                    <div class="row-fluid">
                        <div class=" span5">

                            <div class="separator"></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'school_inep_id_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($model, 'school_inep_id_fk', CHtml::listData(SchoolIdentification::model()->findAll(array('order' => 'name')), 'inep_id', 'name'), array("prompt" => "Selecione uma Escola",
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('enrollment/updatedependencies'),
                                            'success' => "function(data){
                                                data = jQuery.parseJSON(data);
                                                $('#StudentEnrollment_student_fk').html( data.Students);
                                                $('#StudentEnrollment_classroom_fk').html(data.Classrooms);
                                            }",
                                            )));
                                    ?>
                                    <?php echo $form->error($model, 'school_inep_id_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'student_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($model, 'student_fk', CHtml::listData(StudentIdentification::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione um Aluno"));
                                    ?> 
                                    <?php echo $form->error($model, 'student_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'classroom_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'classroom_fk', CHtml::listData(Classroom::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma Turma"));
                                    ?>
                                    <?php echo $form->error($model, 'classroom_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'unified_class', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'unified_class', array(null => "Selecione o tipo de turma infantil", "1" => "CRECHE", "2" => "PRÉ-ESCOLA")); ?>
                                    <?php echo $form->error($model, 'unified_class'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'edcenso_stage_vs_modality_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), array("prompt" => "Selecione a etapa"));
                                    ?>
                                    <?php echo $form->error($model, 'edcenso_stage_vs_modality_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'another_scholarization_place', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'another_scholarization_place', array(null => "Selecione o espaço", "1" => "Em hospital", "2" => "Em domicílio", "3" => "Não recebe")); ?>
                                    <?php echo $form->error($model, 'another_scholarization_place'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'public_transport', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'public_transport', array(null => "Selecione se utiliza transporte", "0" => "Não utiliza", "1" => "Utiliza")); ?>
                                    <?php echo $form->error($model, 'public_transport'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'transport_responsable_government', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'transport_responsable_government', array(null => "Selecione o poder público do transporte", "1" => "Estadual", "2" => "Municipal")); ?>
                                    <?php echo $form->error($model, 'transport_responsable_government'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_van', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_van', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_van'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_microbus', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_microbus', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_microbus'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_bus', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_bus', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_bus'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_bike', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_bike', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_bike'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_animal_vehicle', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_animal_vehicle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_animal_vehicle'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_other_vehicle', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_other_vehicle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_other_vehicle'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_waterway_boat_5', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_5', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_waterway_boat_5'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_waterway_boat_5_15', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_5_15', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_waterway_boat_5_15'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_waterway_boat_15_35', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_15_35', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_waterway_boat_15_35'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_waterway_boat_35', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_35', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_waterway_boat_35'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'vehicle_type_metro_or_train', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'vehicle_type_metro_or_train', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'vehicle_type_metro_or_train'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'student_entry_form', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($model, 'student_entry_form', array(null => "Selecione a forma de ingresso do aluno",
                                        "1" => "Sem processo seletivo",
                                        "2" => "Sorteio",
                                        "3" => "Transferência",
                                        "4" => "Exame de seleção sem reserva de vaga",
                                        "5" => "Exame de seleção, vaga reservada para alunos da rede pública de ensino",
                                        "6" => "Exame de seleção, vaga reservada para alunos da rede pública de ensino, com baixa renda e autodeclarado preto, pardo ou indígena",
                                        "7" => "Exame de seleção, vaga reservada para outros programas de ação afirmativa",
                                        "8" => "Outra forma de ingresso",
                                        "9" => "Exame de seleção, vaga reservada para alunos da rede pública de ensino, com baixa renda"));
                                    ?>
                                    <?php echo $form->error($model, 'student_entry_form'); ?>
                                </div>
                            </div>

                            <div class="formField buttonWizardBar">
                                <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'), array('class' => 'btn btn-icon btn-primary glyphicons circle_ok')); ?>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    var form = '#StudentEnrollment_';
    jQuery(function($) {
        jQuery('body').on('change','#StudentEnrollment_school_inep_id_fk',
        function(){jQuery.ajax({
                'type':'POST',
                'url':'/tag/index.php?r=enrollment/updatedependencies',
                'cache':false,
                'data':jQuery(this).parents("form").serialize(),
                'success':function(data){
                    data = jQuery.parseJSON(data);
                    $('#StudentEnrollment_student_fk').html(data.Students);
                    $('#StudentEnrollment_classroom_fk').html(data.Classrooms);
                }
            });
            return false;
        }
    );
        $(form+'school_inep_id_fk').trigger('change');
        
        
    }); 
</script>