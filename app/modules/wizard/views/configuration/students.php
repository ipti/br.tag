<?php
/* @var $this ClassroomConfigurationControler */
/* @var $form CActiveForm */
/* @var $title String */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/validations.js', CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'school-configuration-form',
    'enableAjaxValidation' => false
        ));

$this->breadcrumbs = array(
    Yii::t('app', 'Classroom Configurarion'),
);

$lastYear = (Yii::app()->user->year - 1);
$year = (Yii::app()->user->year);

$model = new StudentEnrollment();
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic">
            <?php echo $title; ?>
        </h3>
        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Save'), array('class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'submit')); ?>
        </div>
    </div>
</div>
<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">
        <div class="widget-head">
            <ul class="tab-sorcerer">
                <li id="tab-classroom" class="active">
                    <a class="glyphicons vcard" href="student" data-toggle="tab"> 
                        <i></i> <?php echo Yii::t('default', 'Students') . ' ' . $lastYear ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <div class="tab-pane active" id="student">
                    <div class="row-fluid">	
                        <div class=" span12">
                            <div class="control-group">
                                <?php
                                echo Yii::t('default', 'Classrooms');
                                echo chtml::dropDownList('Classrooms', "", CHtml::listData(Classroom::model()->findAll("school_year = :sy AND school_inep_fk = :si",
                                        array("sy" => (Yii::app()->user->year-1), "si"=>yii::app()->user->school)), 'id', 'name'), array(
                                    'class' => 'select-search-on span12',
                                    'multiple' => 'multiple',
                                    'placeholder' => Yii::t('default', 'Select Student'),
                                ));
                                ?> 
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class=" span5">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'classroom_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'classroom_fk', CHtml::listData(Classroom::model()->findAll("school_year = " . Yii::app()->user->year . "", array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma Turma", 'class' => 'select-search-on')); ?>
                                    <?php echo $form->error($model, 'classroom_fk'); ?>
                                </div>
                            </div>
                            <div id="multiclass">
                                <div class="control-group">
                                    <?php echo $form->labelEx($model, 'unified_class', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php echo $form->DropDownList($model, 'unified_class', array(null => "Selecione o tipo de turma infantil", "1" => "CRECHE", "2" => "PRÉ-ESCOLA"), array('class' => 'select-search-off')); ?>
                                        <?php echo $form->error($model, 'unified_class'); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <?php echo CHtml::label("Etapa", 'Stage', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php
                                        echo CHtml::dropDownList("Stage", null, array(
                                            "0" => "Selecione a Modalidade",
                                            "1" => "Infantil",
                                            "2" => "Fundamental Menor",
                                            "3" => "Fundamental Maior",
                                            "4" => "Médio",
                                            "5" => "Profissional",
                                            "6" => "EJA",
                                            "7" => "Outros",
                                                ), array(
                                            'class' => 'select-search-off',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'url' => CController::createUrl('enrollment/getmodalities'),
                                                'update' => '#StudentEnrollment_edcenso_stage_vs_modality_fk'
                                            ),
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <?php echo $form->labelEx($model, 'edcenso_stage_vs_modality_fk', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php echo $form->dropDownList($model, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), array("prompt" => "Selecione a etapa", 'class' => 'select-search-on')); ?>
                                        <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Edcenso Stage Vs Modality Fk Help'); ?>"><i></i></span>
                                        <?php echo $form->error($model, 'edcenso_stage_vs_modality_fk'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'another_scholarization_place', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'another_scholarization_place', array("3" => "Não recebe","1" => "Em hospital", "2" => "Em domicílio"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($model, 'another_scholarization_place'); ?>
                                </div>
                            </div>
                            <div class="control-group"  style="visibility: hidden;">
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
                                        "9" => "Exame de seleção, vaga reservada para alunos da rede pública de ensino, com baixa renda"), array('class' => 'select-search-off'));
                                    ?>
                                    <?php echo $form->error($model, 'student_entry_form'); ?>
                                </div>
                            </div>
                        </div>
                        <div class=" span6">
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'public_transport', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'public_transport', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($model, 'public_transport'); ?>
                                </div>
                            </div>
                            <div class="control-group" id="transport_responsable">
                                <?php echo $form->labelEx($model, 'transport_responsable_government', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'transport_responsable_government', array(null => "Selecione o poder público do transporte", "1" => "Estadual", "2" => "Municipal"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($model, 'transport_responsable_government'); ?>
                                </div>
                            </div>
                            <div class="control-group" id="transport_null">
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_van', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_microbus', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_bus', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_bike', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_animal_vehicle', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_other_vehicle', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_waterway_boat_5', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_waterway_boat_5_15', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_waterway_boat_15_35', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_waterway_boat_35', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($model, 'vehicle_type_metro_or_train', array('value' => null, 'disabled' => 'disabled')); ?>
                            </div>
                            <div class="control-group" id="transport_type">
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
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#Students").select2({width: 'resolve', maximumSelectionSize: 13, minimumInputLength: 5});
    });
    var formEnrollment = '#StudentEnrollment_';
    var updateDependenciesURL = "<?php echo yii::app()->createUrl('enrollment/updatedependencies') ?>";

</script>