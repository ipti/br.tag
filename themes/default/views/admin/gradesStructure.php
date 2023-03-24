<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>


<?php

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/grades-structure.js', CClientScript::POS_END);

$this->setPageTitle('TAG - Estrutura de Notas');
?>
<?php //echo $form->errorSummary($model); ?>

<div class="main">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'classes-form',
        'enableAjaxValidation' => false,
        'action' => CHtml::normalizeUrl(array('classes/checkValidInputs')),
    ));
    ?>
    <div class="row-fluid">
        <div class="span12">
            <h1>Estrutura de Notas</h1>
            <div class="buttons row grades-buttons">
                <a class='t-button-primary save-and-reply'>Salvar e Replicar</a>
                <a class='t-button-primary save'>Salvar</a>
            </div>
        </div>
    </div>
    <div class="tag-inner">

        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div class="alert-required-fields no-show alert alert-error"></div>
        <div class="row filter-bar margin-bottom-none">
            <div>
                <?php echo CHtml::label(yii::t('default', 'Stage') . "*", 'modality_fk', array('class' => 'control-label required', 'style' => 'width: 54px;')); ?>
                <div>
                    <?php
                    echo $form->dropDownList($gradeUnity, 'edcenso_stage_vs_modality_fk', CHtml::listData($stages, 'id', 'name'), array(
                        'key' => 'id',
                        'class' => 'select-search-on control-input grades-structure-input',
                        'prompt' => 'Selecione o estágio...',

                    ));
                    ?>
                </div>
                <div>
                    <?php echo CHtml::label(yii::t('default', 'Discipline') . "*", 'discipline_fk', array('class' => 'control-label required', 'style' => 'width: 80px;')); ?>
                    <div class="coursePlan-input"><?php
                        echo $form->dropDownList($gradeUnity, 'edcenso_discipline_fk', array(), array(
                            'key' => 'id',
                            'class' => 'select-search-on control-input grades-structure-input',
                            'initVal' => $gradeUnity->edcenso_discipline_fk,
                            'prompt' => 'Selecione a disciplina...',
                        ));
                        ?>
                    </div>
                </div>
                <i class="js-grades-structure-loading fa fa-spin fa-spinner"></i>
            </div>
        </div>
        <div class="js-grades-structure-container">
            <div class="row">
                <a href="#new-unity" id="new-unity" class="js-new-unity t-button-primary">
                    <img alt="Unidade" src="/themes/default/img/buttonIcon/start.svg">Unidade
                </a>
            </div>
        </div>
    </div>
    <div class="formulas">
        <?php foreach ($formulas as $formula): ?>
            <option value="<?= $formula->id ?>"><?= $formula->name ?></option>
        <?php endforeach; ?>
    </div>
    <?php $this->endWidget(); ?>
</div>