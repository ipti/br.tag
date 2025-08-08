<?php

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/validations.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
//@done S1 - 15 - A matricula precisa estar atribuida a um ano letivo, senão ela fica atemporal.
$form = $this->beginWidget('CActiveForm', [
    'id' => 'student-enrollment-form',
    'enableAjaxValidation' => false,
]);
?>


<div class="row" style="margin-left: 20px;">
    <div class="span12">
        <h1><?php echo $title; ?>
            <span> | <?php echo Yii::t('default', 'Fields with * are required.') ?>
        </h1>
        <div class="tag-buttons-container buttons hide-responsive">
            <button class="t-button-primary last  save-enrollment" type="button">
                <?= $model->isNewRecord ? Yii::t('default', 'Enroll') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>
</div>

<div class="tag-inner">

    <div class="widget widget-tabs border-bottom-none">

        <?php echo $form->errorSummary($model); ?>
        <?php if (Yii::app()->user->hasFlash('error')) : ?>
            <div class="alert alert-error">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
        <?php endif ?>
        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div class="alert alert-error enrollment-error no-show"></div>
        <h1 style="margin-left: 20px;"><?php echo $model->studentFk->name ?></h1>
        <div class="t-tabs">
            <ul class="t-tabs__list">
                <li class="t-tabs__item active"><a href="#enrollment" class="t-tabs__link" data-toggle="tab">
                        <span class="t-tabs__numeration">1</span>
                        <?php echo Yii::t('default', 'Enrollment') ?></a>
                </li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">

            <div class="tab-content form-content">

                <!-- Tab content -->
                <div class="tab-pane active" id="enrollment">
                    <div class="row">
                        <div class="column">

                            <div class="t-field-select">
                                <?php
                                //@done S1 -  18 - Primeiro seleciona a etapa dae faz um filtro nas turma disponiveis para aquela etapa.
                                echo $form->labelEx($model, 'classroom_fk', ['class' => 't-field-select__label']); ?>

                                <?php
                                $options = [];
foreach ($classrooms as $classroom) {
    $options[$classroom->id] = [
        'data-isMulti' => (int)(TagUtils::isMultiStage(
            $classroom->edcenso_stage_vs_modality_fk
        ))
    ];
}
echo $form->dropDownList(
    $model,
    'classroom_fk',
    CHtml::listData($classrooms, 'id', 'name', 'schoolInepFk.name'),
    [
        'class' => 'select-search-on t-field-select__input',
        'style' => 'width:100%',
        'options' => $options, // Adiciona os atributos data
    ]
);
echo $form->error($model, 'classroom_fk');

