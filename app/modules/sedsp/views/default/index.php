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
                    <span class="title">Importar Aluno usando o RA</span><br>
                    <span class="subtitle">Digite o RA para importar o Aluno</span>
                </div>
            </button>
        </a>
        
        <!--
        <a href="#" data-toggle="modal" data-target="#add-school" target="_blank">
            <button type="button" class="report-box-container">
                <div class="pull-left" style="margin-right: 20px;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sedspIcon/school.svg" alt="school" />
                </div>
                <div class="pull-left">
                    <span class="title">Cadastrar Escola</span><br>
                    <span class="subtitle">Digite o nome da escola e do município</span>
                </div>
            </button>
        </a>
-->
        <a href="<?php echo Yii::app()->createUrl('sedsp/default/ImportFullSchool') ?>" data-toggle="modal" data-target="#get-full-school" rel="noopener" target="_blank">
            <button type="button" class="report-box-container">
                <div class="pull-left" style="margin-right: 20px;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sedspIcon/school.svg" alt="school"/>
                </div>
                <div class="pull-left">
                    <span class="title">Importar Escola e as turmas</span><br>
                    <span class="subtitle">Realize a Importação da Escola, Incluindo Todas as Turmas</span>
                </div>
            </button>
        </a>

        <a  href="<?php echo Yii::app()->createUrl('sedsp/default/ListClassrooms'); ?>" data-toggle="modal" data-target="#import-students" rel="noopener" target="_blank">
            <button type="button" class="report-box-container">
                <div class="pull-left" style="margin-right: 20px;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sedspIcon/classroom.svg" />
                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                </div>
                <div class="pull-left">
                    <span class="title">Importar alunos por turmas</span><br>
                    <span class="subtitle" style="color: red;">Selecione primeiro a escola da qual deseja importar os alunos</span>
                </div>
            </button>
        </a>

        <a href="<?php echo Yii::app()->createUrl('sedsp/default/manageRA') ?>">
            <button type="button" class="report-box-container">
                <div class="pull-left" style="margin-right: 20px;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sedspIcon/generate.svg" alt="gerar ra"/>
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
            <h4 class="modal-title" id="myModalLabel">Importar Aluno usando o RA</h4>
        </div>
        <form class="form-vertical" id="addStudentRA" action="<?php echo yii::app()->createUrl('sedsp/default/ImportStudentRA') ?>" method="post" onsubmit="return validateFormStudent();">
            <div class="modal-body">
                <div class="row-fluid">
                    <div class=" span12">
                    <?php echo CHtml::label(yii::t('default', 'RA do Aluno'), 'year', array('class' => 'control-label')); ?>
                    <input name="numRA" id="numRA" type="text" placeholder="Digite o RA" style="width: 97.5%;" oninput="validateRA();" minlength="12" maxlength="12" required>
                    <span id="ra-char-count"><?php echo 12; ?> caracteres restantes</span>
                    <div id="ra-warning" style="display: none; color:#D21C1C">O RA deve ter exatamente 12 dígitos.</div>
                    </div>
                </div>
                <div id="loading-container-student" style="display: none;">
                    <div id="loading">
                        <div class="loading-content" style="margin-top: 30px; margin-bottom: 30px;">
                            <div id="loading">
                                <img class="js-grades-loading" height="40px" width="40px" src="/themes/default/img/loadingTag.gif" alt="TAG Loading">
                            </div>
                            <div class="loading-text">Importando aluno usando o RA...</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                        data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar
                    </button>
                    <button id="loading-popup" class="btn btn-primary"
                        url="<?php echo Yii::app()->createUrl('sedsp/default/ImportStudentRA'); ?>"
                        type="submit" value="Alterar" style="background: #3F45EA; color: #FFFFFF;"> Cadastrar
                    </button>
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
            </div>
        </form>
    </div>
