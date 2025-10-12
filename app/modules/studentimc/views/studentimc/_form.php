<?php
/* @var $this StudentIMCController */
/* @var $model StudentIMC */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/functions.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/pagination.js', CClientScript::POS_END);

?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'student-imc-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="main form-content">
        <div class="tag-inner">
            <div class="row">
                <div class="column">
                    <h1><?php echo $title; ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <div class="t-tabs js-tab-control">
                        <ul class="tab-student-health t-tabs__list">
                            <li id="tab-student-imc" class="t-tabs__item active">
                                <a class="t-tabs__link first" href="#student-imc" data-toggle="tab">
                                    <span class="t-tabs__numeration">1</span>
                                    Dados Antropométricos
                                </a>
                                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                            </li>
                            <li id="tab-student-diseases" class="t-tabs__item ">
                                <a class="t-tabs__link" href="#student-diseases" data-toggle="tab">
                                    <span class="t-tabs__numeration">2</span>
                                    Doenças e Distúrbios
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <!-- Dados Antropométricos -->
                <div class="tab-pane active" id="student-imc">
                    <div class="row">
                        <div class="column">
                            <h3>Dados Antropométricos</h3>
                        </div>
                    </div>

                    <?php echo $form->errorSummary($model); ?>

                    <div class="row">
                        <div class="column">
                            <div class="t-field-text">
                                <?php echo $form->labelEx($model, 'height', array('class' => 't-field-text__label')); ?>
                                <?php echo $form->textField($model, 'height', array('class' => 't-field-text__input js-height')); ?>
                                <?php echo $form->error($model, 'height'); ?>
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-text">
                                <?php echo $form->labelEx($model, 'weight', array('class' => 't-field-text__label')); ?>
                                <?php echo $form->textField($model, 'weight', array('class' => 't-field-text__input js-weight')); ?>
                                <?php echo $form->error($model, 'weight'); ?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="column">
                            <div class="t-field-text">
                                <?php echo $form->labelEx($model, 'IMC', array('class' => 't-field-text__label')); ?>
                                <?php echo $form->textField($model, 'IMC', array('class' => 't-field-text__input js-imc', 'readonly' => 'readonly')); ?>
                                <?php echo $form->error($model, 'IMC'); ?>
                            </div>
                        </div>
                        <div class="column <?= $model->isNewRecord ? "hide" : "" ?>">
                            <?php echo $form->labelEx($model, 'created_at', array('class' => 't-field-text__label')); ?>
                            <?php echo $form->textField($model, 'created_at', array('class' => 't-field-text__input', 'disabled' => 'disabled')); ?>
                            <?php echo $form->error($model, 'created_at'); ?>
                        </div>
                        <div class="column <?= $model->isNewRecord ? "" : "hide" ?>"></div>
                    </div>


                    <div class="row">
                        <div class="column">
                            <div class="t-field-tarea">
                                <?php echo $form->labelEx($model, 'observations', array('class' => 't-field-tarea__label')); ?>
                                <?php echo $form->textArea($model, 'observations', array('rows' => 6, 'cols' => 60, 'maxlength' => 500, 'class' => 't-field-tarea__input', 'style' => 'height:150px;')); ?>
                                <?php echo $form->error($model, 'observations'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doenças e Distúrbios -->
                <div class="tab-pane" id="student-diseases">
                    <div class="row">
                        <div class="column">
                            <div class="t-field-checkbox-group">
                                <h3>
                                    Deficiências nutricionais
                                </h3>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'iron_deficiency_anemia', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'iron_deficiency_anemia', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'iron_deficiency_anemia'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'hypovitaminosis_a', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'hypovitaminosis_a', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'hypovitaminosis_a'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'rickets', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'rickets', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'rickets'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'scurvy', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'scurvy', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'scurvy'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'iodine_deficiency', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'iodine_deficiency', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'iodine_deficiency'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'protein_energy_malnutrition', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'protein_energy_malnutrition', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'protein_energy_malnutrition'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-checkbox-group">
                                <h3>
                                    Excessos nutricionais
                                </h3>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'overweight', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'overweight', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'overweight'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'obesity', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'obesity', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'obesity'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'dyslipidemia', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'dyslipidemia', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'dyslipidemia'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'hyperglycemia_prediabetes', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'hyperglycemia_prediabetes', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'hyperglycemia_prediabetes'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'type2_diabetes_mellitus', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'type2_diabetes_mellitus', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'type2_diabetes_mellitus'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <div class="t-field-checkbox-group">
                                <h3>Distúrbios alimentares</h3>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'anorexia_nervosa', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'anorexia_nervosa', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'anorexia_nervosa'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'bulimia_nervosa', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'bulimia_nervosa', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'bulimia_nervosa'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'binge_eating_disorder', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'binge_eating_disorder', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'binge_eating_disorder'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-checkbox-group">
                                <h3>Alterações relacionadas à alimentação</h3>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'lactose_intolerance', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'lactose_intolerance', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'lactose_intolerance'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'celiac_disease', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'celiac_disease', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'celiac_disease'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'food_allergies', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'food_allergies', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'food_allergies'); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="column">
                            <div class="t-field-checkbox-group">
                                <h3>Doenças respiratórias</h3>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'asthma', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'asthma', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'asthma'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'chronic_bronchitis', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'chronic_bronchitis', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'chronic_bronchitis'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'allergic_rhinitis', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'allergic_rhinitis', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'allergic_rhinitis'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'chronic_sinusitis', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'chronic_sinusitis', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'chronic_sinusitis'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-checkbox-group">
                                <h3>Doenças metabólicas e endócrinas</h3>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'diabetes_mellitus', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'diabetes_mellitus', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'diabetes_mellitus'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'hypothyroidism', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'hypothyroidism', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'hypothyroidism'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'hyperthyroidism', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'hyperthyroidism', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'hyperthyroidism'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'dyslipidemia_metabolic', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'dyslipidemia_metabolic', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'dyslipidemia_metabolic'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <div class="t-field-checkbox-group">
                                <h3>Doenças cardiovasculares</h3>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'arterial_hypertension', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'arterial_hypertension', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'arterial_hypertension'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'congenital_heart_disease', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'congenital_heart_disease', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'congenital_heart_disease'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-checkbox-group">
                                <h3>Doenças gastrointestinais</h3>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'chronic_gastritis', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'chronic_gastritis', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'chronic_gastritis'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'gastroesophageal_reflux_disease', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'gastroesophageal_reflux_disease', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'gastroesophageal_reflux_disease'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <div class="t-field-checkbox-group">
                                <h3>
                                    Doenças neurológicas
                                </h3>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'epilepsy', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'epilepsy', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'epilepsy'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($disorder, 'tdah', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($disorder, 'tdah', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($disorder, 'tdah'); ?>
                                </div>
                                <div class="t-field-checkbox">
                                    <?php echo $form->checkBox($studentIdentification, 'deficiency_type_autism', array('class' => '', 'value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->labelEx($studentIdentification, 'deficiency_type_autism', array('class' => 't-field-checkbox')); ?>
                                    <?php echo $form->error($studentIdentification, 'deficiency_type_autism'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>


<?php
$isNew = $model->isNewRecord;
$btnNext = "<a data-toggle='tab' class='t-button-primary next'>" . Yii::t('default', 'Next') . "</a>";
$btnPrev = "<a data-toggle='tab' class='t-button-secondary prev' style='display:none;'>" . Yii::t('default', 'Previous') . "<i></i></a>";
$btnSubmit = "<button class='t-button-primary last' type='submit' style='" . ($isNew ? "display:none;" : "") . "'>" .
    ($isNew ? Yii::t('default', 'Create') : Yii::t('default', 'Save')) . "</button>";
?>

<!-- Desktop -->
<div class="row buttons show--desktop" style="justify-content: flex-end; margin-right: 20px;">
    <?= $btnPrev ?>
    <?= $isNew ? $btnNext : '' ?>
    <?= $btnSubmit ?>
</div>

<!-- Tablet -->
<div class="row show--tablet">
    <div class="column">
        <?= $btnPrev ?>
    </div>
</div>
<div class="row show--tablet">
    <div class="column">
        <?= $isNew ? str_replace("next", "t-button-primary nofloat next", $btnNext) : '' ?>
        <button class="t-button-primary last" type="submit"
            style="<?= $isNew ? 'display:none;' : '' ?> width:100%;">
            <?= $isNew ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
        </button>
    </div>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
