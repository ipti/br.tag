<?php

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/validations.js?v=1.0', CClientScript::POS_END);
//@done S1 - 15 - A matricula precisa estar atribuida a um ano letivo, senão ela fica atemporal.
$form = $this->beginWidget('CActiveForm', [
    'id' => 'student-enrollment-form',
    'enableAjaxValidation' => false,
]);
?>


<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?>
            <span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></h3>
        <div class="buttons">
            <button class="btn btn-icon btn-primary last glyphicons circle_ok save-enrollment"
                    type="button">
                <i></i> <?= $model->isNewRecord ? Yii::t('default', 'Enroll') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>
</div>

<div class="innerLR">

    <div class="widget widget-tabs border-bottom-none">

        <?php echo $form->errorSummary($model); ?>
        <div class="alert alert-error enrollment-error no-show"></div>
        <div class="widget-head">
            <ul>
                <li class="active"><a class="glyphicons edit" href="#enrollment"
                                      data-toggle="tab"><i></i><?php echo Yii::t('default', 'Enrollment') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">

            <div class="tab-content">

                <!-- Tab content -->
                <div class="tab-pane active" id="enrollment">
                    <div class="row-fluid">
                        <div class=" span5">

                            <div class="control-group">
                                <?php
                                //@done S1 -  18 - Primeiro seleciona a etapa dae faz um filtro nas turma disponiveis para aquela etapa.
                                echo $form->labelEx($model, 'classroom_fk', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php

                                    $isAdmin = Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id);
                                    $classrooms = $isAdmin ? Classroom::model()->findAll('school_year = ' . Yii::app()->user->year . ' order by name') : Classroom::model()->findAll('school_year = ' . Yii::app()->user->year . ' and school_inep_fk = ' . Yii::app()->user->school . ' order by name');

                                    echo $form->dropDownList($model, 'classroom_fk', CHtml::listData($classrooms, 'id', 'name', 'schoolInepFk.name'), ['class' => 'select-search-on']);
                                    echo $form->error($model, 'classroom_fk');

                                    ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'unified_class', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'unified_class', [null => 'Selecione o tipo de turma infantil', '1' => 'CRECHE', '2' => 'PRÉ-ESCOLA'], ['class' => 'select-search-off']); ?>
                                    <?php echo $form->error($model, 'unified_class'); ?>
                                </div>
                            </div>

                            <?php //@done s1 - criar campo de selecionar o Stage
                            //@done s1 - alterar banco para suprir a necessidade do filtro por Stage
                            //@done s1 - criar requisição ajax para filtrar a modalidade por Stage?>
                            <div class="control-group">
                                <?php echo CHtml::label('Etapa', 'Stage', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php echo CHtml::dropDownList('Stage', null, [
                                        '0' => 'Selecione a Modalidade',
                                        '1' => 'Infantil',
                                        '2' => 'Fundamental Menor',
                                        '3' => 'Fundamental Maior',
                                        '4' => 'Médio',
                                        '5' => 'Profissional',
                                        '6' => 'EJA',
                                        '7' => 'Outros',
                                    ], [
                                        'class' => 'select-search-off',
                                        'ajax' => [
                                            'type' => 'POST',
                                            'url' => CController::createUrl('enrollment/getmodalities'),
                                            'success' => 'function(data){
                                                $("#StudentEnrollment_edcenso_stage_vs_modality_fk").html(decodeHtml(data));
                                            }'
                                        ],
                                    ]); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'edcenso_stage_vs_modality_fk', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), ['prompt' => 'Selecione a etapa', 'class' => 'select-search-on']); ?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark"
                                          data-toggle="tooltip" data-placement="top"
                                          data-original-title="<?php echo Yii::t('help', 'Edcenso Stage Vs Modality Fk Help'); ?>"><i></i></span>
                                    <?php echo $form->error($model, 'edcenso_stage_vs_modality_fk'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'admission_type', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'admission_type', ['1' => 'Rematrícula', '2' => 'Transferência interna', '3' => 'Transferência externa'], ['prompt' => 'Selecione', 'class' => 'select-search-off']); ?>
                                    <?php echo $form->error($model, 'admission_type'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'status', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'status', ['1' => 'Matriculado', '2' => 'Transferido', '3' => 'Cancelado', '4' => 'Evadido'], ['options' => ['1' => ['selected' => true]], 'prompt' => 'Selecione', 'class' => 'select-search-off']); ?>
                                    <?php echo $form->error($model, 'status'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'another_scholarization_place', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($model, 'another_scholarization_place', ['1' => 'Não recebe', '2' => 'Em hospital', '3' => 'Em Domicílio'], ['class' => 'select-search-off']); ?>
                                    <?php echo $form->error($model, 'another_scholarization_place'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'current_stage_situation', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $model,
                                        'current_stage_situation',
                                        [
                                            null => 'Selecione',
                                            '0' => 'Primeira matrícula no curso',
                                            '1' => 'Promovido na série anterior do mesmo curso',
                                            '2' => 'Repetente'
                                        ],
                                        ['class' => 'select-search-off']
                                    ); ?>
                                    <?php echo $form->error($model, 'current_stage_situation'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'previous_stage_situation', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList(
                                        $model,
                                        'previous_stage_situation',
                                        [
                                            null => 'Selecione',
                                            '0' => 'Não frequentou',
                                            '1' => 'Reprovado',
                                            '2' => 'Afastado por transferência',
                                            '3' => 'Afastado por abandono',
                                            '4' => 'Matrícula final em Educação Infantil',
                                            '5' => 'Promovido'
                                        ],
                                        ['class' => 'select-search-off']
                                    ); ?>
                                    <?php echo $form->error($model, 'previous_stage_situation'); ?>
                                </div>
                            </div>

                            <div class="control-group" style="visibility: hidden;">
                                <?php echo $form->labelEx($model, 'student_entry_form', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($model, 'student_entry_form', [null => 'Selecione a forma de ingresso do aluno',
                                        '1' => 'Sem processo seletivo',
                                        '2' => 'Sorteio',
                                        '3' => 'Transferência',
                                        '4' => 'Exame de seleção sem reserva de vaga',
                                        '5' => 'Exame de seleção, vaga reservada para alunos da rede pública de ensino',
                                        '6' => 'Exame de seleção, vaga reservada para alunos da rede pública de ensino, com baixa renda e autodeclarado preto, pardo ou indígena',
                                        '7' => 'Exame de seleção, vaga reservada para outros programas de ação afirmativa',
                                        '8' => 'Outra forma de ingresso',
                                        '9' => 'Exame de seleção, vaga reservada para alunos da rede pública de ensino, com baixa renda'], ['class' => 'select-search-off']);
                                    ?>
                                    <?php echo $form->error($model, 'student_entry_form'); ?>
                                </div>
                            </div>

                        </div>
                        <div class=" span6">
                            <div class="separator"></div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'school_admission_date', ['class' => 'control-label']); ?>
                                <div class="controls">
                                    <?php echo $form->textField($model, 'school_admission_date', ['size' => 10, 'maxlength' => 10]); ?>
                                    <?php echo $form->error($model, 'school_admission_date'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($model, 'public_transport', ['class' => 'control-label required']); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($model, 'public_transport', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    <?php echo $form->error($model, 'public_transport'); ?>
                                </div>
                            </div>
                            <div class="control-group" id="transport_responsable">
                                <?php echo $form->labelEx($model, 'transport_responsable_government', ['class' => 'control-label required']); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($model, 'transport_responsable_government', [null => 'Selecione o poder público do transporte', '1' => 'Estadual', '2' => 'Municipal'], ['class' => 'select-search-off']); ?>
                                    <?php echo $form->error($model, 'transport_responsable_government'); ?>
                                </div>
                            </div>
                            <div class="control-group hide-responsive" id="transport_type">
                                <label class="control-label required"><?php echo Yii::t('default', 'Transport Type'); ?>
                                    *</label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_van']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_van', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_microbus']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_microbus', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bus']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_bus', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bike']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_bike', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_animal_vehicle']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_animal_vehicle', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_other_vehicle']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_other_vehicle', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_5', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5_15']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_5_15', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_15_35']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_15_35', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_35']; ?>
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_35', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group" id="">
                                <label class="control-label"><?php echo Yii::t('default', 'Type of Specialized Educational Assistance'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_cognitive_functions']; ?>
                                        <?php echo $form->checkBox($model, 'aee_cognitive_functions', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_autonomous_life']; ?>
                                        <?php echo $form->checkBox($model, 'aee_autonomous_life', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_curriculum_enrichment']; ?>
                                        <?php echo $form->checkBox($model, 'aee_curriculum_enrichment', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_accessible_teaching']; ?>
                                        <?php echo $form->checkBox($model, 'aee_accessible_teaching', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_libras']; ?>
                                        <?php echo $form->checkBox($model, 'aee_libras', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_portuguese']; ?>
                                        <?php echo $form->checkBox($model, 'aee_portuguese', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_soroban']; ?>
                                        <?php echo $form->checkBox($model, 'aee_soroban', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_braille']; ?>
                                        <?php echo $form->checkBox($model, 'aee_braille', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_mobility_techniques']; ?>
                                        <?php echo $form->checkBox($model, 'aee_mobility_techniques', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_caa']; ?>
                                        <?php echo $form->checkBox($model, 'aee_caa', ['value' => 1, 'uncheckValue' => 0]); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo $model->attributeLabels()['aee_optical_nonoptical']; ?>
                                        <?php echo $form->checkBox($model, 'aee_optical_nonoptical', ['value' => 1, 'uncheckValue' => 0]); ?>
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

<?php
if (isset($_GET['censo']) && isset($_GET['id'])) {
                                        $this->widget('application.widgets.AlertCensoWidget', ['prefix' => 'enrollment', 'dataId' => $_GET['id']]);
                                    }
?>

<script type="text/javascript">

    var formEnrollment = '#StudentEnrollment_';
    var updateDependenciesURL = '<?php echo yii::app()->createUrl('enrollment/updatedependencies')?>';

</script>
