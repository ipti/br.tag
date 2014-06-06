<?php 

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/_initialization.js', CClientScript::POS_END);

//@done S1 - 15 - A matricula precisa estar atribuida a um ano letivo, senão ela fica atemporal.
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-enrollment-form',
	'enableAjaxValidation'=>false,
)); ?>


<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></h3>  
        <div class="buttons">
                    <!--//@done S1 - 19 - O nome do botão é matricular e não criar-->
                    <?php echo CHtml::htmlButton('<i></i>' . ($model->isNewRecord ? Yii::t('default', 'Enroll') : Yii::t('default', 'Save')),
                                array('class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'submit'));?>
                    <?php //echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Enroll') : Yii::t('default', 'Save'), array('class' => 'btn btn-icon btn-primary next')); ?>
        </div>
    </div>
</div>

<div class="innerLR">

    <div class="widget widget-tabs border-bottom-none">

        <?php echo $form->errorSummary($model); ?>
        <div class="widget-head">
            <ul>
                <li class="active"><a class="glyphicons edit" href="#enrollment" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Enrollment') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">

            <div class="tab-content">

                <!-- Tab content -->
                <div class="tab-pane active" id="enrollment">
                    <div class="row-fluid">
                        <div class=" span5">

                            <div class="control-group">
                                <div class="controls">
                                    <?php
                                    //@done S1 - 07 - Remover campo
                                    echo $form->hiddenField($model,'school_inep_id_fk',array('value'=>Yii::app()->user->school));
                                    ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'student_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    //@done S1 - 07 - 16 - Pode ser implementada uma busca neste dropdown, selecionar de uma lista fica dificil
                                    //@done S1 - 17 - Precisa estar em ordem alfabetica
                                    //@done S1 - 10 - 20 - Melhorar o desempenho da criação dos alunos
                                    //@done S1 - 05 - Resolver problema de pane no sistema com matrícula duplicada(urgente)
                                    //@done S1 - 10 - Traduzir mensagens de sucesso edição e falha
                                    //@done S1 - Remover a busca de todos os dropdowns
                                    echo $form->dropDownList($model, 'student_fk', CHtml::listData(StudentIdentification::model()->findAll(array('order' => 'name ASC')), 'id', 'name'), array("prompt" => "Selecione um Aluno","class" => "select-search-on"));
                                    ?> 
                                    <?php echo $form->error($model, 'student_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php 
                                //@done S1 -  18 - Primeiro seleciona a etapa dae faz um filtro nas turma disponiveis para aquela etapa.
                                echo $form->labelEx($model, 'classroom_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'classroom_fk', CHtml::listData(Classroom::model()->findAll("school_year = ".Yii::app()->user->year."", array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma Turma", 'class'=>'select-search-on'));
                                    ?>
                                    <?php echo $form->error($model, 'classroom_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'unified_class', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'unified_class', array(null => "Selecione o tipo de turma infantil", "1" => "CRECHE", "2" => "PRÉ-ESCOLA"),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($model, 'unified_class'); ?>
                                </div>
                            </div>
                            
                            <?php //@done s1 - criar campo de selecionar o Stage
                                //@done s1 - alterar banco para suprir a necessidade do filtro por Stage
                                //@done s1 - criar requisição ajax para filtrar a modalidade por Stage?>
                            <div class="control-group">
                                <?php echo CHtml::label("Etapa", 'Stage', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo CHtml::dropDownList("Stage", null, array(
                                            "0" => "Selecione a Modalidade", 
                                            "1" => "Infantil",    
                                            "2" => "Fundamental Menor",
                                            "3" => "Fundamental Maior",
                                            "4" => "Médio",
                                            "5" => "Profissional",
                                            "6" => "EJA",
                                            "7" => "Outros",
                                        ),array(
                                            'class' => 'select-search-off',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'url' => CController::createUrl('enrollment/getmodalities'),
                                                'update' => '#StudentEnrollment_edcenso_stage_vs_modality_fk'
                                            ),
                                            )); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'edcenso_stage_vs_modality_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), array("prompt" => "Selecione a etapa",'class'=>'select-search-on'));?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Edcenso Stage Vs Modality Fk Help'); ?>"><i></i></span>
                                    <?php echo $form->error($model, 'edcenso_stage_vs_modality_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'another_scholarization_place', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'another_scholarization_place', array(null => "Selecione o espaço", "1" => "Em hospital", "2" => "Em domicílio", "3" => "Não recebe"),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($model, 'another_scholarization_place'); ?>
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
                                        "9" => "Exame de seleção, vaga reservada para alunos da rede pública de ensino, com baixa renda"),array('class' => 'select-search-off'));
                                    ?>
                                    <?php echo $form->error($model, 'student_entry_form'); ?>
                                </div>
                            </div>

                        </div>
                        <div class=" span6">
                        <div class="separator"></div>
                        
                            <div class="control-group">
                                <?php 
                                echo $form->labelEx($model, 'public_transport', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'public_transport', array(null => "Selecione se utiliza transporte", "0" => "Não utiliza", "1" => "Utiliza"),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($model, 'public_transport'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'transport_responsable_government', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'transport_responsable_government', array(null => "Selecione o poder público do transporte", "1" => "Estadual", "2" => "Municipal"),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($model, 'transport_responsable_government'); ?>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo Yii::t('default', 'Transport Type'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_van']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_van', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_microbus']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_microbus', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bus']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_bus', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bike']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_bike', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_animal_vehicle']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_animal_vehicle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_other_vehicle']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_other_vehicle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_5', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5_15']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_5_15', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_15_35']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_15_35', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_35']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_35', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_metro_or_train']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_metro_or_train', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span style="clear:both;display:block"></span>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    var form = '#StudentEnrollment_';
    var baseUrl = "<?php echo Yii::app()->baseUrl; ?>";
    
</script>
