<?php

/**
 * @var ClassesController $this ClassesController
 * @var CActiveDataProvider $dataProvider CActiveDataProvider
 *
 */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/classes/class-contents/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classes/class-contents/validateFunctions.js?v=' . TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Classes Contents'));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classes-form',
    'enableAjaxValidation' => false,
    'action' => CHtml::normalizeUrl(array('classes/saveClassContents')),
));

$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

?>
<div class="main">
    <div class="row">
        <h1 class="title1"><?php echo Yii::t('default', 'Class Contents'); ?></h1>
    </div>
    <div class="mobile-row justify-content--space-between">
        <div class="t-buttons-container auto-width">
            <a id="print" class='t-button-secondary hide printButton'>
                <span class="t-icon-printer"></span>
                <?php echo Yii::t('default', 'Print') ?>
            </a>
        </div>
    </div>
    <table class="table table-bordered table-striped visible-print"
           summary="Tabela relacionada a informações da escola">
        <tr>
            <th scope="school">Escola:</th>
            <td colspan="7"><?php echo $school->inep_id . " - " . $school->name ?></td>
        <tr>
        <tr>
            <th scope="uf">Estado:</th>
            <td colspan="1"><?php echo $school->edcensoUfFk->name . " - " . $school->edcensoUfFk->acronym ?></td>
            <th scope="city">Municipio:</th>
            <td colspan="1"><?php echo $school->edcensoCityFk->name ?></td>
            <th scope="address">Endereço:</th>
            <td colspan="1"><?php echo $school->address ?></td>
        <tr>
        <tr>
            <th scope="location">Localização:</th>
            <td colspan="1"><?php echo $school->location == 1 ? "URBANA" : "RURAL" ?></td>
            <th scope="Administrative Dependence">Dependência Administrativa:</th>
            <td colspan="3"><?php
                $ad = $school->administrative_dependence;
                switch ($ad) {
                    case 1:
                        echo "FEDERAL";
                        break;
                    case 2:
                        echo "ESTADUAL";
                        break;
                    case 3:
                        echo "MUNICIPAL";
                        break;
                    default:
                        echo "PRIVADA";
                        break;
                }
                ?></td>
        <tr>
    </table>
    <table class="table table-bordered table-striped visible-print" summary="Tabela de filtros">
        <tr>
            <th>Turma:</th>
            <td colspan="1" id="classroomValue"></td>
        <tr>
        <tr>
            <th>Mês:</th>
            <td colspan="1" id="monthValue"></td>
        <tr>
        <tr>
            <th>Componente Curricular/ Eixo:</th>
            <td colspan="1" id="disciplinesValue"></td>
        <tr>
    </table>
    <br>
    <div>
        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div class="alert-save no-show alert alert-success">
            Aulas ministradas atualizadas com sucesso!
        </div>
        <div class="alert-required-fields no-show alert alert-error">
            Os campos com * são obrigatórios.
        </div>
        <div id="select-container" class="tablet-row align-items--center-on-desktop">
            <div class="mobile-row">
                <div class="column clearleft">
                    <div class="t-field-select">
                        <?php echo CHtml::label(yii::t('default', 'Classroom'), 'classroom', array('class' => 'control-label t-field-select__label--required', 'style' => 'width: 53px;')); ?>
                        <select class="select-search-on t-field-select__input " id="classroom" name="classroom">
                            <option value="">Selecione a turma</option>
                            <?php foreach ($classrooms as $classroom) : ?>
                                <option value="<?= $classroom->id ?>"
                                        fundamentalmaior="<?= !TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk) ? "1" : "0" ?>"><?= $classroom->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="column month-container">
                    <div class="t-field-select">
                        <?php echo CHtml::label(yii::t('default', 'Month') . "/Ano", 'month', array('class' => 'control-label t-field-select__label--required', 'style' => 'width: 80px;')); ?>
                        <select class="select-search-on t-field-select__input" id="month"
                                style="min-width: 185px;"></select>
                    </div>
                </div>
            </div>
            <div class="mobile-row helper printSelect selectComponente">
                <div class="column clearleft on-tablet disciplines-container" style="display: none;">
                    <div class="t-field-select">
                        <?php echo CHtml::label(yii::t('default', 'Discipline'), 'disciplines', array('class' => 'control-label t-field-select__label--required')); ?>
                        <?php
                        echo CHtml::dropDownList('disciplines', '', array(), array(
                            'key' => 'id',
                            'class' => 'select-search-on t-field-select__input '
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="column clearleft on-tablet align-items--center ">
                <img class="loading-class-contents" height="30px" width="30px"
                     src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
            </div>
        </div>
        <div class="clear"></div>
        <div id="given-classes" class="row"></div>
        <div class="widget" id="widget-class-contents" style="display:none; margin-top: 8px;">
            <table id="class-contents" class="tag-table-secondary table-bordered"
                   aria-labelledby="create class contents">
                <thead>
                <th class="center">1</th>
                </thead>
                <tbody>
                <tr>
                    <td class="center">1</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="error-badge"></div>
    </div>
    <div class="row">
        <div class="t-buttons-container printButton">
            <a id="save-button-mobile"
               class='t-button-primary align-items--center hide'><?php echo Yii::t('default', 'Save') ?></a>
        </div>
    </div>

    <div class="modal fade t-modal-container" id="js-classroomdiary" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="t-modal__header">
                <h4 class="t-title" id="myModalLabel">Diário de Aula</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="">
                </button>
            </div>
            <form method="post">
                <input type="hidden" class="classroom-diary-day">
                <div class="t-modal__body">
                    <div class="t-field-tarea">
                        <label class="t-field-tarea__label">Diário de Aula Geral</label>
                        <textarea class="t-field-tarea__input large classroom-diary-textarea"
                                  readonly placeholder="Digite"></textarea>
                    </div>

                    <label>Diário de Aula por Aluno</label>
                    <div class="alert alert-error classroom-diary-no-students no-show">Não há alunos matriculados na
                        turma.
                    </div>
                    <div class="accordion-students"></div>
                </div>
        </div>
        </form>
    </div>
</div>
<style>
    @media print {
        .title1 {
            padding-left: 30px;
        }

        .printButton {
            display: none;
        }

        .saveButton {
            display: none;
        }

        .printSelect {
            margin-left: 0px;
        }

        .classroom-diary-button {
            display: none;
        }

        .selectComponente {
            padding-left: 0px;
        }

        .tablet-row {
            display: none;
        }
    }


</style>
<?php $this->endWidget(); ?>
