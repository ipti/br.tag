<?php

/**
 * @var StudentController $this StudentController
 * @var CActiveForm $form CActiveForm
 * @var $cs CClientScript
 * @var StudentIdentification $modelStudentIdentification StudentIdentification
 * @var StudentDocumentsAndAddress $modelStudentDocumentsAndAddress StudentDocumentsAndAddress
 *
 */
/* @var $modelStudentIdentification /app/models/StudentIdentification */
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/student/form/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/student/form/validations.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/student/form/pagination.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/student/form/datepicker-pt-BR.js?v=' . TAG_VERSION, CClientScript::POS_END);

$cs->registerScriptFile($baseUrl . '/js/enrollment/form/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/validations.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);

$cs->registerScript("", '
    var sedspEnable = ' . Yii::app()->features->isEnable("FEAT_SEDSP") . '
    sedspEnable = sedspEnable || false;
', CClientScript::POS_HEAD);


$form = $this->beginWidget(
  'CActiveForm',
  array(
    'id' => 'student',
    'enableAjaxValidation' => false,
  )
);
/**
 * @var CActiveForm $form CActiveForm
 */
?>


<div class="mobile-row ">
  <div class="column clearleft">
    <?php
    if (!$modelStudentIdentification->isNewRecord && Yii::app()->features->isEnable("FEAT_SEDSP")):
      $sedspSync = StudentIdentification::model()->findByPk($modelStudentIdentification->id)->sedsp_sync;
      ?>
      <div style="display: flex;align-items: center;margin-right: 10px;margin-top: 13px;">
        <?php if ($sedspSync): ?>
          <div style="font-weight: bold;margin-right: 20px;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/SyncTrue.png"
              style="width: 25px; margin-right: 2px;">Sincronizado
          </div>
        <?php else: ?>
          <div style="font-weight: bold;margin-right: 20px;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/notSync.png" style="width: 25px;margin-right: 2px;">Não
            sincronizado
          </div>
        <?php endif; ?>

        <a class="update-student-from-sedsp"
          style="margin-right: 10px;background: #2e33b7;color: white;font-size: 13px;padding-left: 4px;padding-right: 4px;border-radius: 6px;cursor: pointer">
          Importar dados da SED
        </a>
      </div>
    <?php endif; ?>
    <h1><?php echo $title; ?></h1>
  </div>
  <div class="column clearfix align-items--center justify-content--end show--desktop">
    <a data-toggle="tab" class='hide-responsive t-button-secondary prev'
      style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
    <?= $modelStudentIdentification->isNewRecord ? "<a data-toggle='tab' class='t-button-primary  next'>" . Yii::t('default', 'Next') . "</a>" : '' ?>

    <button class="t-button-primary  last save-student" type="button">
      <?= $modelStudentIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
    </button>
  </div>
</div>

<div class="tag-inner">
  <div class="widget widget-tabs border-bottom-none">
    <?php
    echo $form->errorSummary($modelStudentIdentification);
    echo $form->errorSummary($modelStudentDocumentsAndAddress);
    ?>
    <?php if (Yii::app()->user->hasFlash('success') && (!$modelClassroom->isNewRecord)) { ?>
      <div class="alert student-alert alert-success">
        <?php echo Yii::app()->user->getFlash('success') ?>
      </div>
    <?php } elseif (Yii::app()->user->hasFlash('error') && (!$modelClassroom->isNewRecord)) { ?>
      <div class="alert student-alert alert-error">
        <?php echo Yii::app()->user->getFlash('error') ?>
      </div>
    <?php } else { ?>
      <div class="alert student-alert no-show"></div>
    <?php } ?>
    <div class="t-tabs js-tab-control">
      <ul class="tab-student t-tabs__list">
        <li id="tab-student-identify" class="t-tabs__item active">
          <a class="t-tabs__link first" href="#student-identify" data-toggle="tab">
            <span class="t-tabs__numeration">1</span>
            <!-- <?php echo Yii::t('default', 'Identification') ?> -->
            <?php echo Yii::t('default', 'Student Data') ?>
          </a>
          <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
        </li>
        <li id="tab-student-affiliation" class="t-tabs__item ">
          <a class="t-tabs__link" href="#student-affiliation" data-toggle="tab">
            <span class="t-tabs__numeration">2</span>
            <?php echo Yii::t('default', 'Affiliation') ?>
          </a>
          <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
        </li>
        <li id="tab-student-documents" class="t-tabs__item ">
          <a class="t-tabs__link" href="#student-documents" data-toggle="tab">
            <span class="t-tabs__numeration">3</span>
            <!-- <?php echo Yii::t('default', 'Documents') ?> -->
            <?php echo Yii::t('default', 'Social Data') ?>
          </a>
          <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
        </li>
        <li id="tab-student-address" class="t-tabs__item ">
          <a class="t-tabs__link" href="#student-address" data-toggle="tab">
            <span class="t-tabs__numeration js-change-number-3">4</span>
            <?php echo Yii::t('default', 'Address') ?>
          </a>
          <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
        </li>
        <li id="tab-student-enrollment" class="t-tabs__item ">
          <a class="t-tabs__link" href="#student-enrollment" data-toggle="tab">
            <span class="t-tabs__numeration js-change-number-4">5</span>
            <?php echo Yii::t('default', 'Enrollment') ?>
          </a>
          <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
        </li>
        <li id="tab-student-health" class="t-tabs__item ">
          <a class="t-tabs__link" href="#student-health" data-toggle="tab">
            <span class="t-tabs__numeration js-change-number-5">6</span>
            <?php echo Yii::t('default', 'Health') ?>
          </a>
        </li>
      </ul>
    </div>
    <div>
      <div class="tab-content" style="display:none">
        <!-- Tab content Botão de próximo -->
        <!-- Tab Dados do aluno -->
        <div class="tab-pane active" id="student-identify">
          <div class="row">
            <h3>
              Dados Básicos
            </h3>
          </div>
          <!-- Nome -->
          <div class="row">
            <!-- name social -->
            <div class="column clearleft is-two-fifths">
              <?php echo $form->hiddenField($modelStudentIdentification, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>
              <!-- name student -->
              <div class="t-field-text" id="nameStudents">
                <?php echo $form->label($modelStudentIdentification, 'name', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'name',
                  array(
                    'size' => 60,
                    'maxlength' => 100,
                    'class' => 't-field-text__input',
                    'placeholder' => 'Digite o Nome de Apresentação'
                  )
                ); ?>
                <span id="similarMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                  <img id="warningNameIcon" onclick="displayRecords()" style="display: none;"
                    src="<?php echo $themeUrl . '/img/warning-icon.svg' ?>" alt="icone aviso">
                  <img id="errorNameIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>"
                    alt="icone erro">
                </span>
                <?php echo $form->error($modelStudentIdentification, 'name'); ?>
              </div>
            </div>
            <!-- Nome civil -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-checkbox js-hide-not-required" id="show-student-civil-name-box">
                <input type="checkbox" class="t-field-checkbox__input" id="show-student-civil-name" <?php if ($modelStudentIdentification->civil_name != null) {
                  echo "checked";
                } ?>>
                <label class="t-field-checkbox__label">Esse é um nome social?</label>
              </div>
              <div class="t-field-text student-civil-name" id="civilName" style="display: none;">
                <?php echo $form->label($modelStudentIdentification, 'civil_name', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'civil_name',
                  array(
                    'size' => 60,
                    'maxlength' => 100,
                    'class' => 't-field-text__input',
                    'placeholder' => 'Digite o Nome Civil'
                  )
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'civil_name'); ?>
              </div>
            </div>
          </div>
          <!-- Aniversário e CPF -->
          <div class="row">
            <!-- Aniversário -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text" id="dateOfBirth">
                <?php echo $form->label($modelStudentIdentification, 'birthday', array('class' => 't-field-text__label--required')); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', DatePickerWidget::renderDatePicker($modelStudentIdentification, 'birthday'));
                echo CHtml::link('	Limpar', '#', array(
                  'onclick' => '$("#' . CHtml::activeId($modelStudentIdentification, 'birthday') . '").datepicker("setDate", null); return false;',
                ));
                echo $form->error($modelStudentIdentification, 'birthday');
                ?>
              </div>
            </div>
            <!-- CPF -->
            <div class="column clearleft--on-mobile is-two-fifths">
                <div class="t-field-text js-hide-not-required" id="show-student-cpf-box" style="display:flex; flex-direction:row; gap:0 0.5rem;">
                    <input type="checkbox" class="t-field-checkbox__input" id="show-cpf-reason"
                        <?php if ($modelStudentDocumentsAndAddress->cpf != null) { echo "checked"; } ?>>
                    <label class="t-field-checkbox__label">Aluno possui CPF?</label>
                </div>
                <div class="t-field-text" id="cpfStudents" style="display: none;">
                    <?php echo $form->label($modelStudentDocumentsAndAddress, 'cpf', array('class' => 't-field-text__label')); ?>
                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cpf', array('size' => 11, 'maxlength' => 14, "class" => "t-field-text__input")); ?>
                    <span id="cpfMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                    <img id="errorCPFIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>"
                        alt="icone erro">
                    </span>
                    <?php if ($modelStudentDocumentsAndAddress->hasErrors('cpf')): ?>
                    <div style='margin-top: 5px;color: red;'>
                        <?= CHtml::encode($modelStudentDocumentsAndAddress->getError('cpf')); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="t-field-text" id="cpfReasonStudents" style="display: block;">
                    <?= $form->label($modelStudentDocumentsAndAddress, 'cpf_reason', array('class' => 't-field-text__label')); ?>
                    <?php echo $form->DropDownList(
                    $modelStudentDocumentsAndAddress,
                    'cpf_reason',
                    array(null => "Selecione uma justificativa", 1 => "Pais não entregaram o documento do aluno na unidade escolar", 2 => "Impedimento judicial", 3=>"Aluno não possui documento CPF"),
                    array('class' => 'select-search-off t-field-select__input select2-container')
                    ); ?>
                    <?php if ($modelStudentDocumentsAndAddress->hasErrors('cpf_reason')): ?>
                    <div style='margin-top: 5px;color: red;'>
                        <?= CHtml::encode($modelStudentDocumentsAndAddress->getError('cpf_reason')); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
          </div>
          <!-- Sexo e Raça -->
          <div class="row">
            <!-- Sexo -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select" id="gender-select">
                <?php echo $form->label($modelStudentIdentification, 'sex', array('class' => 't-field-select__label--required')); ?>
                <?php echo $form->DropDownList(
                  $modelStudentIdentification,
                  'sex',
                  array(null => "Selecione o sexo", "1" => "Masculino", "2" => "Feminino"),
                  array('class' => 'select-search-off t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'sex'); ?>
              </div>
            </div>
            <!-- Raça -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select" id="color">
                <?php echo $form->label($modelStudentIdentification, 'color_race', array('class' => 't-field-select__label--required')); ?>
                <?php
                echo $form->DropDownList($modelStudentIdentification, 'color_race', array(
                  null => "Selecione a cor/raça",
                  "0" => "Não declarada",
                  "1" => "Branca",
                  "2" => "Preta",
                  "3" => "Parda",
                  "4" => "Amarela",
                  "5" => "Indígena"
                ), array('class' => 'select-search-off t-field-select__input select2-container'));
                ?>
                <?php echo $form->error($modelStudentIdentification, 'color_race'); ?>
              </div>
            </div>
          </div>
          <!-- Nacionalidade e País de origem-->
          <div class="row">
            <!-- Nacionalidade  -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select" id="nationality-select">
                <?= $form->label($modelStudentIdentification, 'nationality', array('class' => 't-field-select__label--required')); ?>
                <?=
                  $form->dropDownList(
                    $modelStudentIdentification,
                    'nationality',
                    array(null => "Selecione a nacionalidade", "1" => "Brasileira", "2" => "Brasileira: Nascido no exterior ou Naturalizado", "3" => "Estrangeira"),
                    array('class' => 'select-search-off t-field-select__input select2-container'),
                    array(
                      'ajax' => array(
                        'type' => 'POST',
                        'url' => CController::createUrl('student/getnations'),
                        'update' => '#StudentIdentification_edcenso_nation_fk'
                      )
                    )
                  );
                ?>
                <?php echo $form->error($modelStudentIdentification, 'nationality'); ?>
              </div>
            </div>
            <!-- País de origem -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select">
                <?php echo $form->label($modelStudentIdentification, 'edcenso_nation_fk', array('class' => 't-field-select__label--required')); ?>
                <?php
                echo $form->dropDownList(
                  $modelStudentIdentification,
                  'edcenso_nation_fk',
                  CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name')), 'id', 'name'),
                  array("prompt" => "Selecione uma nação", 'class' => 'select-search-on nationality-sensitive no-br t-field-select__input select2-container', 'disabled' => 'disabled')
                );
                ?>
                <?php echo $form->error($modelStudentIdentification, 'edcenso_nation_fk'); ?>
              </div>
            </div>
          </div>
          <!-- Estado e Cidade -->
          <div class="row">
            <!-- Estado -->
            <div class="column clearleft is-two-fifths" id="state-select">
              <div class="t-field-select js-hide-not-required js-change-required">
                <?php echo $form->label($modelStudentIdentification, 'edcenso_uf_fk', array('class' => 't-field-select__label--required')); ?>
                <?php
                echo $form->dropDownList(
                  $modelStudentIdentification,
                  'edcenso_uf_fk',
                  CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'),
                  array(
                    'ajax' => array(
                      'type' => 'POST',
                      'url' => CController::createUrl('student/getcities', array('rt' => 0)),
                      'update' => '#StudentIdentification_edcenso_city_fk'
                    ),
                    "prompt" => "Selecione um estado",
                    "class" => "select-search-on nationality-sensitive br t-field-select__input select2-container",
                    "disabled" => "disabled",
                  )
                );
                ?>
                <?php echo $form->error($modelStudentIdentification, 'edcenso_uf_fk'); ?>
              </div>
            </div>
            <!-- Cidade -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select js-hide-not-required js-change-required" id="city-select">
                <?php echo $form->label($modelStudentIdentification, 'edcenso_city_fk', array('class' => 't-field-select__label--required')); ?>
                <?php
                echo $form->dropDownList(
                  $modelStudentIdentification,
                  'edcenso_city_fk',
                  CHtml::listData(EdcensoCity::model()->findAllByAttributes(
                    array('edcenso_uf_fk' => $modelStudentIdentification->edcenso_uf_fk),
                    array('order' => 'name')
                  ), 'id', 'name'),
                  array(
                    "prompt" => "Selecione uma cidade",
                    "disabled" => "disabled",
                    'class' => 'select-search-on nationality-sensitive br t-field-select__input select2-container'
                  )
                );
                ?>
                <?php echo $form->error($modelStudentIdentification, 'edcenso_city_fk'); ?>
              </div>
            </div>
          </div>
          <!-- Email e escolaridade -->
          <div class="row">
            <!-- Email -->
            <div class="column clearleft is-two-fifths">
              <div class=" t-field-text js-hide-not-required" id="email">
                <?php echo $form->label($modelStudentIdentification, 'id_email', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'id_email',
                  array('size' => 60, 'maxlength' => 255, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Email')
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'id_email'); ?>
              </div>
            </div>
            <!-- Escolaridade -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select js-hide-not-required" id="scholarity-select">
                <?php echo CHtml::label("Escolaridade", 'scholarity', array('class' => 't-field-select__label')); ?>
                <?php
                echo $form->dropDownList(
                  $modelStudentIdentification,
                  'scholarity',
                  array(
                    "0" => "Selecione a escolaridade",
                    "1" => "Formação Geral",
                    "2" => "Modalidade Normal (Magistério)",
                    "3" => "Curso Técnico",
                    "4" => "Magistério Indígena Modalidade Normal",
                    "5" => "Ensino Fundamental",
                  ),
                  array(
                    'class' => 'select-search-off t-field-select__input select2-container'
                  )
                );
                ?>
                <?php echo $form->error($modelStudentIdentification, 'scholarity'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- Deficiência -->
            <div class="column clearleft is-two-fifths">
              <div class=" t-field-text js-hide-not-required" id="deficiency">
                <h3>
                  Deficiência
                </h3>
                <!-- Deficiência -->
                <div class="t-field-checkbox" id="deficiency-checkbox">
                  <?php echo $form->checkBox($modelStudentIdentification, 'deficiency', ['value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input']); ?>
                  <?php echo $form->label($modelStudentIdentification, 'deficiency', ['class' => 'control-label t-field-checkbox__label', 'label' => 'Possui deficiência']); ?>
                  <?php echo $form->error($modelStudentIdentification, 'deficiency'); ?>
                </div>

              </div>
              <!-- Tipos de deficiência -->
              <div id="StudentIdentification_deficiencies"
                class="t-field-checkbox-group control-group deficiencies-container js-change-required js-visibility-deficiencies">
                <label class="t-field-checkbox__label--required"><?php echo Yii::t('default', 'Deficiency Type'); ?>
                </label>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_blindness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_blindness']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_low_vision', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_low_vision']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafness']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_disability_hearing', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_disability_hearing']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafblindness', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafblindness']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_phisical_disability', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_phisical_disability']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_intelectual_disability', array('value' => 1, 'uncheckValue' => 0, 'class' => 'linked-deficiency')); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_intelectual_disability']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_autism', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_autism']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_gifted', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_gifted']; ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="column clearleft is-two-fifths">
              <div class=" t-field-text js-hide-not-required" style="margin-left: 12px;">
                <!-- Recursos requeridos em avaliações do INEP (Prova Brasil, SAEB, outros) -->
                <div class="t-field-checkbox-group js-visibility-dresource hide-responsive resources-container"
                  id="resources-checkbox">
                  <label class="t-field-checkbox__label"><?php echo Yii::t('default', 'Required Resources'); ?></label>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_aid_lector', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_aid_lector']; ?>
                    </label>
                  </div>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_aid_transcription', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_aid_transcription']; ?>
                    </label>
                  </div>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_interpreter_guide', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_interpreter_guide']; ?>
                    </label>
                  </div>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_interpreter_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_interpreter_libras']; ?>
                    </label>
                  </div>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_lip_reading', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_lip_reading']; ?>
                    </label>
                  </div>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_18', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_18']; ?>
                    </label>
                  </div>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_24', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_24']; ?>
                    </label>
                  </div>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_braille_test', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_braille_test']; ?>
                    </label>
                  </div>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_proof_language', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_proof_language']; ?>
                    </label>
                  </div>

                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_cd_audio', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_cd_audio']; ?>
                    </label>
                  </div>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_video_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_video_libras']; ?>
                    </label>
                  </div>
                  <div class="t-field-checkbox">
                    <?php echo $form->checkBox($modelStudentIdentification, 'resource_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                    <label class="t-field-checkbox">
                      <?php echo StudentIdentification::model()->attributeLabels()['resource_none']; ?>
                    </label>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <?php if (Yii::app()->features->isEnable("FEAT_SEDSP")): ?>
            <!--Gov ID-->
            <div class="row">
              <div class="column clearleft is-two-fifths">
                <div class="t-field-text js-hide-not-required">
                  <?php echo $form->label($modelStudentIdentification, 'gov_id', array('class' => 't-field-text__label')); ?>
                  <?php echo $form->textField(
                    $modelStudentIdentification,
                    'gov_id',
                    array(
                      'size' => 60,
                      'maxlength' => 12,
                      'class' => 't-field-text__input',
                      'placeholder' => 'Não possui',
                      'disabled' => 'disabled'
                    )
                  ); ?>
                  <button type="button" id="copy-gov-id" class="t-button-icon">
                    <span class="t-icon-copy"></span>
                  </button>
                  <span id="copy-message" style="display:none;">
                  </span>
                  <?php echo $form->error($modelStudentIdentification, 'gov_id'); ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>


        <!--
                    ********************
                    FILIACAO DO ALUNO
                    ********************
                -->
        <div class="tab-pane" id="student-affiliation">
          <div class="row">
            <h3>
              Filiação
            </h3>
          </div>
          <!-- Filiação e Telefone do resposável -->
          <div class="row">
            <!-- Filiação -->
            <div class="column clearleft is-two-fifths">
              <?php echo $form->hiddenField($modelStudentIdentification, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>

              <div class="t-field-select" id="filiation-select">
                <?php echo $form->label($modelStudentIdentification, 'filiation', array('class' => 't-field-select__label--required')); ?>
                <?php
                if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
                  echo $form->DropDownList(
                    $modelStudentIdentification,
                    'filiation',
                    array("1" => "Pai e/ou Mãe"),
                    array('class' => 'select-search-off t-field-select__input select2-container')
                  );
                } else {
                  echo $form->DropDownList(
                    $modelStudentIdentification,
                    'filiation',
                    array(null => "Selecione a filiação", "0" => "Não declarado/Ignorado", "1" => "Pai e/ou Mãe"),
                    array('class' => 'select-search-off t-field-select__input select2-container')
                  );
                }
                ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation'); ?>
              </div>
            </div>
            <!-- Responsavel pelo aluno -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select" id="responsable-select">
                <?php echo $form->label($modelStudentIdentification, 'responsable', array('class' => 't-field-select__label')); ?>
                <?php
                echo $form->dropDownList(
                  $modelStudentIdentification,
                  'responsable',
                  array(null => "Selecione o responsável", 0 => 'Pai', 1 => 'Mãe', 2 => 'Outro', ),
                  array('class' => 'select-search-off t-field-select__input select2-container')
                );
                ?>
                <?php echo $form->error($modelStudentIdentification, 'responsable'); ?>
              </div>
            </div>
          </div>
          <!-- Profissão do responsável e email do Responsável -->
          <div class="row">
            <!-- Telefone do responsavel -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hide-not-required" id="telephone">
                <?php echo $form->label($modelStudentIdentification, 'responsable_telephone', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($modelStudentIdentification, 'responsable_telephone', array('size' => 60, 'maxlength' => 15, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($modelStudentIdentification, 'responsable_telephone'); ?>
              </div>
            </div>
            <!-- nome do responsével -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required" id="nameResponsable">
                <?php echo $form->label($modelStudentIdentification, 'responsable_name', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'responsable_name',
                  array('size' => 60, 'maxlength' => 100, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Nome do Responsável')
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'responsable_name'); ?>
              </div>
            </div>
          </div>
          <!-- Nome do responsável e Escolaridade do resposável -->
          <div class="row">
            <!-- Email responsável -->
            <div class="column clearleft is-two-fifths">
              <div class=" t-field-text js-hide-not-required" id="emailResponsable">
                <?php echo $form->label(
                  $modelStudentIdentification,
                  'email_responsable',
                  array('class' => 't-field-text__label')
                ); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'email_responsable',
                  array(
                    'size' => 60,
                    'maxlength' => 255,
                    'class' => 't-field-text__input',
                    'placeholder' => 'Digite o Email'
                  )
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'email_responsable'); ?>
              </div>
            </div>
            <!-- Profissião do responsavel -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required" id="responsableJob">
                <?php echo $form->label($modelStudentIdentification, 'responsable_job', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'responsable_job',
                  array(
                    'size' => 60,
                    'maxlength' => 100,
                    'class' => 't-field-text__input',
                    'placeholder' => 'Digite a Profissão do Responsável'
                  )
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'responsable_job'); ?>
              </div>
            </div>
          </div>
          <!-- Rg e cpf do responsável -->
          <div class="row">
            <!-- Escolaridade do responsavel -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select js-hide-not-required" id="scholarityResponsable-select">
                <?php echo $form->label($modelStudentIdentification, 'responsable_scholarity', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelStudentIdentification,
                  'responsable_scholarity',
                  array(
                    null => "Selecione a escolaridade",
                    "1" => "Formação Geral",
                    "2" => "Modalidade Normal (Magistério)",
                    "3" => "Curso Técnico",
                    "4" => "Magistério Indígena Modalidade Normal"
                  ),
                  array('class' => 'select-search-off t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'responsable_scholarity'); ?>
              </div>
            </div>
            <!-- rg responsavel -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required" id="rgResposable">
                <?php echo $form->label($modelStudentIdentification, 'responsable_rg', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'responsable_rg',
                  array('size' => 60, 'maxlength' => 45, 'class' => 't-field-text__input', 'placeholder' => 'Digite o RG do Responsável')
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'responsable_rg'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- cpf responsavel -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hide-not-required" id="cpfResponsable">
                <?php echo $form->label($modelStudentIdentification, 'responsable_cpf', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($modelStudentIdentification, 'responsable_cpf', array('size' => 60, 'maxlength' => 14, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($modelStudentIdentification, 'responsable_cpf'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="js-disabled-finputs">
              <h3>
                Filiação 1
              </h3>
            </div>
          </div>
          <!-- Nome e cpf filiação1 -->
          <div class="row">
            <!-- Nome Filiação 1-->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hide-not-required  js-disabled-finputs" id="filiationMain">
                <?php echo $form->label($modelStudentIdentification, 'filiation_1', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'filiation_1',
                  array(
                    'size' => 60,
                    'maxlength' => 100,
                    "class" => "js-disabled-finputs js-finput-clear t-field-text__input",
                    'placeholder' => 'Digite o Nome Completo da Filiação'
                  )
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_1'); ?>
              </div>
            </div>
            <!-- CPF da Mãe -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required js-disabled-finputs" id="cpfFiliation1">
                <?php echo $form->label($modelStudentIdentification, 'filiation_1_cpf', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'filiation_1_cpf',
                  array('size' => 60, 'maxlength' => 14, "class" => "js-disabled-finputs js-finput-clear t-field-text__input")
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_1_cpf'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- Data de Nascimento da Mãe -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hide-not-required js-disabled-finputs" id="dateOfBirthFiliation">
                <?php echo $form->label($modelStudentIdentification, 'filiation_1_birthday', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($modelStudentIdentification, 'filiation_1_birthday', array('size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_1_birthday'); ?>
              </div>
            </div>
            <!-- RG da Mãe -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text  js-hide-not-required js-disabled-finputs" id="rgFiliation1">
                <?php echo $form->label($modelStudentIdentification, 'filiation_1_rg', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'filiation_1_rg',
                  array(
                    'size' => 60,
                    'maxlength' => 45,
                    "class" => "js-disabled-finputs js-finput-clear t-field-text__input",
                    'placeholder' => 'Digite o RG da Filiação 1'
                  )
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_1_rg'); ?>
              </div>
            </div>
          </div>
          <!-- RG e Escolaridade filiação 1 -->
          <div class="row">
            <!-- Escolaridade da Mãe -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select js-hide-not-required js-disabled-finputs" id="scholarityFiliation1-select">
                <?php echo $form->label($modelStudentIdentification, 'filiation_1_scholarity', array('class' => 't-field-select__label')); ?>
                <?php
                echo $form->dropDownList($modelStudentIdentification, 'filiation_1_scholarity', array(
                  null => "Não declarado",
                  0 => 'Não sabe ler e escrever ',
                  1 => 'Sabe ler e escrever',
                  2 => 'Ens. Fund. Incompleto',
                  3 => 'Ens. Fund. Completo',
                  4 => 'Ens. Médio Incompleto',
                  5 => 'Ens. Médio Completo',
                  6 => 'Ens. Sup. Incompleto',
                  7 => 'Ens. Sup. Completo'
                ), array('class' => 'select-search-off js-disabled-finputs js-finput-clear t-field-select__input select2-container'));
                ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_1_scholarity'); ?>
              </div>
            </div>
            <!-- Profissão filiação 1 -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required js-disabled-finputs" id="professionFiliation1">
                <?php echo $form->label($modelStudentIdentification, 'filiation_1_job', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'filiation_1_job',
                  array(
                    'size' => 60,
                    'maxlength' => 100,
                    "class" => "js-disabled-finputs js-finput-clear t-field-text__input select2-container",
                    'placeholder' => 'Digite a Profissão da Filiação 1'
                  )
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_1_job'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="js-disabled-finputs">
              <h3>
                Filiação 2
              </h3>
            </div>
          </div>
          <!-- Nome e cpf Filiação 2  -->
          <div class="row">
            <!-- Nome filiação 2 -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hide-not-required js-disabled-finputs" id="filiationSecondary">
                <?php echo $form->label($modelStudentIdentification, 'filiation_2', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'filiation_2',
                  array(
                    'size' => 60,
                    'maxlength' => 100,
                    "class" => "js-disabled-finputs js-finput-clear t-field-text__input",
                    'placeholder' => 'Digite o Nome Completo do Pai'
                  )
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_2'); ?>
              </div>
            </div>
            <!-- CPF filiação 2 -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required js-disabled-finputs" id="cpfFiliation2">
                <?php echo $form->label($modelStudentIdentification, 'filiation_2_cpf', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'filiation_2_cpf',
                  array('size' => 60, 'maxlength' => 14, "class" => "js-disabled-finputs js-finput-clear t-field-text__input")
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_2_cpf'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- Data de Nascimento do Pai -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hide-not-required js-disabled-finputs" id="dateSecondary">
                <?php echo $form->label($modelStudentIdentification, 'filiation_2_birthday', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($modelStudentIdentification, 'filiation_2_birthday', array('size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_2_birthday'); ?>
              </div>
            </div>
            <!-- Rg filiação 2 -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required js-disabled-finputs" id="rgFiliation2">
                <?php echo $form->label($modelStudentIdentification, 'filiation_2_rg', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'filiation_2_rg',
                  array('size' => 60, 'maxlength' => 45, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite o RG do Pai')
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_2_rg'); ?>
              </div>
            </div>
          </div>
          <!-- RG e escolaridade filiação 2 -->
          <div class="row">
            <!-- Escolaridade do pai -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select js-hide-not-required js-disabled-finputs" id="scholarityFiliation2-select">
                <?php echo $form->label($modelStudentIdentification, 'filiation_2_scholarity', array('class' => 'control-label t-field-select__label')); ?>
                <?php
                echo $form->dropDownList($modelStudentIdentification, 'filiation_2_scholarity', array(
                  null => "Não declarado",
                  0 => 'Não sabe ler e escrever ',
                  1 => 'Sabe ler e escrever',
                  2 => 'Ens. Fund. Incompleto',
                  3 => 'Ens. Fund. Completo',
                  4 => 'Ens. Médio Incompleto',
                  5 => 'Ens. Médio Completo',
                  6 => 'Ens. Sup. Incompleto',
                  7 => 'Ens. Sup. Completo'
                ), array('class' => 'select-search-off js-disabled-finputs js-finput-clear t-field-select__input select2-container'));
                ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_2_scholarity'); ?>
              </div>
            </div>
            <!-- Trabalho filiação 2 -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required js-disabled-finputs" id="jobFiliation2">
                <?php echo $form->label($modelStudentIdentification, 'filiation_2_job', array('class' => 'ct-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'filiation_2_job',
                  array('size' => 60, 'maxlength' => 100, "class" => "js-disabled-finputs js-finput-clear t-field-text__input", 'placeholder' => 'Digite a Profissão do Pai')
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'filiation_2_job'); ?>
              </div>
            </div>
          </div>
        </div>


        <!--
                    *****************************
                    Tab Documentos dos alunos
                    *****************************
                -->
        <div class="tab-pane" id="student-documents">
          <div class="row">
            <h3>Documentos Entregues </h3>
          </div>

          <div class="row t-field-checkbox-group" id="received">
            <div class="column clearleft is-two-fifths">
              <div class="t-field-checkbox">
                <?php echo $form->checkBox(
                  $modelStudentDocumentsAndAddress,
                  'received_cc',
                  array(
                    'value' => 1,
                    'class' => 't-field-checkbox__input',
                    'uncheckValue' => 0,
                    'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_cc
                  )
                ); ?>
                <label class="t-field-checkbox">
                  <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_cc']; ?>
                </label>
              </div>
              <div class="t-field-checkbox">
                <?php echo $form->checkBox(
                  $modelStudentDocumentsAndAddress,
                  'received_address',
                  array(
                    'value' => 1,
                    'class' => 't-field-checkbox__input',
                    'uncheckValue' => 0,
                    'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_address
                  )
                ); ?>
                <label class="t-field-checkbox ">
                  <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_address']; ?>
                </label>
              </div>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-checkbox">
                <?php echo $form->checkBox(
                  $modelStudentDocumentsAndAddress,
                  'received_photo',
                  array(
                    'value' => 1,
                    'class' => 't-field-checkbox__input',
                    'uncheckValue' => 0,
                    'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_photo
                  )
                ); ?>
                <label class="t-field-checkbox ">
                  <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_photo']; ?>
                </label>
              </div>
              <div class="t-field-checkbox">
                <?php echo $form->checkBox(
                  $modelStudentDocumentsAndAddress,
                  'received_nis',
                  array(
                    'value' => 1,
                    'class' => 't-field-checkbox__input',
                    'uncheckValue' => 0,
                    'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_nis
                  )
                ); ?>
                <label class="t-field-checkbox ">
                  <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_nis']; ?>
                </label>
              </div>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-checkbox">
                <?php echo $form->checkBox(
                  $modelStudentDocumentsAndAddress,
                  'received_responsable_rg',
                  array(
                    'value' => 1,
                    'class' => 't-field-checkbox__input',
                    'uncheckValue' => 0,
                    'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_responsable_rg
                  )
                ); ?>
                <label class="t-field-checkbox">
                  <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_responsable_rg']; ?>
                </label>
              </div>
              <div class="t-field-checkbox">
                <?php echo $form->checkBox(
                  $modelStudentDocumentsAndAddress,
                  'received_responsable_cpf',
                  array(
                    'value' => 1,
                    'class' => 't-field-checkbox__input',
                    'uncheckValue' => 0,
                    'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_responsable_cpf
                  )
                ); ?>
                <label class="t-field-checkbox">
                  <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_responsable_cpf']; ?>
                </label>
              </div>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <label class="t-field-checkbox" style="align-items: center">
                <?php echo $form->checkBox(
                  $modelStudentDocumentsAndAddress,
                  'consent_form',
                  array(
                    'value' => 1,
                    'class' => 't-field-checkbox__input',
                    'uncheckValue' => 0,
                    'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->consent_form
                  )
                ); ?>
                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['consent_form']; ?>
              </label>
              <label class="t-field-checkbox" style="align-items: center">
                <?php echo $form->checkBox(
                  $modelStudentDocumentsAndAddress,
                  'received_sus_card',
                  array(
                    'value' => 1,
                    'class' => 't-field-checkbox__input',
                    'uncheckValue' => 0,
                    'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_sus_card
                  )
                ); ?>
                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_sus_card']; ?>
              </label>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <label class="t-field-checkbox" style="align-items: center">
                <?php echo $form->checkBox(
                  $modelStudentDocumentsAndAddress,
                  'received_student_rg',
                  array(
                    'value' => 1,
                    'class' => 't-field-checkbox__input',
                    'uncheckValue' => 0,
                    'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_student_rg
                  )
                ); ?>
                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_student_rg']; ?>
              </label>
              <label class="t-field-checkbox" style="align-items: center">
                <?php echo $form->checkBox(
                  $modelStudentDocumentsAndAddress,
                  'received_student_cpf',
                  array(
                    'value' => 1,
                    'class' => 't-field-checkbox__input',
                    'uncheckValue' => 0,
                    'checked' => ($modelStudentDocumentsAndAddress->id == "") ? 'checked' : $modelStudentDocumentsAndAddress->received_student_cpf
                  )
                ); ?>
                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_student_cpf']; ?>
              </label>
            </div>
          </div>
          <div class="row">
            <h3>
              Certidão Civil
            </h3>
          </div>
          <!-- Certidão Civil e Tipo de certidão civil -->
          <div class="row">
            <!-- Certidão Civil -->
            <div class="column clearleft is-two-fifths" id="civilCertification-select">
              <?php echo $form->hiddenField($modelStudentIdentification, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>
              <div class="t-field-select">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'civil_certification', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelStudentDocumentsAndAddress,
                  'civil_certification',
                  array(null => "Selecione o modelo", "1" => "Modelo Antigo", "2" => "Modelo Novo"),
                  array("class" => "select-search-off t-field-select__input select2-container nationality-sensitive br no-br", "disabled" => "disabled")
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification'); ?>
              </div>
            </div>
            <!-- Tipo de certidão civil -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select js-hidden-oldDocuments-fields" id="typeOfCivil-select">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'civil_certification_type', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropdownList(
                  $modelStudentDocumentsAndAddress,
                  'civil_certification_type',
                  array(null => "Selecione o tipo", "1" => "Nascimento", "2" => "Casamento"),
                  array("class" => "select-search-off t-field-select__input select2-container nationality-sensitive br no-br", "disabled" => "disabled")
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_type'); ?>
              </div>

            </div>
          </div>
          <div class="row">
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hidden-oldDocuments-fields" id="termNumber">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'civil_certification_term_number', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'civil_certification_term_number',
                  array(
                    'size' => 8,
                    'maxlength' => 8,
                    "disabled" => "disabled",
                    "class" => "t-field-text__input nationality-sensitive br no-br",
                    'placeholder' => 'Digite o Nº do Termo'
                  )
                ); ?>
                <span id="termMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                  <img id="errorTermIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>"
                    alt="icone erro">
                </span>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_term_number'); ?>
              </div>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hidden-oldDocuments-fields" id="sheet">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'civil_certification_sheet', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'civil_certification_sheet',
                  array('size' => 4, 'maxlength' => 4, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive br no-br", 'placeholder' => 'Digite a Folha')
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_sheet'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hidden-oldDocuments-fields" id="bookCertification">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'civil_certification_book', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'civil_certification_book',
                  array('size' => 8, 'maxlength' => 8, "disabled" => "disabled", "class" => "t-field-text__input nationality-sensitive br no-br", 'placeholder' => 'Digite o Livro')
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_book'); ?>
              </div>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hidden-oldDocuments-fields" id="certificationDate">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'civil_certification_date', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'civil_certification_date',
                  array(
                    'size' => 10,
                    'maxlength' => 10,
                    "disabled" => "disabled",
                    "class" => "t-field-text__input nationality-sensitive br no-br",
                    'placeholder' => 'Digite a Data de Emissão da Certidão (Dia/Mês/Ano)'
                  )
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_date'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select js-hidden-oldDocuments-fields" id="ufRegistry-select">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'notary_office_uf_fk', array('class' => 't-field-select__label')); ?>
                <?php
                echo $form->dropDownList(
                  $modelStudentDocumentsAndAddress,
                  'notary_office_uf_fk',
                  CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'),
                  array(
                    'ajax' => array(
                      'type' => 'POST',
                      'url' => CController::createUrl('student/getcities', array('rt' => 1)),
                      'update' => '#StudentDocumentsAndAddress_notary_office_city_fk'
                    ),
                    "prompt" => "Selecione um estado",
                    "class" => "select-search-on t-field-select__input select2-container nationality-sensitive br no-br",
                    "disabled" => "disabled",
                  )
                );
                ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_uf_fk'); ?>
              </div>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select js-hidden-oldDocuments-fields" id="municipalityRegistry-select">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'notary_office_city_fk', array('class' => 't-field-select__label')); ?>
                <?php
                echo $form->dropDownList(
                  $modelStudentDocumentsAndAddress,
                  'notary_office_city_fk',
                  CHtml::listData(EdcensoCity::model()->findAllByAttributes(
                    array('edcenso_uf_fk' => $modelStudentDocumentsAndAddress->notary_office_uf_fk),
                    array('order' => 'name')
                  ), 'id', 'name'),
                  array(
                    'ajax' => array(
                      'type' => 'POST',
                      'url' => CController::createUrl('student/getnotaryoffice'),
                      'update' => '#StudentDocumentsAndAddress_edcenso_notary_office_fk'
                    ),
                    "prompt" => "Selecione uma cidade",
                    "class" => "select-search-on t-field-select__input select2-container nationality-sensitive br no-br",
                    "disabled" => "disabled"
                  )
                );
                ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_city_fk'); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select js-hidden-oldDocuments-fields" id="notaryOffice-select">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk', array('class' => 't-field-select__label')); ?>
                <?php
                echo $form->dropDownList(
                  $modelStudentDocumentsAndAddress,
                  'edcenso_notary_office_fk',
                  CHtml::listData(EdcensoNotaryOffice::model()->findAllByAttributes(
                    array('city' => $modelStudentDocumentsAndAddress->notary_office_city_fk),
                    array('order' => 'name')
                  ), 'cod', 'name') + array('7177' => 'OUTROS'),
                  array(
                    "prompt" => "Selecione um cartório",
                    "class" => "select-search-on t-field-select__input select2-container nationality-sensitive br no-br",
                    "disabled" => "disabled",
                  )
                );
                ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk'); ?>
              </div>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hidden-newDocument-field" id="numberRegistration">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'civil_register_enrollment_number',
                  array("disabled" => "disabled", "class" => "nationality-sensitive br no-br t-field-text__input select2-container")
                ); ?>
                <span id="registerMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                  <img id="registerIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>"
                    alt="icone erro">
                </span>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number'); ?>
              </div>
            </div>
          </div>

          <section class="t-field-section">
            <div class="row">
              <h3>
                Cartão Nacional de Saúde
              </h3>
            </div>
          </section>

          <div class="row">
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text" id="numberCns">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'cns', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'cns',
                  array(
                    'size' => 11,
                    'maxlength' => 15,
                    "disabled" => "disabled",
                    "class" => "t-field-text__input nationality-sensitive br no-br",
                    'placeholder' => 'Digite o Nº do CNS'
                  )
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'cns'); ?>
              </div>
            </div>
          </div>

          <section class="t-field-section">
            <div class="row">
              <h3>
                Registro Geral
              </h3>
            </div>
          </section>

          <!-- Número do RG e órgão emissor da identidade -->
          <div class="row">
            <!-- Numero do RG -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text" id="numberIdentity">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'rg_number', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'rg_number',
                  array(
                    'size' => 20,
                    'maxlength' => 20,
                    "disabled" => "disabled",
                    "class" => "t-field-text__input nationality-sensitive br no-br",
                    'placeholder' => 'Digite o Nº da Identidade'
                  )
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number'); ?>
              </div>
            </div>
            <!-- Órgão emissor da identidade -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select" id="rgOrgan-select">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropdownList(
                  $modelStudentDocumentsAndAddress,
                  'rg_number_edcenso_organ_id_emitter_fk',
                  CHtml::listData(EdcensoOrganIdEmitter::model()->findAll(array('order' => 'name')), 'id', 'name'),
                  array(
                    "prompt" => "Selecione um órgão emissor da identidade",
                    "class" => "select-search-on t-field-select__input select2-container nationality-sensitive br no-br ",
                    "disabled" => "disabled"
                  )
                );
                ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk'); ?>
              </div>
            </div>
          </div>
          <!-- Data de expedição da identidade e UF da identidade-->
          <div class="row">
            <!-- Data de expedição da identidade -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text" id="identityDate">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'rg_number_expediction_date', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'rg_number_expediction_date',
                  array(
                    'size' => 10,
                    'maxlength' => 10,
                    "disabled" => "disabled",
                    "class" => "t-field-text__input nationality-sensitive br no-br",
                    'placeholder' => 'Digite a Data de Expedição da Identidade dd/mm/aaaa'
                  )
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_expediction_date'); ?>
              </div>
            </div>
            <!-- UF da identidade -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select" id="identyUF-select">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk', array('class' => 't-field-select__label')); ?>
                <?php echo $form->dropDownList(
                  $modelStudentDocumentsAndAddress,
                  'rg_number_edcenso_uf_fk',
                  CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'),
                  array(
                    "prompt" => "Selecione um estado",
                    "class" => "select-search-on t-field-select__input select2-container nationality-sensitive br no-br",
                    "disabled" => "disabled"
                  )
                );
                ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk'); ?>
              </div>
            </div>
          </div>

          <!-- Restrição na Justiça e Documento Estrangeiro / Passaporte -->
          <div class="row">
            <!-- Restrição na Justiça -->
            <div class="column clearleft is-two-fifths">
              <h3>Justiça</h3>
              <div class="t-field-select" id="justice-select">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'justice_restriction', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelStudentDocumentsAndAddress,
                  'justice_restriction',
                  array(null => "Selecione", "0" => "Não possui restrições", "1" => "LA - Liberdade Assistida", "2" => "PSC - Prestação de Serviços Comunitários"),
                  array('class' => 'select-search-off t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'justice_restriction'); ?>
              </div>
            </div>
            <!-- Documento Estrangeiro / Passaporte -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <h3>Passaporte</h3>
              <div class="t-field-text">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'foreign_document_or_passport', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'foreign_document_or_passport',
                  array(
                    'size' => 20,
                    'maxlength' => 20,
                    "disabled" => "disabled",
                    "class" => "t-field-text__input nationality-sensitive no-br",
                    'placeholder' => 'Digite o Passaporte ou Documento Estrangeiro'
                  )
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'foreign_document_or_passport'); ?>
              </div>
            </div>
          </div>

          <section class="t-field-section">
            <div class="row">
              <h3>
                Justificativa da falta de documentação
              </h3>
            </div>
          </section>

          <!-- Justificativa de falta de documentação -->
          <div class="row">
            <!-- Justificativa de falta de documentação -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select" id="justification-select">
                <?php echo $form->label($modelStudentIdentification, 'no_document_desc', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelStudentIdentification,
                  'no_document_desc',
                  array(
                    null => "Selecione a justificativa",
                    "1" => "O(a) aluno(a) não possui os documentos pessoais solicitados",
                    "2" => "A escola não dispõe ou não recebeu os documentos pessoais do(a) aluno(a)"
                  ),
                  array("class" => "select-search-off t-field-select__input select2-container nationality-sensitive br no-br", "disabled" => "disabled")
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'no_document_desc'); ?>
              </div>
            </div>
          </div>

          <section class="t-field-section">
            <div class="row">
              <h3>
                Dados Sociais
              </h3>
            </div>
          </section>
          <!--  Nº de Identificação Social (NIS) e ID INEP-->
          <div class="row">
            <!-- Nº de Identificação Social (NIS) -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hide-not-required" id="nis">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'nis', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($modelStudentDocumentsAndAddress, 'nis', array('size' => 11, 'maxlength' => 11, 'placeholder' => 'Digite o NIS')); ?>
                <span id="nisMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                  <img id="errorNisIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>"
                    alt="icone erro">
                </span>
                <span id="nisMessage" data-toggle="tooltip" data-placement="top" data-original-title="">
                  <img id="errorNisIcon" style="display: none;" src="<?php echo $themeUrl . '/img/error-icon.svg' ?>"
                    alt="icone erro">
                </span>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'nis'); ?>
              </div>
            </div>
            <!-- ID INEP -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required" id="idInep">
                <?php echo $form->label($modelStudentIdentification, 'inep_id', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentIdentification,
                  'inep_id',
                  array('size' => 60, 'maxlength' => 12, 'class' => 't-field-text__input', 'placeholder' => 'Digite o ID INEP')
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'inep_id'); ?>
              </div>
            </div>
          </div>
          <!-- Participante do Bolsa Família e Pós Censo * -->
          <div class="row">
            <!-- Participante do Bolsa Família -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-checkbox js-hide-not-required" id="participantBF">
                <?php echo $form->checkBox($modelStudentIdentification, 'bf_participator', array('class' => 't-field-checkbox__input')); ?>
                <?php echo $form->label($modelStudentIdentification, 'bf_participator', array('class' => 'control-label t-field-checkbox__label')); ?>
                <?php echo $form->error($modelStudentIdentification, 'bf_participator'); ?>
              </div>
            </div>
            <!-- Pós Censo -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-checkbox" id="postCensus">
                <?php echo $form->checkBox(
                  $modelStudentIdentification,
                  'send_year',
                  array('value' => date('Y') + 1, 'uncheckValue' => (date('Y')), 'class' => 't-field-checkbox__input')
                ); ?>
                <?php echo $form->error($modelStudentIdentification, 'send_year'); ?>
                <?php echo $form->label($modelStudentIdentification, 'send_year', array('class' => 'control-label t-field-checkbox__label--required')); ?>
              </div>
            </div>
          </div>
        </div>


        <!--
                    **********************
                    Tab Endereço aluno
                    **********************
                -->
        <div class="tab-pane" id="student-address">
          <!-- Estado e CEP -->
          <div class="row">
            <!-- Estado -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select js-hide-not-required">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'edcenso_uf_fk', array('class' => 't-field-select__label')); ?>
                <?php
                echo $form->dropDownList(
                  $modelStudentDocumentsAndAddress,
                  'edcenso_uf_fk',
                  CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'),
                  array(
                    'ajax' => array(
                      'type' => 'POST',
                      'url' => CController::createUrl('student/getcities', array('rt' => 2)),
                      'update' => '#StudentDocumentsAndAddress_edcenso_city_fk'
                    ),
                    "prompt" => "Selecione um estado",
                    "class" => "select-search-on t-field-select__input select2-container"
                  )
                );
                ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_uf_fk'); ?>
              </div>
            </div>
            <!-- CEP -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'cep', array('class' => 't-field-text__label')); ?>
                <?php
                echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'cep',
                  array(
                    'size' => 8,
                    'maxlength' => 9,
                    'class' => 't-field-text_input'
                  )
                );
                ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'cep'); ?>
              </div>
            </div>
          </div>
          <!-- Cidade e Endereço -->
          <div class="row">
            <!-- Cidade -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select js-hide-not-required">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'edcenso_city_fk', array('class' => 't-field-text__label')); ?>
                <?php
                echo $form->dropDownList(
                  $modelStudentDocumentsAndAddress,
                  'edcenso_city_fk',
                  CHtml::listData(EdcensoCity::model()->findAllByAttributes(
                    array('edcenso_uf_fk' => $modelStudentDocumentsAndAddress->edcenso_uf_fk),
                    array('order' => 'name')
                  ), 'id', 'name'),
                  array("prompt" => "Selecione uma cidade", "class" => "select-search-on t-field-select__input select2-container")
                );
                ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_city_fk'); ?>
              </div>
            </div>
            <!-- Endereço -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'address', array('class' => 't-field-text__labeld')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'address',
                  array('size' => 60, 'maxlength' => 100, 'placeholder' => 'Digite o Endereço', 'class' => 't-field-text__input')
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'address'); ?>
              </div>
            </div>
          </div>
          <!-- Bairro/Povoado e N° -->
          <div class="row">
            <!-- Bairro -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hide-not-required">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'neighborhood', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'neighborhood',
                  array('size' => 50, 'maxlength' => 50, 'placeholder' => 'Digite o Bairro ou Povoado', 'class' => 't-field-text__input')
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'neighborhood'); ?>
              </div>
            </div>
            <!-- N° -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'number', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'number',
                  array('size' => 10, 'maxlength' => 10, 'placeholder' => 'Digite o Número', 'class' => 't-field-text__input')
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'number'); ?>
              </div>
            </div>
          </div>
          <!-- Complemento e localização diferênciada -->
          <div class="row">
            <!-- Complemento -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hide-not-required" id="complement">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'complement', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField(
                  $modelStudentDocumentsAndAddress,
                  'complement',
                  array('size' => 20, 'maxlength' => 20, 'placeholder' => 'Digite o Complemento', 'class' => 't-field-text__input')
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'complement'); ?>
              </div>
            </div>
            <!-- Localização diferênciada -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select  js-hide-not-required" id="location-select">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'diff_location', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelStudentDocumentsAndAddress,
                  'diff_location',
                  array(
                    null => 'Selecione a localização',
                    7 => 'Não reside em área de localização diferenciada',
                    1 => 'Área de assentamento',
                    2 => 'Terra indígena',
                    3 => 'Comunidade quilombola',
                    8 => 'Área onde se localizam povos e comunidades tradicionais'
                  ),
                  array("class" => "select-search-on t-field-select__input select2-container")
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'diff_location'); ?>
              </div>
            </div>
          </div>
          <!-- Zona residêncial -->
          <div class="row">
            <div class="column clearleft is-two-fifths">
              <!-- Localização / Zona de residência * -->
              <div class="t-field-select" id="zone-select">
                <?php echo $form->label($modelStudentDocumentsAndAddress, 'residence_zone', array('class' => 't-field-select__label--required')); ?>
                <?php echo $form->DropDownList(
                  $modelStudentDocumentsAndAddress,
                  'residence_zone',
                  array(null => "Selecione uma zona", "1" => "URBANA", "2" => "RURAL"),
                  array('class' => 'select-search-off t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelStudentDocumentsAndAddress, 'residence_zone'); ?>
              </div>
            </div>
          </div>
        </div>


        <!--
                    *************************
                    Tab Aluno matricula
                    *************************
                -->
        <div class="tab-pane" id="student-enrollment">
          <div class="row">
            <div class="column clearleft is-two-fifths">
              <div class="t-buttons-container">
                <?php
                if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
                  $idStudent = isset($_GET['id']) ? $_GET['id'] : null;

                  if ($idStudent !== null) {
                    $sql = "SELECT COUNT(*) FROM classroom
                                                    WHERE id IN (SELECT classroom_fk FROM student_enrollment WHERE student_fk = :idStudent AND status = 1)
                                                    AND school_year = :schoolYear";

                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindValues(array(':idStudent' => $idStudent, ':schoolYear' => Yii::app()->user->year));
                    $existEnrollment = (int) $command->queryScalar();

                    if ($existEnrollment === 0) {
                      echo '<a href="#" class="t-button-primary" id="new-enrollment-button">Adicionar Matrícula</a>';
                    }
                  } else {
                    echo '<a href="#" class="t-button-primary" id="new-enrollment-button">Adicionar Matrícula</a>';
                  }
                } else {
                  echo '<a href="#" class="t-button-primary" id="new-enrollment-button">Adicionar Matrícula</a>';
                }

                ?>
                <?php
                echo $modelStudentIdentification->isNewRecord ? "" : '<a href=' . @Yii::app()->createUrl(
                  'student/transfer',
                  array('id' => $modelStudentIdentification->id)
                ) . ' class="t-button-secondary" id="transfer-student">Transferir Matrícula</a>'
                  ?>
              </div>
            </div>
          </div>
          <!-- Turma e tipo de ingresso -->
          <div class="row new-enrollment-form" style="display: none;">
            <!-- Turma -->
            <div class="column clearleft is-two-fifths">
              <?php echo $form->hiddenField($modelEnrollment, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>
              <div class="t-field-select" id="class-select">
                <?php echo $form->label($modelEnrollment, 'classroom_fk', array('class' => 't-fild-select--required')); ?>
                <?php
                $stage = $modelStudentIdentification->getCurrentStageVsModality();
                $stages = implode(",", EdcensoStageVsModality::getNextStages($stage));
                $classrooms = Classroom::model()->findAll(
                  "school_year = :year AND school_inep_fk = :school order by name",
                  [
                    ':year' => Yii::app()->user->year,
                    ':school' => Yii::app()->user->school,
                  ]
                );

                $classroomOptions = CHtml::listData($classrooms, 'id', 'name');
                $optionsDataAttributes = array();
                foreach ($classrooms as $classroom) {
                  $optionsDataAttributes[$classroom->id] = array(
                    'data-isMulti' =>
                      (int) (TagUtils::isMultiStage(
                        $classroom->edcenso_stage_vs_modality_fk
                      ))
                  );
                }

                echo $form->dropDownList(
                  $modelEnrollment,
                  'classroom_fk',
                  $classroomOptions,
                  array(
                    "prompt" => "Selecione uma Turma",
                    'class' => 'select-search-off t-field-select__input select2-container js-classroom-is-multi',
                    'options' => $optionsDataAttributes,
                    'encode' => false
                  )
                );

                ?>
                <?php echo $form->error($modelEnrollment, 'classroom_fk'); ?>
              </div>
            </div>
            <!-- Tipo de ingresso -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select js-hide-not-required" id="ticketType-select">
                <?php echo $form->label($modelEnrollment, 'admission_type', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelEnrollment,
                  'admission_type',
                  array("1" => "Rematrícula", "2" => "Transferência interna", "3" => "Transferência externa"),
                  array("prompt" => "Selecione", 'class' => 'select-search-off t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelEnrollment, 'admission_type'); ?>
              </div>
            </div>
          </div>
          <!-- Data de ingresso na escola e Situação na série/etapa atual -->
          <div class="row new-enrollment-form" style="display: none;">
            <!--  Data de ingresso na escola -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-text js-hide-not-required" id="ticketDate">
                <?php echo $form->label($modelEnrollment, 'school_admission_date', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($modelEnrollment, 'school_admission_date', array('size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($modelEnrollment, 'school_admission_date'); ?>
              </div>
            </div>
            <!-- Situação na série/etapa atual -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select js-hide-not-required" id="situationSerie-select">
                <?php echo $form->label($modelEnrollment, 'current_stage_situation', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelEnrollment,
                  'current_stage_situation',
                  array(
                    null => "Selecione",
                    "0" => "Primeira matrícula no curso",
                    "1" => "Promovido na série anterior do mesmo curso",
                    "2" => "Repetente"
                  ),
                  array('class' => 'select-search-off t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelEnrollment, 'current_stage_situation'); ?>
              </div>
            </div>
          </div>
          <div class="row new-enrollment-form" style="display: none;">
            <!--  Data de transferência externa na escola -->
            <div id="transferDiv" class="column clearleft is-two-fifths hide">
              <div class="t-field-text js-hide-not-required">
                <?php echo $form->label($modelEnrollment, 'class_transfer_date', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($modelEnrollment, 'class_transfer_date', array('size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($modelEnrollment, 'class_transfer_date'); ?>
              </div>
            </div>
            <!--  Data de rematrícula na escola -->
            <div id="readmissionDiv" class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-text js-hide-not-required">
                <?php echo $form->label($modelEnrollment, 'school_readmission_date', array('class' => 't-field-text__label')); ?>
                <?php echo $form->textField($modelEnrollment, 'school_readmission_date', array('size' => 10, 'maxlength' => 10, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($modelEnrollment, 'school_readmission_date'); ?>
              </div>
            </div>
          </div>
          <!-- Situação da matrícula e Situação na série/etapa atual -->
          <div class="row new-enrollment-form" style="display: none;">
            <!-- Situação da matrícula -->
            <div class="column clearleft is-two-fifths">
              <div class="t-field-select js-hide-not-required" id="registrationStatus-select">
                <?php echo $form->label($modelEnrollment, 'status', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelEnrollment,
                  'status',
                  StudentEnrollment::getListStatus(),
                  array('options' => array('1' => array('selected' => true)), "prompt" => "Selecione", 'class' => 'select-search-off t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelEnrollment, 'status'); ?>
              </div>
            </div>
            <!-- Situação na série/etapa atual -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select js-hide-not-required" id="situationYear-select">
                <?php echo $form->label($modelEnrollment, 'previous_stage_situation', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelEnrollment,
                  'previous_stage_situation',
                  array(
                    null => "Selecione",
                    "0" => "Não frequentou",
                    "1" => "Reprovado",
                    "2" => "Afastado por transferência",
                    "3" => "Afastado por abandono",
                    "4" => "Matrícula final em Educação Infantil",
                    "5" => "Promovido"
                  ),
                  array('class' => 'select-search-off t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelEnrollment, 'previous_stage_situation'); ?>
              </div>
            </div>
          </div>
          <div class="row new-enrollment-form" style="display: none;">
            <h3>
              Unificação de turma
            </h3>
          </div>
          <!-- turma unificada , etapa e Etapa de Ensino  / -->
          <div class="row new-enrollment-form" style="display: none;">
            <div class="column clearleft is-two-fifths">
              <!-- Turma unificada -->
              <div class="t-field-select js-hide-not-required" id="unifiedClassroom-select">
                <?php echo $form->label($modelEnrollment, 'unified_class', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelEnrollment,
                  'unified_class',
                  array(null => "Selecione o tipo de turma infantil", "1" => "CRECHE", "2" => "PRÉ-ESCOLA", "3" => "NÃO POSSUI"),
                  array('class' => 'select-search-off t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelEnrollment, 'unified_class'); ?>
              </div>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <div class="t-field-select js-hide-not-required" id="schooling-select">
                <?php echo $form->label($modelEnrollment, 'another_scholarization_place', array('class' => 't-field-select__label')); ?>
                <?php echo $form->DropDownList(
                  $modelEnrollment,
                  'another_scholarization_place',
                  array("1" => "Não recebe", "2" => "Em hospital", "3" => "Em domicílio"),
                  array('class' => 'select-search-on t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelEnrollment, 'another_scholarization_place'); ?>
              </div>
            </div>
          </div>
          <div class="row new-enrollment-form" style="display: none;">
            <div class="column clearleft is-two-fifths">
              <!-- Etapa -->
              <div class="t-field-select js-hide-not-required" id="stage-select">
                <?php echo CHtml::label("Etapa", 'Stage', array('class' => 't-field-select__label')); ?>
                <?php
                echo CHtml::dropDownList(
                  "Stage",
                  null,
                  array(
                    "0" => "Selecione a Modalidade",
                    "1" => "Infantil",
                    "2" => "Fundamental Menor",
                    "3" => "Fundamental Maior",
                    "4" => "Médio",
                    "5" => "Profissional",
                    "6" => "EJA",
                    "7" => "Outros",
                  ),
                  array(
                    'class' => 'select-search-off t-field-select__input select2-container',
                    'ajax' => array(
                      'type' => 'POST',
                      'url' => CController::createUrl('enrollment/getmodalities'),
                      'success' => 'function(data){
                                                $("#StudentEnrollment_edcenso_stage_vs_modality_fk").html(decodeHtml(data));
                                            }'
                    ),
                  )
                );
                ?>
              </div>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <!-- Etapa de Ensino -->
              <div class="t-field-select js-hide-not-required" id="teachingStage-select">
                <?php echo $form->label($modelEnrollment, 'edcenso_stage_vs_modality_fk', array('class' => 't-field-select__label')); ?>
                <?php echo $form->dropDownList(
                  $modelEnrollment,
                  'edcenso_stage_vs_modality_fk',
                  CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'),
                  array("prompt" => "Selecione a etapa", 'class' => 'select-search-on t-field-select__input select2-container')
                ); ?>
                <?php echo $form->error($modelEnrollment, 'edcenso_stage_vs_modality_fk'); ?>
              </div>
            </div>
          </div>
          <!-- Transporte -->
          <div class="row new-enrollment-form" style="display: none;">
            <!-- Transporte escolar Público, Tipo Transporte escolar Público e Tipo de Transporte-->
            <div class="column clearleft is-two-fifths">
              <h3>Transporte</h3>
              <!-- Transporte escolar Público -->
              <div class="t-field-checkbox js-hide-not-required" id="publicTransport">
                <?php echo $form->checkBox($modelEnrollment, 'public_transport', array('value' => 1, 'uncheckValue' => 0, 'class' => 't-field-checkbox__input')); ?>
                <?php echo $form->label($modelEnrollment, 'public_transport', array('class' => 'control-label t-field-checkbox__label--required')); ?>
              </div>
              <!-- Tipo Transporte escolar Público -->
              <div class="t-field-select t-input" id="transport_responsable">
                <?php echo $form->label($modelEnrollment, 'transport_responsable_government', array('class' => 'control-label t-input__label--required')); ?>
                <?php echo $form->dropDownList($modelEnrollment, 'transport_responsable_government', array(null => "Selecione o poder público do transporte", "1" => "Estadual", "2" => "Municipal"), array('class' => 'select-search-off control-input t-field-select__input')); ?>
                <?php echo $form->error($modelEnrollment, 'transport_responsable_government'); ?>
              </div>
              <!-- Tipo de Transporte * -->
              <div class="t-field-checkbox-group hide-responsive" id="transport_type">
                <label class="t-field-checkbox__label"><?php echo Yii::t('default', 'Transport Type'); ?>
                  *</label>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_van', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_van']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_microbus', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_microbus']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_bus', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bus']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_bike', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bike']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_animal_vehicle', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_animal_vehicle']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_other_vehicle', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_other_vehicle']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_5', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_5_15', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5_15']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_15_35', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_15_35']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_35', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_35']; ?>
                  </label>
                </div>
              </div>

            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <h3>Atendimento especializado</h3>
              <!-- Tipo de Atendimento Educacional Especializado -->
              <div class="t-field-checkbox-group js-hide-not-required" id="typeOfService">
                <label
                  class="t-field-checkbox__label"><?php echo Yii::t('default', 'Type of Specialized Educational Assistance'); ?></label>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_cognitive_functions', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_cognitive_functions']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox" id="text">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_autonomous_life', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_autonomous_life']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_curriculum_enrichment', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_curriculum_enrichment']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_accessible_teaching', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_accessible_teaching']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_libras']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_portuguese', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_portuguese']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_soroban', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_soroban']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_braille', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_braille']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_mobility_techniques', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_mobility_techniques']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_caa', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_caa']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelEnrollment, 'aee_optical_nonoptical', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentEnrollment::model()->attributeLabels()['aee_optical_nonoptical']; ?>
                  </label>
                </div>
              </div>
              <div class="t-field-text js-hide-not-required">
                <?php echo $form->label($modelEnrollment, 'observation', array('class' => ' t-field-text__label')); ?>
                <?php echo $form->textArea($modelEnrollment, 'observation', array('rows' => 6, 'cols' => 50, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($modelEnrollment, 'observation'); ?>
              </div>
            </div>
          </div>
          <div class="row-fluid">
            <?php
            $error = $modelEnrollment->getErrors('enrollment_id');
            if (!empty($error)):
              ?>
              <div class="alert alert-error">
                <?php echo $error[0]; ?>
              </div>
            <?php endif; ?>
            <div id="enrollment" class="widget widget-scroll margin-bottom-none">
              <div class="row">
                <h3>
                  <?php echo yii::t("default", "Enrollments"); ?>
                </h3>
              </div>
              <div class="row">
                <div class="column clearleft is-four-fifths">
                  <div id="accordion" class="t-accordeon-quaternary">
                    <?php
                    foreach ($modelStudentIdentification->studentEnrollments as $me) {
                      ?>
                      <div class="ui-accordion-header justify-content--space-between">
                        <div>
                          <div class="mobile-row align-items--center">
                            <h4 class="t-title"><?php echo $me->classroomFk->name ?>
                              - <?php echo $me->classroomFk->school_year ?></h4>
                            <?php
                            switch ($me->status) {
                              case "1":
                                $enrollment_date = "";
                                if (isset($me->enrollment_date)) {
                                  $enrollment_date = date_create_from_format('Y-m-d', $me->enrollment_date)->format('d/m/Y');
                                }
                                echo "<label class='t-badge-success'>Matriculado " . $enrollment_date . "</label>";
                                break;
                              case "2":
                                $transfer_date = "";
                                if (isset($me->transfer_date)) {
                                  $transfer_date = date_create_from_format('Y-m-d', $me->transfer_date)->format('d/m/Y');
                                }
                                echo "<label class='t-badge-success'>Transferido " . $transfer_date . "</label>";
                                break;
                              case "3":
                                echo "<label class='t-badge-critical'>Cancelado</label>";
                                break;
                              case "4":
                                echo "<label class='t-badge-critical'>Deixou de Frequentar</label>";
                                break;
                              case "5":
                                echo "<label class='t-badge-warning'>Remanejado</label>";
                                break;
                              case "6":
                                echo "<label class='t-badge-success'>Aprovado</label>";
                                break;
                              case "7":
                                echo "<label class='t-badge-success'>Aprovado pelo Conselho</label>";
                                break;
                              case "8":
                                echo "<label class='t-badge-critical'>Reprovado</label>";
                                break;
                              case "9":
                                echo "<label class='t-badge-success'>Concluinte</label>";
                                break;
                              case "10":
                                echo "<label class='t-badge-warning'>Indeterminado</label>";
                                break;
                              case "11":
                                echo "<label class='t-badge-critical'>Falecido</label>";
                                break;
                              case "12":
                                echo "<label class='t-badge-success'>Avançado</label>";
                                break;
                              case "13":
                                echo "<label class='t-badge-success'>Reintegrado</label>";
                                break;
                              default:
                                echo "";
                            }
                            ?>
                            <?php if ($me->classroomFk->school_year >= Yii::app()->user->year) { ?>
                              <a href='<?php echo @Yii::app()->createUrl('enrollment/update', array('id' => $me->id)); ?>'
                                class="t-link-button--info">
                                <div class="t-icon-pencil t-icon"></div>
                              </a>
                            <?php } ?>

                          </div>
                          <div id="accordion-school-label" class="mobile-row">
                            <label class="accordion-label"><?php echo $me->schoolInepIdFk->name ?></label>
                          </div>
                        </div>
                        <div class="align-items--center">
                          <?php
                          if (!$modelStudentIdentification->isNewRecord && Yii::app()->features->isEnable("FEAT_SEDSP")) {
                            $sedspSync = StudentEnrollment::model()->findByPk($me->id)->sedsp_sync;
                            ?>
                            <div style="display: flex;align-items: center;margin-right: 10px;margin-top: 13px;">
                              <?php if ($sedspSync) { ?>
                                <div style="font-weight: bold;margin-right: 20px;">
                                  <img src="/themes/default/img/SyncTrue.png" title="Sincronizado"
                                    style="width: 20px;margin-right: 20px;">
                                </div>
                              <?php } else { ?>
                                <div style="font-weight: bold;margin-right: 20px;">
                                  <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/notSync.png"
                                    title="Não Sincronizado" style="width: 20px;margin-right: 20px;">
                                </div>
                              <?php } ?>
                            </div>
                          <?php } ?>
                          <span class="t-icon-down_arrow accordion-arrow-icon"></span>
                        </div>
                      </div>
                      <div class="ui-accordion-content">
                        <div class="mobile-row">
                          <label class="accordion-label--title">Turma:</label>
                          <a href='<?php echo Yii::app()->createUrl("classroom/update", array("id" => $me->classroomFk->id)); ?>'
                            class="t-link-button--info accordion-label">
                            <?php echo $me->classroomFk->name; ?>
                          </a>
                        </div>
                        <div class="mobile-row">
                          <label class="accordion-label--title">Turno:</label>
                          <label class="accordion-label">
                            <?php
                            switch ($me->classroomFk->turn) {
                              case "M":
                                echo "Matutino";
                                break;
                              case "T":
                                echo "Vespertino";
                                break;
                              case "N":
                                echo "Noturno";
                                break;
                              case "I":
                                echo "Integral";
                                break;
                              default:
                                echo "-";
                            }
                            ?>
                          </label>
                        </div>
                        <div class="mobile-row">
                          <label class="accordion-label--title">Ano:</label>
                          <label class="accordion-label"><?php echo $me->classroomFk->school_year ?></label>
                        </div>
                        <?php if (!empty($me->studentEnrollmentHistories)): ?>
                          <div class="mobile-row upper-margin">
                            <label class="accordion-label--title">Histórico:</label>
                          </div>
                          <div class="enrollment-history">
                            <?php foreach (array_reverse($me->studentEnrollmentHistories) as $studentEnrollmentHistory) {
                              switch ($studentEnrollmentHistory->status) {
                                case "1":
                                  $enrollment_date = "";
                                  if (isset($studentEnrollmentHistory->enrollment_date)) {
                                    $enrollment_date = date_create_from_format('Y-m-d', $studentEnrollmentHistory->enrollment_date)->format('d/m/Y');
                                  }
                                  echo "<div>• Matriculado " . $enrollment_date . "</div>";
                                  break;
                                case "2":
                                  $transfer_date = "";
                                  if (isset($studentEnrollmentHistory->transfer_date)) {
                                    $transfer_date = date_create_from_format('Y-m-d', $studentEnrollmentHistory->transfer_date)->format('d/m/Y');
                                  }
                                  echo "<div>• Transferido " . $transfer_date . "</div>";
                                  break;
                                case "3":
                                  echo "<div>• Cancelado</div>";
                                  break;
                                case "4":
                                  echo "<div>• Deixou de Frequentar </div>";
                                  break;
                                case "5":
                                  echo "<div>• Remanejado</div>";
                                  break;
                                case "6":
                                  echo "<div>• Aprovado</div>";
                                  break;
                                case "7":
                                  echo "<div>• Aprovado pelo Conselho</div>";
                                  break;
                                case "8":
                                  echo "<div>• Reprovado</div>";
                                  break;
                                case "9":
                                  echo "<div>• Concluinte</div>";
                                  break;
                                case "10":
                                  echo "<div>• Indeterminado</div>";
                                  break;
                                case "11":
                                  echo "<div>• Falecido</div>";
                                  break;
                                case "12":
                                  echo "<div>• Avançado</div>";
                                  break;
                                case "13":
                                  echo "<div>• Reintegrado</div>";
                                  break;
                                default:
                                  echo "";
                              }
                            } ?>
                          </div>
                        <?php endif; ?>
                        <div class="mobile-row upper-margin">
                          <label class="accordion-label--title">Formulários:</label>
                        </div>
                        <div class="reports">
                          <?php
                          $forms = unserialize(FORMS);
                          foreach ($forms as $item) {
                            $link = Yii::app()->createUrl('forms/' . $item['action'], array('type' => $type, 'enrollment_id' => $me->id));
                            ?>
                            <a class="<?= $item['name'] == "Ficha de Matrícula" ? 't-button-primary' : 't-button-secondary' ?> mobile-margin"
                              rel="noopener" target="_blank" href="<?= $link ?>">
                              <span class="t-icon-printer"></span>
                              <?php echo $item['name'] ?>
                            </a>
                            <?php
                          }
                          ?>
                          <a class="t-button-secondary" rel="noopener" target="_blank" href="<?php echo @Yii::app()->createUrl(
                            'forms/EnrollmentGradesReport',
                            array('enrollment_id' => $me->id)
                          ) ?>">
                            <span class="t-icon-printer"></span>
                            Ficha de Notas
                          </a>
                          <?php if (Yii::app()->features->isEnable("HAS_INDIVIDUALRECORD")): ?>
                          <a class="t-button-secondary" rel="noopener" target="_blank"
                            href="<?php echo @Yii::app()->createUrl('forms/IndividualRecord', array('enrollment_id' => $me->id)) ?>">
                            <span class="t-icon-printer"></span>
                            Ficha Individual
                          </a>
                          <?php endif; ?>
                        </div>
                        <?php if ($me->classroomFk->school_year == date('Y')) { ?>
                          <div class="mobile-row">
                            <label class="accordion-label--title">Questionários:</label>
                          </div>
                        <?php } ?>
                        <div class="reports">
                          <?php
                          if ($me->classroomFk->school_year == date('Y')) {
                            $date = date('Y-m-d');
                            $quizs = Quiz::model()->findAll(
                              'status = 1 AND init_date <=:init_date AND final_date >=:final_date',
                              [':init_date' => $date, ':final_date' => $date]
                            );
                            if (count($quizs) > 0) {
                              foreach ($quizs as $quiz) {
                                $link = Yii::app()->createUrl('quiz/default/answer', array('quizId' => $quiz->id, 'studentId' => $me->studentFk->id));
                                ?>
                                <a class="t-button-secondary mobile-margin" rel="noopener" target="_blank" href="<?= $link ?>">
                                  <span class="t-icon-printer"></span>
                                  <?php echo $quiz->name ?>
                                </a>
                                <?php
                              }
                            }
                          }
                          ?>
                        </div>
                        <?php if ($me->classroomFk->school_year >= Yii::app()->user->year) { ?>
                          <div class="row">
                            <a href='#' id="delete-enrollment" class="t-link-button--warning"
                              enrollment="<?= $me->id ?>">Excluir Matrícula
                            </a>
                          </div>
                        <?php } ?>
                      </div>
                      <?php
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!--  Tab Student-Health -->
        <div class="tab-pane" id="student-health">
          <!-- Titulo -->

          <div class="row">
            <div class="column clearleft is-two-fifths">
              <h3>Restrições</h3>
              <div class="t-field-checkbox-group" id="restrictions-checkbox">
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentRestrictions, 'celiac', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentRestrictions::model()->attributeLabels()['celiac']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentRestrictions, 'diabetes', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentRestrictions::model()->attributeLabels()['diabetes']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentRestrictions, 'hypertension', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentRestrictions::model()->attributeLabels()['hypertension']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentRestrictions, 'iron_deficiency_anemia', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentRestrictions::model()->attributeLabels()['iron_deficiency_anemia']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentRestrictions, 'sickle_cell_anemia', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentRestrictions::model()->attributeLabels()['sickle_cell_anemia']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentRestrictions, 'lactose_intolerance', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentRestrictions::model()->attributeLabels()['lactose_intolerance']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentRestrictions, 'malnutrition', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentRestrictions::model()->attributeLabels()['malnutrition']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentRestrictions, 'obesity', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentRestrictions::model()->attributeLabels()['obesity']; ?>
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $modelStudentRestrictions->others != null ?
                    "<input type='checkbox' id='others-check' checked>" :
                    "<input type='checkbox' id='others-check'>" ?>
                  <label class="t-field-checkbox">
                    <?php echo StudentRestrictions::model()->attributeLabels()['others']; ?>
                  </label>
                </div>
                <div class="row others-text-box" style="display: none;">
                  <?php echo $form->textArea($modelStudentRestrictions, 'others', array('rows' => 6, 'cols' => 50)); ?>
                </div>
              </div>
            </div>
            <!-- Vacinas -->
            <div class="column clearleft--on-mobile is-two-fifths">
              <h3>
                Vacinas
              </h3>
              <div class="t-field-checkbox-group vaccines-container">
                <?php foreach ($vaccines as $vaccine): ?>
                  <div class="t-field-checkbox" id="vaccine-checkbox">
                    <?php echo CHtml::activeCheckBox($vaccine, "vaccine_id[]", array('checked' => in_array($vaccine->id, $studentVaccinesSaves), 'value' => $vaccine->id, 'uncheckValue' => null, 'class' => 'vaccine-checkbox', 'code' => $vaccine->code)); ?>
                    <label class="t-field-checkbox">
                      <?= $vaccine->name; ?>
                    </label>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="column clearleft--on-mobile is-two-fifths">
              <h3>
                Transtornos
              </h3>
              <div class="t-field-checkbox-group" id="transtorno-checkbox">
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentDisorder, 'tdah', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    Transtorno do déficit de atenção com hiperatividade (TDAH)
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentDisorder, 'depressao', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    Transtorno depressivo (depressão)
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentDisorder, 'tab', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    Transtorno bipolar (TAB)
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentDisorder, 'toc', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    Transtorno obsessivo compulsivo (TOC)
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentDisorder, 'tag', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    Transtorno de ansiedade generalizada (TAG)
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentDisorder, 'tod', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    Distúrbio desafiador e de oposição (TOD)
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $form->checkBox($modelStudentDisorder, 'tcne', array('value' => 1, 'uncheckValue' => 0)); ?>
                  <label class="t-field-checkbox">
                    Transtorno de conduta não especificado
                  </label>
                </div>
                <div class="t-field-checkbox">
                  <?php echo $modelStudentDisorder->others != null ?
                    "<input type='checkbox' id='others-check' checked>" :
                    "<input type='checkbox' id='others-check'>" ?>
                  <label class="t-field-checkbox">
                    Outros transtornos de conduta
                  </label>
                </div>
                <div class="row others-text-box" style="display: none;">
                  <?php echo $form->textArea($modelStudentDisorder, 'others', array('rows' => 6, 'cols' => 50)); ?>
                </div>
              </div>
            </div>
          </div>
          <?php $this->endWidget(); ?>
        </div>
        <div class="row reverse show--tablet">
          <div class="t-buttons-container">
            <div class="column clearfix">
              <a data-toggle="tab" class='t-button-secondary prev'
                style="display:none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
            </div>
            <div class="column clearfix">
              <?= $modelStudentIdentification->isNewRecord ? "<a data-toggle='tab' class='t-button-primary nofloat next'>" . Yii::t('default', 'Next') . "</a>" : '' ?>
              <a class="t-button-primary  last save-student" type="button">
                <?= $modelStudentIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade modal-content" id="importStudentFromSEDSP" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
          <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
            style="vertical-align: -webkit-baseline-middle">
        </button>
        <h4 class="modal-title" id="myModalLabel">Importar aluno da SEDSP</h4>
      </div>
      <form method="post"
        action="<?php echo $this->createUrl('sedsp/default/UpdateStudentFromSedsp', array('id' => $modelStudentIdentification->id, 'gov_id' => $modelStudentIdentification->gov_id)); ?>">
        <div class="centered-loading-gif">
          <i class="fa fa-spin fa-spinner"></i>
        </div>
        <div class="modal-body">
          <div class="alert alert-error no-show"></div>
          <div class="row-fluid">
            Você tem certeza?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
            </button>
            <button type="button" class="btn btn-primary import-student-button">Confirmar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php

if (isset($_GET['censo']) && isset($_GET['id'])) {
  $this->widget('application.widgets.AlertCensoWidget', array('prefix' => 'student', 'dataId' => $_GET['id']));
}
?>

<script type="text/javascript">
  var formIdentification = '#StudentIdentification_';
  var formDocumentsAndAddress = '#StudentDocumentsAndAddress_';
  var formEnrollment = '#StudentEnrollment_';
  var updateDependenciesURL = '<?php echo yii::app()->createUrl('enrollment/updatedependencies') ?>';
  var filled = -1;
</script>