</div>
<div class="modal fade modal-content" id="get-full-school" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
        </button>
        <h4 class="modal-title" id="myModalLabel">Importar Escola:</h4>
    </div>
    <form class="form-vertical" id="submit-full-school" action="<?php echo Yii::app()->createUrl('sedsp/default/ImportFullSchool') ?>" method="post">
        <input type="hidden" name="nameSchool" 
            value="<?php echo SchoolIdentification::model()->findBySql(
                "select name from school_identification where inep_id = " . Yii::app()->user->school
            )->name; ?>"
        >
        <div class="modal-body">
        <?php echo SchoolIdentification::model()->findBySql("select name from school_identification where inep_id = " . Yii::app()->user->school)->name?>
            <div id="loading-container-school" style="display: none;">
                <div id="loading">
                    <div class="loading-content" style="margin-top: 30px; margin-bottom: 30px;">
                        <div id="loading">
                            <img class="js-grades-loading" height="40px" width="40px" src="/themes/default/img/loadingTag.gif" alt="TAG Loading">
                        </div>
                        <div class="loading-text">Aguarde enquanto a escola e as classes são importadas...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">
                    Voltar
                </button>
                <button id="loading-popup-school" class="btn btn-primary"
                    url="<?= Yii::app()->createUrl('sedsp/default/ImportFullSchool'); ?>"
                    type="submit" value="Cadastrar" style="background: #3F45EA; color: #FFFFFF;"> Importar
                </button>
            </div>
        </div>
    </form>
</div>


<div class="row">
    <div class="modal fade modal-content" id="import-students" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Selecione as turmas para as quais deseja importar os alunos</h4>
        </div>
        <div style="margin: 5px;">
            <form id="select-classes-form" action="<?= Yii::app()->createUrl('sedsp/default/ImportFullStudentsByClasses'); ?>" method="post">
                <div style="max-height: 300px; overflow-y: auto;">
                    <table class="display student-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs" style="width:100%" aria-label="students table">
                        <thead>
                            <tr>
                                <th>N° Classe</th>
                                <th>Nome da turma</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $inep_id = Yii::app()->user->school;
                            $classes = Classroom::model()->findAllBySql("SELECT c.gov_id, c.name FROM classroom c WHERE c.gov_id is not null and c.school_inep_fk = " . $inep_id);
                            $selectedClasses = [];
                            foreach ($classes as $class) {
                                ?>
                                <tr>
                                    <td><?php echo $class['gov_id'] ?></td>
                                    <td><?php echo $class['name'] ?></td>
                                    <td>
                                        <?php echo CHtml::checkBox('checkboxListNumClasses[]', in_array($class['gov_id'], $selectedClasses), array('value' => $class['gov_id'])) ?>
                                    </td>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Add hidden input field to store selected classes -->
                <input type="hidden" name="selectedClasses" id="selectedClasses">
                <div id="loading-container-import-student" style="display: none;">
                    <div id="loading">
                        <div class="loading-content" style="margin-top: 30px; margin-bottom: 30px;">
                            <div id="loading">
                                <img class="js-grades-loading" height="40px" width="40px" src="/themes/default/img/loadingTag.gif" alt="TAG Loading">
                            </div>
                            <div class="loading-text">Importando alunos...</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="width:100%">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                    <button id="loading-popup-import-students" class="btn btn-primary" type="submit" value="Importar" style="background: #3F45EA; color: #FFFFFF;"> Importar alunos </button>
                </div>
            </form>
        </div>
</div>


<script>
    function updateSelectedClasses() {
        const checkboxes = document.querySelectorAll('input[name="checkboxListNumClasses[]"]');
        const selectedClasses = [];

        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                selectedClasses.push(checkbox.value);
            }
        });
        document.getElementById('selectedClasses').value = selectedClasses.join(',');
    }

    const checkboxes = document.querySelectorAll('input[name="checkboxListNumClasses[]"]');
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('click', updateSelectedClasses);
    });
</script>

<script>
    $(document).ready(function() {
        $('#loading-popup').click(function() {
            $('#loading-container-student').show();
        });
        
        $('#loading-popup-school').click(function() {
            $('#loading-container-school').show();
        });

        $('#loading-popup-class').click(function() {
            $('#loading-container-class').show();
        });
        $('#loading-popup-import-students').click(function() {
            $('#loading-container-import-student').show();
        });

        //Limpa todos os checkboxes
        $('#import-students').on('show.bs.modal', function() {
            $('input[name="checkboxListNumClasses[]"]').prop('checked', false);
        });
    });
</script>

<style>
    #loading {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<script>
    const raInput = document.getElementById('numRA');
    const charCount = document.getElementById('ra-char-count');
    const maxChars = 12;

    raInput.addEventListener('input', function() {
        const remainingChars = maxChars - raInput.value.length;
        if (remainingChars === 1) {
            charCount.textContent = '1 caractere restante';
        } else if (remainingChars > 1) {
            charCount.textContent = `${remainingChars} caracteres restantes`;
        } else {
            charCount.textContent = '';
        }
        validateRA();
    });
</script>