?>

                            </div>
                            <div class="t-field-select">
                                <?php echo $form->labelEx($model, 'unified_class', ['class' => 't-field-select__label']); ?>
                                <?php echo $form->DropDownList(
    $model,
    'unified_class',
    [
        null => 'Selecione o tipo de turma infantil',
        '1' => 'CRECHE',
        '2' => 'PRÉ-ESCOLA',
        '3' => 'NÃO POSSUI'
    ],
    ['class' => 'select-search-off t-field-select__input', 'style' => 'width:100%']
); ?>
                                <?php echo $form->error($model, 'unified_class'); ?>
                            </div>

                            <?php //@done s1 - criar campo de selecionar o Stage
                                                                                            //@done s1 - alterar banco para suprir a necessidade do filtro por Stage
                                                                                            //@done s1 - criar requisição ajax para filtrar a modalidade por Stage
                                                                                            ?>
                            <div class="t-field-select">
                                <?php echo CHtml::label('Etapa', 'Stage', ['class' => 't-field-select__label']); ?>

                                <?php echo CHtml::dropDownList('Stage', $model->edcensoStageVsModalityFk->stage, [
                                    '0' => 'Selecione a Modalidade',
                                    '1' => 'Infantil',
                                    '2' => 'Fundamental Menor',
                                    '3' => 'Fundamental Maior',
                                    '4' => 'Médio',
                                    '5' => 'Profissional',
                                    '6' => 'EJA',
                                    '7' => 'Outros',
                                ], [
                                    'class' => 'select-search-off t-field-select__input',
                                    'ajax' => [
                                        'type' => 'POST',
                                        'url' => CController::createUrl('enrollment/getmodalities'),
                                        'success' => 'function(data){
                                                $("#StudentEnrollment_edcenso_stage_vs_modality_fk").html(decodeHtml(data));
                                            }'
                                    ],
                                    'style' => 'width:100%'
                                ]); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($model, 'edcenso_stage_vs_modality_fk', ['class' => 't-field-select__label']); ?>
                                <?php echo $form->dropDownList($model, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), ['prompt' => 'Selecione a etapa', 'class' => 'select-search-on t-field-select__input']); ?>
                                <!--  <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark"
                                          data-toggle="tooltip" data-placement="top"
                                          data-original-title="<?php echo Yii::t('help', 'Edcenso Stage Vs Modality Fk Help'); ?>"><i></i></span> -->
                                <?php echo $form->error($model, 'edcenso_stage_vs_modality_fk'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($model, 'admission_type', ['class' => 't-field-select__label']); ?>
                                <?php echo $form->DropDownList($model, 'admission_type', ['1' => 'Rematrícula', '2' => 'Transferência interna', '3' => 'Transferência externa'], ['prompt' => 'Selecione', 'class' => 't-field-select__input select-search-off', 'style' => 'width:100%']); ?>
                                <?php echo $form->error($model, 'admission_type'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($model, 'status', ['class' => 't-field-select__label']); ?>
                                <?php echo $form->DropDownList($model, 'status', StudentEnrollment::getListStatus(), ['options' => ['1' => ['selected' => true]], 'prompt' => 'Selecione', 'class' => 'select-search-off t-field-select__input', 'style' => 'width:100%']); ?>
                                <?php echo $form->error($model, 'status'); ?>
                            </div>

                            <?php if (Yii::app()->features->isEnable('FEAT_SEDSP')): ?>
                                <div class="t-field-select" id="reasonDropdown" style="display: none;">
                                    <label for="reason">Tipo da baixa</label>
                                    <select name="reason" id="reason" class="select-search-off t-field-select__input" style="width:100%">
                                        <option value="0">Selecione um tipo</option>
                                        <option value="1">Baixa por Transferência</option>
                                        <option value="2">Não Comparecimento</option>
                                        <option value="3">Abandono (Evasão)</option>
                                        <option value="6">Cessão por objetivos atingidos</option>
                                    </select>
                                </div>
                                <div id="secondDropdown" style="display: none;">
                                    <label for="secondReason">Motivo da transferência:</label>
                                    <select name="secondReason" id="secondReason" class="select-search-off t-field-select__input" style="width:100%">
                                        <option value="1">Mudança de residência (Exceto para matrículas na educação básica)</option>
                                        <option value="2">Mudança para outra escola particular/publica</option>
                                        <option value="3">Mudança para outro estado</option>
                                        <option value="4">Mudança para outro país</option>
                                        <option value="5">Mudança para escola da rede particular</option>
                                        <option value="6">Melhor ajustamento escolar</option>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($model, 'another_scholarization_place', ['class' => 't-field-select__label']); ?>
                                <?php echo $form->DropDownList($model, 'another_scholarization_place', ['1' => 'Não recebe', '2' => 'Em hospital', '3' => 'Em Domicílio'], ['class' => 't-field-select__input select-search-off']); ?>
                                <?php echo $form->error($model, 'another_scholarization_place'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($model, 'current_stage_situation', ['class' => 't-field-select__label']); ?>
                                <?php echo $form->DropDownList(
                                                                    $model,
                                                                    'current_stage_situation',
                                                                    [
                                                                        null => 'Selecione',
                                                                        '0' => 'Primeira matrícula no curso',
                                                                        '1' => 'Promovido na série anterior do mesmo curso',
                                                                        '2' => 'Repetente'
                                                                    ],
                                                                    ['class' => 't-field-select__input select-search-off']
                                                                ); ?>
                                <?php echo $form->error($model, 'current_stage_situation'); ?>
                            </div>

                            <div class="t-field-select">
                                <?php echo $form->labelEx($model, 'previous_stage_situation', ['class' => 't-field-select__label']); ?>
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
                                    ['class' => 't-field-select__input select-search-off']
                                ); ?>
                                <?php echo $form->error($model, 'previous_stage_situation'); ?>
                            </div>

                            <div class="t-field-select" style="visibility: hidden;">
                                <?php echo $form->labelEx($model, 'student_entry_form', ['class' => 't-field-select__label']); ?>
                                <?php
                                echo $form->DropDownList($model, 'student_entry_form', [
                                    null => 'Selecione a forma de ingresso do aluno',
                                    '1' => 'Sem processo seletivo',
                                    '2' => 'Sorteio',
                                    '3' => 'Transferência',
                                    '4' => 'Exame de seleção sem reserva de vaga',
                                    '5' => 'Exame de seleção, vaga reservada para alunos da rede pública de ensino',
                                    '6' => 'Exame de seleção, vaga reservada para alunos da rede pública de ensino, com baixa renda e autodeclarado preto, pardo ou indígena',
                                    '7' => 'Exame de seleção, vaga reservada para outros programas de ação afirmativa',
                                    '8' => 'Outra forma de ingresso',
                                    '9' => 'Exame de seleção, vaga reservada para alunos da rede pública de ensino, com baixa renda'
                                ], ['class' => 'select-search-off t-field-select__input']);
?>
                                <?php echo $form->error($model, 'student_entry_form'); ?>
                            </div>

                        </div>
                        <div class="column">
                            <div class="t-field-text">
                                <?php echo $form->label($model, 'enrollment_date', ['class' => 't-field-text__label--required']); ?>
                                <?php
$this->widget('zii.widgets.jui.CJuiDatePicker', DatePickerWidget::renderDatePicker($model, 'enrollment_date'));
echo $form->error($model, 'enrollment_date');
?>
                            </div>

                            <!--  Data de transferência externa na escola -->
                            <div id="transferDiv" class="hide">
                                <div class="t-field-text js-hide-not-required">
                                    <?php echo $form->label($model, 'class_transfer_date', ['class' => 't-field-text__label']); ?>
                                    <?php echo $form->textField($model, 'class_transfer_date', ['size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input']); ?>
                                    <?php echo $form->error($model, 'class_transfer_date'); ?>
                                </div>
                            </div>

                            <!--  Data de rematrícula na escola -->
                            <div id="readmissionDiv" class="hide">
                                <div class="t-field-text js-hide-not-required">
                                    <?php echo $form->label($model, 'school_readmission_date', ['class' => 't-field-text__label']); ?>
                                    <?php echo $form->textField($model, 'school_readmission_date', ['size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input']); ?>
                                    <?php echo $form->error($model, 'school_readmission_date'); ?>
                                </div>
                            </div>

                            <div class="t-field-checkbox">
                                <?php echo $form->checkBox($model, 'public_transport', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                <?php echo $form->error($model, 'public_transport'); ?>
                                <?php echo $form->labelEx($model, 'public_transport', ['class' => 't-field-checkbox__label--required required']); ?>

                            </div>
                            <div class="t-field-select" id="transport_responsable">
                                <?php echo $form->labelEx($model, 'transport_responsable_government', ['class' => 't-field-select__label required']); ?>
                                <?php echo $form->dropDownList($model, 'transport_responsable_government', [null => 'Selecione o poder público do transporte', '1' => 'Estadual', '2' => 'Municipal'], ['class' => 'select-search-off t-field-select__input']); ?>
                                <?php echo $form->error($model, 'transport_responsable_government'); ?>
                            </div>
                            <div class="control-group hide-responsive" id="transport_type">
                                <label class="t-field-checkbox__label--required required"><?php echo Yii::t('default', 'Transport Type'); ?>
                                </label>
                                <div class="uniformjs t-field-checkbox-group">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'vehicle_type_van', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label" for="StudentEnrollment_vehicle_type_van">
                                            <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_van']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'vehicle_type_microbus', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label" for="StudentEnrollment_vehicle_type_microbus">
                                            <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_microbus']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'vehicle_type_bus', ['value' => 1, 'uncheckValue' => 0, 'class="t-field-checkbox"__input']); ?>
                                        <label class="t-field-checkbox__label" for="StudentEnrollment_vehicle_type_bus">
                                            <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bus']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'vehicle_type_bike', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label" for="StudentEnrollment_vehicle_type_bike">
                                            <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bike']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'vehicle_type_animal_vehicle', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label" for="StudentEnrollment_vehicle_type_animal_vehicle">
                                            <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_animal_vehicle']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'vehicle_type_other_vehicle', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label" for="StudentEnrollment_vehicle_type_other_vehicle">
                                            <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_other_vehicle']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_5', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label" for="StudentEnrollment_vehicle_type_waterway_boat_5">
                                            <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_5_15', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label" for="StudentEnrollment_vehicle_type_waterway_boat_5_15">
                                            <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5_15']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_15_35', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label" for="StudentEnrollment_vehicle_type_waterway_boat_15_35">
                                            <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_15_35']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'vehicle_type_waterway_boat_35', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label" for="StudentEnrollment_vehicle_type_waterway_boat_35">
                                            <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_35']; ?>
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="t-field-checkbox-group" id="">
                                <label class="t-field-checkbox__label"><?php echo Yii::t('default', 'Type of Specialized Educational Assistance'); ?></label>
                                <div class="uniformjs">
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_cognitive_functions', ['value' => 1, 'uncheckValue' => 0, 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_cognitive_functions']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_autonomous_life', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_autonomous_life']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_curriculum_enrichment', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_curriculum_enrichment']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_accessible_teaching', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_accessible_teaching']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_libras', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_libras']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_portuguese', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_portuguese']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_soroban', ['value' => 1, 'uncheckValue' => 0,  'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_soroban']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_braille', ['value' => 1, 'uncheckValue' => 0,  'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_braille']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_mobility_techniques', ['value' => 1, 'uncheckValue' => 0,  'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_mobility_techniques']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_caa', ['value' => 1, 'uncheckValue' => 0,  'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_caa']; ?>
                                        </label>
                                    </div>
                                    <div class="t-field-checkbox">
                                        <?php echo $form->checkBox($model, 'aee_optical_nonoptical', ['value' => 1, 'uncheckValue' => 0,  'class' => 't-field-checkbox__input']); ?>
                                        <label class="t-field-checkbox__label">
                                            <?php echo $model->attributeLabels()['aee_optical_nonoptical']; ?>
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="t-field-tarea js-hide-not-required">
                                <?php echo $form->labelEx($model, 'observation', ['class' => 't-field-tarea__label control-label']); ?>
                                <?php echo $form->textArea($model, 'observation', ['rows' => 6, 'cols' => 50, 'class' => 't-field-tarea__input']); ?>
                                <?php echo $form->error($model, 'observation'); ?>
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
    var updateDependenciesURL = '<?php echo Yii::app()->createUrl('enrollment/updatedependencies') ?>';
</script>