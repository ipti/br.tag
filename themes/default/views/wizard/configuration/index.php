<?php
/* @var $this ClassroomConfigurationControler */
/* @var $form ActiveForm */
/* @var $title String */
$baseUrl = Yii::app()->baseUrl;
$baseUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . 'sass/css/main.css');

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'school-configuration-form',
    'enableAjaxValidation' => false
));

$this->breadcrumbs = array(
    Yii::t('default', 'Reaproveitamento das Turmas'),

);
?>

<div class="row-fluid">
    <div class="span12">
        <h1>
            <?php echo Yii::t('default', 'Configurarion'); ?>
        </h1>
    </div>
</div>
<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success')) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">
        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <div class="tab-pane row active" id="student">
                    <div class="column">
                        <div class="row">
                            <div class="t-cards">
                                <a href="<?php echo yii::app()->createUrl('wizard/Configuration/school') ?>" class="t-cards-link">
                                    <div class="t-cards-title"><img class="t-cards-icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/escola.svg" />Escola</div>
                                    <div class="t-cards-text">Edite as informações da sua escola</div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="t-cards">
                                <a href="<?php echo yii::app()->createUrl('wizard/Configuration/classroom') ?>" class="t-cards-link">
                                    <div class="t-cards-title"><img class="t-cards-icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/turma.svg" />Turmas</div>
                                    <div class="t-cards-text">Faça o reaproveitamento de turmas</div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="t-cards">
                                <a href="<?php echo yii::app()->createUrl('wizard/Configuration/student') ?>" class="t-cards-link">
                                    <div class="t-cards-title"><img class="t-cards-icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/aluno.svg" />Aluno</div>
                                    <div class="t-cards-text">Realize o reaproveitamento de alunos</div>
                                </a>
                            </div>
                        </div>
                        <!-- <div class="column"></div> -->
                    </div>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>