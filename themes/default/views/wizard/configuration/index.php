<?php
/* @var $this ClassroomConfigurationControler */
/* @var $form ActiveForm */
/* @var $title String */
$baseUrl = Yii::app()->baseUrl;
$baseUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl. 'sass/css/main.css');

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
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">	
        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <div class="tab-pane active" id="student">
                    <div class="row-fluid">	
                        <div class=" span6"> 
                            <div class="row-fluid">
                                <div class="t-cards">
                                    <a href="<?php echo yii::app()->createUrl('wizard/Configuration/school')?>" class="t-cards-link">
                                        <span class="t-cards-icon"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/escola.svg" alt="icone de escola" /></span>
                                        <span class="t-cards-title">Escola</span>
                                        <span class="t-cards-subtitle">Como funciona a página inicial do tag?</span>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                                <div class="t-cards">
                                    <a href="<?php echo yii::app()->createUrl('wizard/Configuration/classroom')?>" class="t-cards-link">
                                        <span class="t-cards-icon"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/turma.svg" alt="icone de turmas"/></span>
                                        <span class="t-cards-title">Turmas</span>
                                        <span class="t-cards-subtitle">Como funciona a página inicial do tag?</span>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                                <div class="t-cards">
                                    <a href="<?php echo yii::app()->createUrl('wizard/Configuration/student')?>" class="t-cards-link">
                                        <span class="t-cards-icon"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/aluno.svg" alt="icone de aluno"/></span>
                                        <span class="t-cards-title">Aluno</span>
                                        <span class="t-cards-subtitle">Como funciona a página inicial do tag?</span>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                                <div class="span3">
                                </div>
                            </div>
                        </div>
                        <div class=" span6">
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>