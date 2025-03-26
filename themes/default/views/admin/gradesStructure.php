<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>


<?php

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/grades-structure.js?v=' . TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - Estrutura de Unidades e Avaliações');
?>
<?php //echo $form->errorSummary($model);
?>

<div class="main">
    <?php
    $form = $this->beginWidget(
        'CActiveForm',
        array(
            'id' => 'classes-form',
            'enableAjaxValidation' => false,
        )
    );
    ?>

<div class="row">
    <div class="column">
        <div class="column">
            <h1>Estrutura de Unidades e Avaliações</h1>
        </div>
        <div class="buttons row grades-buttons">
            <!-- <a class='t-button-primary save-and-reply'>Salvar e Replicar</a> -->
            <a class='t-button-primary save' style="margin-right: 2.5em;">Salvar</a>
        </div>
    </div>
</div>
    <div class="column js-grades-container">
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div class="alert-media-fields no-show alert"></div>
        <div class="alert-required-fields no-show alert alert-error"></div>
        <div class="column">
            <div class="row">
                <div class="t-field-text column is-three-fifths clearfix">
                    <label class='t-field-text__label--required'>Nome:</span></label>
                    <input type='text' class='t-field-text__input js-grade-rules-name' placeholder='Nome'>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="row margin-bottom-none">
                <div class="t-field-select column is-three-fifths clearfix">

                    <?php echo CHtml::label(yii::t('default', 'Etapas'), 'modality_fk', array('class' => 't-field-select__label--required', 'style' => 'width: 54px;')); ?>
                        <?php
                        echo $form->dropDownList(
                            $gradeUnity,
                            'edcenso_stage_vs_modality_fk',
                            CHtml::listData($stages, 'id', 'name'),
                            array(
                                'key' => 'id',
                                'class' => ' t-field-select__input js-stage-select select-search-on select2-container t-multiselect multiselect',
                                'multiple' => 'multiple',
                                'prompt' => 'Selecione o estágio...',
                                'style' => 'width: 100%;'

                            )
                        );
                        ?>

                </div>
            </div>
        </div>
        <div class="column js-grades-rules-container is-three-fifths" style="display: none;">
            <h2>Regras de aprovação</h2>
            <p class="subheading">
                Configure as regras básicas para aprovação dos alunos
            </p>
            <div class="t-field-select">
                <label class="t-field-select__label--required">Modelo de avalição</span></label>
                <select class="js-rule-type select-search-on t-field-select__input">
                    <option value="N">Númerico</option>
                    <option value="C">Conceito</option>
                </select>
            </div>
            <div class="numeric-fields t-field-select">
                <label class="t-field-select__label--required">Calculo da média final</span></label>
                <select class="calculation-final-media select-search-on t-field-select__input">
                    <?php foreach ($formulas as $formula):
                    ?>

                        <option value="<?= $formula->id ?>">
                            <?= $formula->name ?>
                        </option>
                    <?php
                          endforeach;
                    ?>
                </select>
            </div>
            <div class="numeric-fields t-field-text">
                <label class="t-field-text__label--required">Média de Aprovação</span></label>
                <input type="text" class="approval-media t-field-text__input">
            </div>
            <div class="numeric-fields t-field-checkbox">
                <?php echo CHtml::checkbox(
                    'has_final_recovery',
                    false,
                    array(
                        'class' => 't-field-checkbox__input js-has-final-recovery'
                    )
                ) ?>
                <?php echo CHtml::label("Incluir recuperação final?", 'has_final_recovery', array('class' => 't-field-checkbox__label', 'id' => 'active-label')); ?>
            </div>
        </div>
        <div class="js-alert-save-recovery-first no-show alert"></div>
        <div class="column js-grades-rules-container" style="display: none;">
            <div class="row">
                <h2>Definição das unidades</h2>
                <a href="#new-unity" id="new-unity" class="js-new-unity t-button-primary">
                    <img alt="Unidade" src="/themes/default/img/buttonIcon/start.svg">Unidade
                </a>
            </div>
            <p class="subheading">
                Gerencie a quantidade de unidades avaliativas, elas podem ser bimestres, semestres e outros
            </p>
        </div>
        <div id="accordion js-grades-rules-container"
            class="grades-structure-container t-accordeon-quaternary js-grades-structure-container accordion"
           >
        </div>
        <div class="js-alert-save-unities-first no-show alert"></div>
        <div class="column js-grades-rules-container js-partial-recoveries-header" style="display: none;">
            <div class="row">
                <h2>Definição das Recuperações Parciais</h2>
                <a id="new-partial-recovery" class="js-new-partial-recovery t-button-primary disabled">
                    <img alt="Recuperação" src="/themes/default/img/buttonIcon/start.svg">Recuperação Parcial
                </a>
            </div>
            <p class="subheading">
                Gerencie a quantidade de recuperações parciais
            </p>
        </div>
        <div class="row">
            <div class="column is-three-fifths">
                <div id="accordion-partial-recovery" class="t-accordeon-quaternary js-partial-recoveries-container">
                </div>
            </div>
        </div>


        <div class="column js-recovery-form  is-three-fifths" style="display: none;">
            <h2>Regras de recuperação final</h2>
            <p class="subheading">
                Configure as regras básicas para aprovação dos alunos
            </p>
            <input type='hidden' class="final-recovery-unity-id">
            <input type='hidden' class="final-recovery-unity-type" value="RF">
            <input type="hidden" class="final-recovery-unity-operation" value="create">
            <div class="t-field-text js-recovery-media-visibility">
                <label class="t-field-text__label--required">Média de Rec. Final</span></label>
                <input type="text" class="final-recover-media t-field-text__input">
            </div>
            <div class="t-field-text" style="margin-top: 16px">
                <label class='t-field-text__label--required'>Nome:</span></label>
                <input type='text' class='t-field-text__input final-recovery-unity-name'
                    placeholder='Recuperação Final'>
            </div>
            <div class="t-field-select js-calculation">
                <label class='t-field-select__label--required'>Forma de cálculo:</span></label>
                <select class='t-field-select__input select-search-on final-recovery-unity-calculation'>
                    <?php foreach ($formulas as $formula):?>

                        <option value="<?= $formula->id ?>">
                            <?= $formula->name ?>
                        </option>
                    <?php
                          endforeach;
                    ?>
                </select>
            </div>
            <div class="row weights-final-recovery hide">
                <div class="t-field-text">
                    <label for="PesoRecFinal" class="t-field-text__label--required">Peso da média anual</label>
                    <input type="text" name="PesoRecFinal" class="t-field-text__input weight-final-recovery weight" />
                </div>
                <div class="t-field-text">
                    <label for="PesoRecFinal" class="t-field-text__label--required">Peso da recuperação final</label>
                    <input type="text" name="PesoRecFinal" class="t-field-text__input weight-final-media weight" />
                </div>
            </div>

            <div class="t-field-select js-final-recovery-fomula hide">
                <label class="t-field-select__label">Fórmula da média da recuperação final</label>
                <select class="t-field-select__input select-search-on js-final-recovery-fomula-select">
                    <option value="Médias dos Semestres">Média aritimética da soma das médias dos semestres mais a recuperação final</option>
                    <option value="Média Anual">Média aritimética da média anual mais a recuperação final</option>
                </select>
            </div>
        </div>
    </div>
    <div class="save-unity-loading-gif">
        <i class="fa fa-spin fa-spinner fa-3x"></i>
    </div>
</div>

<div class="formulas">
    <?php foreach ($formulas as $formula): ?>
        <option value="<?= $formula->id ?>"><?= $formula->name ?></option>
    <?php endforeach; ?>
</div>
<div class="modal fade modal-content" id="js-saveandreply-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
                    style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Salvar e Replicar para</h4>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="radios-container">
                    <div class="radio">
                        <label><input type="radio" class="reply-option" name="reply-option" value="A"><span>Toda a
                                Matriz Curricular.</span></label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" class="reply-option" name="reply-option" value="S"><span>Todas as
                                etapas de <span class="stagemodalityname"></span>.</span></label>
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: 50px">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                    </button>
                    <button type="button" class="btn btn-primary js-save-and-reply-button" data-dismiss="modal">
                        Salvar e Replicar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $this->endWidget(); ?>
</div>
