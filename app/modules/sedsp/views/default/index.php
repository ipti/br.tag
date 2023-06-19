<?php
/* @var $this DefaultController */

$this->setPageTitle('TAG - ' . Yii::t('default', 'SEDSP'));

$this->breadcrumbs = array(
    $this->module->id,
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();

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
                    <span class="subtitle">Digite o RA para cadastrar o Aluno</span>
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
                    <span class="subtitle">Cadastrar turma podendo trazer alunos matriculados</span>
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
                    <span class="subtitle">Digite o nome da escola e do município</span>
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

<!-- Modals -->
<div class="row">
    <div class="modal fade modal-content" id="add-student-ra" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Cadastrar Aluno</h4>
        </div>
        <form class="form-vertical" id="addStudentRA" action="<?php echo yii::app()->createUrl('sedsp/default/AddStudentWithRA') ?>" method="post" onsubmit="return validateFormStudent();">
            <div class="modal-body">
                <div class="row-fluid">
                    <div class=" span12">
                        <?php echo CHtml::label(yii::t('default', 'RA do Aluno'), 'year', array('class' => 'control-label')); ?>
                        <input name="ra" id="ra" type="number" placeholder="Digite o RA" style="width: 97.5%;" oninput="validateRA();" maxlength="12" required>
                        <div id="ra-warning" style="display: none;color:#D21C1C">O RA deve ter exatamente 12 dígitos.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                    <button class="btn btn-primary" url="<?php echo Yii::app()->createUrl('sedsp/default/AddStudentWithRA'); ?>" type="submit" value="Alterar" style="background: #3F45EA; color: #FFFFFF;"> Cadastrar </button>
                </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="modal fade modal-content" id="add-school" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Cadastrar Escola</h4>
        </div>
        <form class="form-vertical" id="addSchool" action="<?php echo yii::app()->createUrl('sedsp/default/AddSchool') ?>" method="post">
            <div class="modal-body">
                <div class="row-fluid">
                    <div class=" span12">
                        <?php echo CHtml::label(yii::t('default', 'Nome da Escola'), 'school_id', array('class' => 'control-label')); ?>
                        <input type="text" name="schoolName" id="schoolName" style="width: 97.7%;" placeholder="Digite o Nome da Escola">
                        <?php echo CHtml::label(yii::t('default', 'Nome do Município'), 'school_id', array('class' => 'control-label')); ?>
                        <input type="text" name="schoolMun" id="schoolMun" style="width: 97.7%;" placeholder="Digite o Nome do Município">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                    <button class="btn btn-primary" url="<?php echo Yii::app()->createUrl('sedsp/default/AddSchool'); ?>" type="submit" value="Cadastrar" style="background: #3F45EA; color: #FFFFFF;"> Cadastrar </button>
                </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="modal fade modal-content" id="add-classroom" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Cadastrar Turma</h4>
        </div>
        <form class="form-vertical" id="addClassroom" action="<?php echo yii::app()->createUrl('sedsp/default/AddClassroom') ?>" method="post" onsubmit="return validateFormClass();">
            <div class="modal-body">
                <div class="row-fluid">
                    <div class=" span12" style="display: flex;">
                        <div style="width: 100%;">
                            <?php echo CHtml::label(yii::t('default', 'Código da Turma'), 'school_id', array('class' => 'control-label')); ?>
                            <input name="classroomNum" id="class" type="number" placeholder="Digite o Código da Turma" oninput="validateClass();" maxlength="9" style="width: 97.5%;" required>
                            <div id="class-warning" style="display: none;color:#D21C1C">O Código deve ter exatamente 9 dígitos.</div>
                            <div class="checkbox modal-replicate-actions-container">
                                <input type="checkbox" name="importStudents" style="margin-right: 10px;">
                                Importar Matrículas dos Alunos? Isso pode aumentar o tempo de espera.
                            </div>
                            <div class="checkbox modal-replicate-actions-container" style="margin-top: 8px;">
                                <input type="checkbox" name="registerAllClasses" style="margin-right: 10px;">
                                Importar todas as turmas? Isso pode aumentar o tempo de espera.
                            </div>
                            <div id="loading" style="display: none;">
                                <div class="loading-content" style="margin-top: 30px; margin-bottom: 30px;">
                                    <div id="loading">
                                        <img class="js-grades-loading" height="40px" width="40px" src="/themes/default/img/loadingTag.gif" alt="TAG Loading">
                                    </div>
                                    <div class="loading-text">Aguarde enquanto a(s) turma(s) é(são) importada(s)...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"
                        style="background: #EFF2F5; color:#252A31;">Voltar</button>
                    <button class="btn btn-primary" id="importClass"
                        url="<?php echo Yii::app()->createUrl('sedsp/default/AddClassroom'); ?>" type="submit"
                        value="Cadastrar" style="background: #3F45EA; color: #FFFFFF;"> Cadastrar </button>
                </div>
        </form>
    </div>
</div>

<style>
    #loading {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<script>
    document.getElementById('importClass').addEventListener('click', function (e) {
        $("#loading").show();
    });
</script>