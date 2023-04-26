<?php
/* @var $this DefaultController */

$this->setPageTitle('TAG - ' . Yii::t('default', 'SEDSP'));

$this->breadcrumbs = array(
    $this->module->id,
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($themeUrl . '/css/template2.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/sass/css/main.css');
$cs->registerCssFile($baseUrl . '/css/sedsp.css');
$cs->registerScriptFile($baseScriptUrl . '/common/js/functions.js?v=1.1', CClientScript::POS_END);
?>

<div id="mainPage" class="main">
    <div class="row">
        <div class="column">
            <h1>SEDSP</h1>
        </div>
    </div>
    <div class="alert alert-error alert-error-export" style="display: none;"></div>
    <?php if (Yii::app()->user->hasFlash('error')) : ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('success')) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
        <br />
    <?php endif ?>
    <div class="container-box" style="display: grid;">
        <p>Alunos</p>

        <a href="#" data-toggle="modal" data-target="#add-student-ra" target="_blank">
            <button type="button" class="report-box-container">
                <div class="pull-left" style="margin-right: 20px;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sedspIcon/graduation-cap.svg" />
                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                </div>
                <div class="pull-left">
                    <span class="title">Cadastrar Aluno</span><br>
                    <span class="subtitle">Digite a RA para cadastrar o Aluno</span>
                </div>
            </button>
        </a>

        <a href="#" data-toggle="modal" data-target="#add-classroom" target="_blank">
            <button type="button" class="report-box-container">
                <div class="pull-left" style="margin-right: 20px;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sedspIcon/classroom.svg" />
                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                </div>
                <div class="pull-left">
                    <span class="title">Cadastrar Turma</span><br>
                    <span class="subtitle">Digite a código para cadastrar uma turma</span>
                </div>
            </button>
        </a>
        
        <a href="#" data-toggle="modal" data-target="#add-school" target="_blank">
            <button type="button" class="report-box-container">
                <div class="pull-left" style="margin-right: 20px;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sedspIcon/school.svg" />
                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                </div>
                <div class="pull-left">
                    <span class="title">Cadastrar Escola</span><br>
                    <span class="subtitle">Digite a código para cadastrar uma turma</span>
                </div>
            </button>
        </a>


        <a href="<?php echo Yii::app()->createUrl('sedsp/default/manageRA') ?>">
            <button type="button" class="report-box-container">
                <div class="pull-left" style="margin-right: 20px;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sedspIcon/generate.svg" />
                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                </div>
                <div class="pull-left">
                    <span class="title">Gerar RA</span><br>
                    <span class="subtitle">Trazer ou enviar um RA para a sede</span>
                </div>
            </button>
        </a>
    </div>
</div>
