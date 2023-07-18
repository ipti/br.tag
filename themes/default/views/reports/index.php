<?php
/* @var $this ReportsController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/reports.css');
$cs->registerScriptFile($baseUrl . '/js/reports/index/functions.js', CClientScript::POS_END);

$this->pageTitle = 'TAG - ' . Yii::t('default', 'Reports');
$this->breadcrumbs = array(
    Yii::t('default', 'Reports'),
);
?>
<div class="main">

    <div class="row-fluid">
        <div class="span12" style="margin-left: 20px;">
            <h1><?php echo Yii::t('default', 'Reports'); ?></h1>
        </div>
    </div>

    <div class="home">
        <div class="row-fluid">
            <?php if (Yii::app()->user->hasFlash('success')) : ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br />
            <?php endif ?>
            <?php if (Yii::app()->user->hasFlash('error')) : ?>
                <div class="alert alert-error">
                    <?php echo Yii::app()->user->getFlash('error') ?>
                </div>
            <?php endif ?>
            <div class="container-box student-report-container">

                <p>Alunos</p>

                <a href="<?php echo Yii::app()->createUrl('reports/NumberStudentsPerClassroomReport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-add-group t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Número de alunos por turma</span><br>
                            <span class="subtitle">Quantidade de alunos em cada turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/incompatiblestudentagebyclassroomreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-parents-children t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Alunos com idade incompatível por turma</span><br>
                            <span class="subtitle">Alunos com idades incompatíveis com as recomendadas</span>
                        </div>
                    </button>
                </a>

                <button type="button" class="report-box-container" data-toggle="modal" data-target="#studentperclassroom" target="_blank">
                    <div class="pull-left" style="margin-right: 20px;">
                        <span class="t-icon-group-people t-reports_icons"></span>
                    </div>
                    <div class="pull-left">
                        <span class="title">Alunos por turma</span><br>
                        <span class="subtitle">Informações dos alunos de uma turma</span>
                    </div>
                </button>

                <button type="button" class="report-box-container" data-toggle="modal" data-target="#reportFamilyBag" target="_blank">
                    <div class="pull-left" style="margin-right: 20px;">
                        <span class="t-icon-identity t-reports_icons"></span>
                    </div>
                    <div class="pull-left">
                        <span class="title">Frequência para o bolsa família</span><br>
                        <span class="subtitle">Frequência dos alunos por turma nos últimos três meses</span>
                    </div>
                </button>

                <a href="<?php echo Yii::app()->createUrl('reports/studentpendingdocument') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-weather-report t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Alunos com documentos pendentes</span><br>
                            <span class="subtitle">Relação de alunos com documentos pendentes de entrega.</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsbyclassroomreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-percentage t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação de alunos por turma</span><br>
                            <span class="subtitle">Informações dos alunos de cada turma</span>
                        </div>
                    </button>
                </a>

                <?php if (INSTANCE == "BUZIOS" || INSTANCE == "TREINAMENTO" || INSTANCE == "DEMO" || INSTANCE == "LOCALHOST") { ?>
                    <button type="button" class="report-box-container" data-toggle="modal" data-target="#quarterly-class-council" target="_blank">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-e-learning t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Ata de conselho de classe</span><br>
                            <span class="subtitle">Apresenta as matrículas, professores e avaliações da turma.</span>
                        </div>
                    </button>
                <?php } ?>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsbetween5and14yearsoldreport', array('id' => Yii::app()->user->school)) ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-heart t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Alunos com idade entre 5 e 14 Anos (SUS)</span><br>
                            <span class="subtitle">Todos os alunos que possuem idade entre 5 e 14 anos</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsinalphabeticalorderrelationreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-arrow-az t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação de alunos em ordem alfabética</span><br>
                            <span class="subtitle">Alunos em ordem alfabética</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentspecialfood') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-room-service t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Cardápios especiais</span><br>
                            <span class="subtitle">Alunos com restrições alimentares</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/outoftownstudentsreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-backpack t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação de alunos fora da cidade</span><br>
                            <span class="subtitle">Alunos que estudam fora da sua cidade natal</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/electronicdiary') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container" style="padding-left: 25px;">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-book t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Diário eletrônico</span><br>
                            <span class="subtitle">Relatório de notas por aluno e frequência da turma</span>
                        </div>
                    </button>
                </a>

                <button type="button" class="report-box-container" data-toggle="modal" data-target="#cns-per-classroom" target="_blank">
                    <div class="pull-left" style="margin-right: 20px;">
                        <span class="t-medical t-reports_icons"></span>
                    </div>
                    <div class="pull-left">
                        <span class="title">Relatório CNS por Turma</span><br>
                        <span class="subtitle">Listagem de CNS dos alunos por Turma</span>
                    </div>
                </button>

                <?php if (INSTANCE == "BUZIOS" || INSTANCE == "TREINAMENTO" || INSTANCE == "DEMO" || INSTANCE == "LOCALHOST") { ?>
                    <button type="button" class="report-box-container" data-toggle="modal" data-target="#quarterly-report" target="_blank">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-checklist t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relatório trimestral do aluno</span><br>
                            <span class="subtitle">Avaliação do aluno no trimestre</span>
                        </div>
                    </button>
                    <button type="button" class="report-box-container quarterly-follow-up" data-toggle="modal" data-target="#quarterly-follow-up" target="_blank" style="margin-left:15px;padding-left: 35px;">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-student-monitoring t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relatório trimestral de acompanhamento</span><br>
                            <span class="subtitle">Acompanhamento dos alunos por disciplina</span>
                        </div>
                    </button>

                    <button type="button" class="report-box-container evaluation-follow-up" data-toggle="modal" data-target="#evaluation-follow-up-students" target="_blank" style="padding-left: 35px;">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class=" t-monitoring_report t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Acompanhamento avaliativo dos alunos</span><br>
                            <span class="subtitle">Acompanhamento avaliativo dos alunos por disciplina</span>
                        </div>
                    </button>
                <?php } ?>
            </div>
            <div class="container-box">

                <p>Professores</p>

                <a href="<?php echo Yii::app()->createUrl('reports/schoolprofessionalnumberbyclassroomreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-graduation-cap t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Número de professores por turma</span><br>
                            <span class="subtitle">Quantidade de professores por turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/TeachersByStage') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">    
                        <div class="pull-left" style="margin-right: 20px;">
                            <img class="t-reports_icons" src="<?php echo Yii::app()->theme->baseUrl . '/img/reportsIcon/stage_teachers.svg'?>" alt="Stage Teachers"></img>
                        </div>
                        <div class="pull-left">
                            <span class="title">Professores por Etapa</span><br>
                            <span class="subtitle">Listagem e total de professores por etapa</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/disciplineandinstructorrelationreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-person t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação componente curricular por docente</span><br>
                            <span class="subtitle">Docente e componente curricular ministrado em cada turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/InstructorsPerClassroomReport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-column_graphi t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Professores por turma </span><br>
                            <span class="subtitle">Informações do docente por turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/classroomwithoutinstructorrelationreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-no-teacher t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação de turmas sem instrutor</span><br>
                            <span class="subtitle">Todas as turmas que não possuem docente</span>
                        </div>
                    </button>
                </a>

                <button type="button" class="report-box-container" data-toggle="modal" data-target="#teacher-training" target="_blank" style="padding-left: 35px;">
                    <div class="pull-left" style="margin-right: 20px;">
                        <span class="t-monitoring_report t-reports_icons"></span>
                    </div>
                    <div class="pull-left">
                        <span class="title">ATA Formação de Professores</span><br>
                        <span class="subtitle">Matrícula e avaliações da turma de professores</span>
                    </div>
                </button>
            </div>
            <div class="container-box">

                <p>Escola</p>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsusingschooltransportationrelationreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-bus2 t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação transporte escolar</span><br>
                            <span class="subtitle">Alunos que utilizam transporte escolar</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/educationalassistantperclassroomreport', array('id' => Yii::app()->user->school)) ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-handshake t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Auxiliar educacional por turma</span><br>
                            <span class="subtitle">Professores auxiliares por turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/enrollmentcomparativeanalysisreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-copy t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Analise comparativa de matrículas </span><br>
                            <span class="subtitle">Comparação de matrículas</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentswithdisabilitiesrelationreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-wheelchair t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação acessibilidade</span><br>
                            <span class="subtitle">Alunos que possuem deficiência</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/complementaractivityassistantbyclassroomreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-bullseye-arrow t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Monitores de atividade complementar</span><br>
                            <span class="subtitle">Monitores de atividade complementar por turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentinstructornumbersrelationreport') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-icon-teachers-students t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Número de alunos e professores por turma</span><br>
                            <span class="subtitle">Quantidade de alunos e professores por turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/CnsPerSchool') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">    
                        <div class="pull-left" style="margin-right: 20px;">
                            <span class="t-doctor t-reports_icons"></span>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relatório CNS da escola</span><br>
                            <span class="subtitle">Listagem de CNS dos alunos da escola atual</span>
                        </div>
                    </button>
                </a>
            </div>
            
            <?php if(Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) { ?>
            <div class="container-box">
                <p>Administrador</p>

                <button type="button" class="report-box-container" data-toggle="modal" data-target="#loading-warning" target="_blank">
                    <div class="pull-left" style="margin-right: 20px;">
                        <span class="t-hospital-user t-reports_icons"></span>
                    </div>
                    <div class="pull-left">
                        <span class="title">Relatório CNS de todas as escolas</span><br>
                        <span class="subtitle">Listagem de CNS dos alunos de todas as escolas</span>
                    </div>
                </button>

                <a href="<?php echo Yii::app()->createUrl('reports/TeachersBySchool') ?>" target="_blank" rel="noopener">
                    <button type="button" class="report-box-container">    
                        <div class="pull-left" style="margin-right: 20px;">
                            <img class="t-reports_icons" src="<?php echo Yii::app()->theme->baseUrl . '/img/reportsIcon/school_teacher.svg'?>" alt="School Teachers"></img>
                        </div>
                        <div class="pull-left">
                            <span class="title">Professores por Escola</span><br>
                            <span class="subtitle">Listagem e total de professores por escola</span>
                        </div>
                    </button>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
    <!-- Modais -->
    <div class="row">
        <div class="modal fade modal-content" id="teacher-training" tabindex="-1" role="dialog" style="height: auto !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title" id="myModalLabel">Ata de Formação de Professores - Escolha a Turma</h4>
            </div>
            <form class="form-vertical" action="<?php echo Yii::app()->createUrl('reports/TeacherTrainingReport'); ?>" method="post" target="_blank">
                <div class="modal-body" style="max-height: none !important;overflow: visible !important;">
                    <div class="row-fluid">
                        <div class=" span12">
                            <?php
                            echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                            ?>
                            <select name="classroom" id="classroom" placeholder="Selecione a turma" style="width:100%" required>
                                <?php
                                echo "<option value='' selected>Selecione a turma</option>";
                                foreach ($classrooms as $classroom) {
                                    echo "<option value='" . $classroom->id . "'>" . $classroom->name . "</option>";
                                }
                                ?>
                            </select>
                            <div class="model-quarterly-container" style="display: flex;">
                                <div style="display:block;width:65%;margin-right:5%;">
                                    <label for="count_days" class="control-label" style="width: 100%;"> Dia da reunião de conselho de classe</label>
                                    <input type="number" name="count_days" placeholder="Digite o dia das reuniões" style="width: 100%;height:35px;" min="1" max="31" required>
                                </div>
                                <div style="display:block;width:30%;">
                                    <label for="hour" class="control-label" style="width: 100%;">Horário das reuniões</label>
                                    <input type="time" id="hour" name="hour" min="00:00" max="23:59" style="width: 92%;height:35px;" required>
                                </div>
                            </div>

                            <div class="model-quarterly-container" style="display: flex;">
                                <div style="display:block;width:45%;margin-right:5%;">
                                    <label for="year" class="control-label" style="width: 100%;">Ano das reuniões</label>
                                    <select name="year" id="year" placeholder="Selecione o ano" style="width:100%" required>
                                        <?php
                                        $years = range(date('Y'), 2014);
                                        echo "<option value='' selected>Selecione o ano</option>";
                                        for ($i = 0; $i < count($years); $i++) {
                                            echo "<option value=" . $years[$i] . ">" . $years[$i] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div style="display:block;width:50%;">
                                    <label for="mounth" class="control-label" style="width: 100%;">Mês das reuniões</label>
                                    <select id="mounth" name="mounth" style="width:100%" required>
                                        <option value='' selected>Selecione o mês</option>
                                        <option value="Janeiro">Janeiro</option>
                                        <option value="Fevereiro">Fevereiro</option>
                                        <option value="Março">Março</option>
                                        <option value="Abril">Abril</option>
                                        <option value="Maio">Maio</option>
                                        <option value="Junho">Junho</option>
                                        <option value="Julho">Julho</option>
                                        <option value="Agosto">Agosto</option>
                                        <option value="Setembro">Setembro</option>
                                        <option value="Outubro">Outubro</option>
                                        <option value="Novembro">Novembro</option>
                                        <option value="Dezembro">Dezembro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="model-quarterly-container" style="display: flex;">
                                <div style="display:block;width:70%;padding-right:10px;">
                                    <label for="quarterly" class="control-label" style="width: 30%;">Trimestre</label>
                                    <select name="quarterly" id="quarterly" style="width:100%" required>
                                        <option value='' selected>Selecione o trimestre</option>
                                        <option value="1º">1º Trimestre</option>
                                        <option value="2º">2º Trimestre</option>
                                        <option value="3º">3º Trimestre</option>
                                        <option value="4º">4º Trimestre</option>
                                    </select>
                                </div>
                                <div style="display:block;width:30%;">
                                    <label for="model_report" class="control-label" style="width: 100%;">Modelo de Eixos</label>
                                    <select name="model_report" id="model_report" style="width: 100%;" required>
                                        <option value="">Selecione o modelo</option>
                                        <option value="1">1º Ano</option>
                                        <option value="2">2º Ano</option>
                                        <option value="3">3º Ano</option>
                                        <option value="4">4º Ano</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                        <button class="btn btn-primary" type="submit" value="Gerar" style="background: #3F45EA; color: #FFFFFF;">Gerar</button>
                    </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="modal fade modal-content" id="loading-warning" tabindex="-1" role="dialog" aria-labelledby="Generate Another Timesheet">
            <div class="modal-dialog" role="document">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                    </button>
                    <h4 class="modal-title" id="myModalLabel">ATENÇÃO <span class="t-info_positive"></span></h4>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="row-fluid">
                            Deseja gerar o relatório de CNS dos alunos de todas as escolas? <b>Isso pode demorar um pouco!</b>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <a href="<?php echo Yii::app()->createUrl('reports/CnsSchools') ?>" target="_blank" rel="noopener" style="margin-left: 5px;">
                                <button type="button" class="btn btn-primary">Gerar</button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="modal fade modal-content" id="reportFamilyBag" tabindex="-1" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="
                Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title" id="myModalLabel">Frequência Bolsa Família - Escolha a Turma</h4>
            </div>
            <form class="form-vertical" action="" method="post">
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class=" span12">
                            <?php
                            echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                            ?>
                            <select name="classroom2" id="classroom2" placeholder="Selecione a turma" style="width:100%">
                                <?php
                                echo "<option value='' selected>Selecione a turma</option>";
                                foreach ($classrooms as $classroom) {
                                    echo "<option value='" . $classroom->id . "'>" . $classroom->name . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                        <button class="btn btn-primary" url="<?php echo Yii::app()->createUrl('reports/BFReport'); ?>" type="button" value="Gerar" id="buildReportBF" style="background: #3F45EA; color: #FFFFFF;"> Selecionar turma </button>
                    </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="modal fade modal-content" id="studentperclassroom" tabindex="-1" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title" id="myModalLabel">Selecione a turma</h4>
            </div>
            <form class="form-vertical" action="" method="post">
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class=" span12">
                            <?php
                            echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                            ?>
                            <select name="classroom" id="classroom" placeholder="Selecione a turma" style="width:100%">
                                <?php
                                echo "<option value='' selected>Selecione a turma</option>";
                                foreach ($classrooms as $classroom) {
                                    echo "<option value='" . $classroom->id . "'>" . $classroom->name . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                        <button class="btn btn-primary" url="<?php echo Yii::app()->createUrl('reports/studentbyclassroomreport'); ?>" type="button" value="Gerar" id="buildReport" style="background: #3F45EA; color: #FFFFFF;"> Selecionar turma </button>
                    </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="modal fade modal-content" id="cns-per-classroom" tabindex="-1" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title" id="myModalLabel">Selecione a turma</h4>
            </div>
            <form class="form-vertical" action="<?php echo Yii::app()->createUrl('reports/CnsPerClassroomReport'); ?>" method="post" target="_blank">
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class=" span12">
                            <?php
                            echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                            ?>
                            <select name="cns_classroom_id" id="cns_classroom_id" placeholder="Selecione a turma" style="width:100%" required>
                                <?php
                                echo "<option value='' selected>Selecione a turma</option>";
                                foreach ($classrooms as $classroom) {
                                    echo "<option value='" . $classroom->id . "'>" . $classroom->name . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                        <button class="btn btn-primary" type="submit" value="Gerar" style="background: #3F45EA; color: #FFFFFF;"> Selecionar turma </button>
                    </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="modal fade modal-content" id="quarterly-class-council" tabindex="-1" role="dialog" style="height: auto !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title" id="myModalLabel">Ata de Conselho de Classe - Escolha a Turma</h4>
            </div>
            <form class="form-vertical" action="<?php echo Yii::app()->createUrl('reports/ClassCouncilReport'); ?>" method="post" target="_blank">
                <div class="modal-body" style="max-height: none !important;overflow: visible !important;">
                    <div class="row-fluid">
                        <div class=" span12">
                            <?php
                            echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                            ?>
                            <select name="classroom2" id="classroom2" placeholder="Selecione a turma" style="width:100%" required>
                                <?php
                                echo "<option value='' selected>Selecione a turma</option>";
                                foreach ($classrooms as $classroom) {
                                    echo "<option value='" . $classroom->id . "'>" . $classroom->name . "</option>";
                                }
                                ?>
                            </select>
                            <div class="model-quarterly-container" style="display: flex;">
                                <div style="display:block;width:65%;margin-right:5%;">
                                    <label for="count_days" class="control-label" style="width: 100%;"> Dia da reunião de conselho de classe</label>
                                    <input type="number" name="count_days" placeholder="Digite o dia das reuniões" style="width: 100%;height:35px;" min="1" max="31" required>
                                </div>
                                <div style="display:block;width:30%;">
                                    <label for="hour" class="control-label" style="width: 100%;">Horário das reuniões</label>
                                    <input type="time" id="hour" name="hour" min="00:00" max="23:59" style="width: 92%;height:35px;" required>
                                </div>
                            </div>

                            <div class="model-quarterly-container" style="display: flex;">
                                <div style="display:block;width:45%;margin-right:5%;">
                                    <label for="year" class="control-label" style="width: 100%;">Ano das reuniões</label>
                                    <select name="year" id="year" placeholder="Selecione o ano" style="width:100%" required>
                                        <?php
                                        $years = range(date('Y'), 2014);
                                        echo "<option value='' selected>Selecione o ano</option>";
                                        for ($i = 0; $i < count($years); $i++) {
                                            echo "<option value=" . $years[$i] . ">" . $years[$i] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div style="display:block;width:50%;">
                                    <label for="mounth" class="control-label" style="width: 100%;">Mês das reuniões</label>
                                    <select id="mounth" name="mounth" style="width:100%" required>
                                        <option value='' selected>Selecione o mês</option>
                                        <option value="Janeiro">Janeiro</option>
                                        <option value="Fevereiro">Fevereiro</option>
                                        <option value="Março">Março</option>
                                        <option value="Abril">Abril</option>
                                        <option value="Maio">Maio</option>
                                        <option value="Junho">Junho</option>
                                        <option value="Julho">Julho</option>
                                        <option value="Agosto">Agosto</option>
                                        <option value="Setembro">Setembro</option>
                                        <option value="Outubro">Outubro</option>
                                        <option value="Novembro">Novembro</option>
                                        <option value="Dezembro">Dezembro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="model-quarterly-container" style="display: flex;">
                                <!-- <div style="display:block;width:45%;margin-right:5%;">
                                    <label for="quarterly-model" class="control-label">Modelo</label>
                                    <select name="quarterly-model" id="quarterly-model" style="width:100%" required>
                                        <option value='' selected>Selecione o modelo</option>
                                        <option value="1">Educação Infantil</option>
                                        <option value="2">1º ao 5º Ano</option>
                                        <option value="3">6º ao 9º Ano</option>
                                        <option value="4">Ensino Médio</option>
                                    </select>
                                </div> -->
                                <div style="display:block;width:70%;">
                                    <label for="quarterly" class="control-label" style="width: 30%;">Trimestre</label>
                                    <select name="quarterly" id="quarterly" style="width:100%" required>
                                        <option value='' selected>Selecione o trimestre</option>
                                        <option value="1º">1º Trimestre</option>
                                        <option value="2º">2º Trimestre</option>
                                        <option value="3º">3º Trimestre</option>
                                        <option value="4º">4º Trimestre</option>
                                    </select>
                                </div>
                                <div style="width: 30%;margin-top: 6%;text-align:right;">
                                    <input type="checkbox" name="infantil-model" id="infantil-model" style="margin-right:5px;margin-bottom:2px;">
                                    Educação Infantil?
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                        <button class="btn btn-primary" type="submit" value="Gerar" style="background: #3F45EA; color: #FFFFFF;">Gerar</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="modal fade modal-content" id="quarterly-report" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Relatório Trimestral Individual - Escolha o Aluno</h4>
        </div>
        <form class="form-vertical" action="<?php echo Yii::app()->createUrl('reports/QuarterlyReport'); ?>" method="post" target="_blank">
            <div class="modal-body">
                <div class="row-fluid">
                    <div class=" span12">
                        <?php
                        echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                        ?>
                        <select name="quartely_report_classroom_student" id="quartely_report_classroom_student" style="width: 100%;" required>
                            <option value="">Selecione a Turma</option>
                            <?php
                            foreach ($classrooms as $classroom) {
                                echo "<option value='" . $classroom->id . "'>" . $classroom->name . "</option>";
                            }
                            ?>
                        </select>
                        <div class="classroom-student-container" style="display: none;">
                            <?php
                            echo CHtml::label(yii::t('default', 'Student Fk'), 'year', array('class' => 'control-label'));
                            ?>
                            <select name="student" id="student" placeholder="Selecione o aluno" style="width:100%" required>
                                <?php
                                echo "<option value='' selected>Selecione o aluno</option>";

                                ?>
                            </select>
                        </div>
                        <div class="classroom-student-error" style="display:none;color:#D21C1C;margin-left:5px;font-size:12px;">
                            <span>A turma não tem nenhum aluno matriculado.
                            </span>
                            <a href="#" data-toggle="modal" data-target="#change-year" target="_blank" data-dismiss="modal">
                                Clique aqui para mudar o ano.
                            </a>
                        </div>
                        <div class="classroom-student-container" style="display: none;">
                            <label for="model_quartely" class="control-label" style="width: 100%;">Modelo de Eixos e Campos de Experiência</label>
                            <select name="model_quartely" id="model_quartely" style="width: 100%;" required>
                                <option value="">Selecione o modelo</option>
                                <option value="1">1º ANO</option>
                                <option value="2">2º ANO</option>
                                <option value="3">3º ANO</option>
                                <option value="4">CRECHE II</option>
                                <option value="5">CRECHE III</option>
                                <option value="6">CRECHE IV</option>
                                <option value="7">PRÉ I</option>
                                <option value="8">PRÉ II</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                    <button class="btn btn-primary" type="submit" value="Gerar" style="background: #3F45EA; color: #FFFFFF;">Gerar</button>
                </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="modal fade modal-content" id="quarterly-follow-up" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Relatório Trimestral de Acompanhamento</h4>
        </div>
        <form class="form-vertical" action="<?php echo Yii::app()->createUrl('reports/QuarterlyFollowUpReport'); ?>" method="post" target="_blank">
            <div class="modal-body">
                <div class="row-fluid">
                    <div class=" span12">
                        <?php
                        echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                        ?>
                        <select name="quarterly_follow_up_classroom" id="quarterly_follow_up_classroom" style="width: 100%;" required>
                            <option value="">Selecione a Turma</option>
                            <?php
                            foreach ($classrooms as $classroom) {
                                echo "<option value='" . $classroom->id . "'>" . $classroom->name . "</option>";
                            }
                            ?>
                        </select>
                        <div class="quarterly-follow-up-disciplines-container">
                            <?php
                            echo CHtml::label(yii::t('default', 'Discipline'), 'discipline', array('class' => 'control-label'));
                            ?>
                            <select name="quarterly_follow_up_disciplines" id="quarterly_follow_up_disciplines" placeholder="Selecione a disciplina" style="width:100%" required>
                                <option value="" selected>Selecione a disciplina</option>
                                <option value="10">Arte (Educação Artística, Teatro, Dança, Música, Artes Plásticas e outras)</option>
                                <option value="11">Educação Física</option>
                            </select>
                        </div>
                        <label for="" class="control-label">Trimestre</label>
                        <select name="quarterly" id="quarterly" style="width: 100%;" required>
                            <option value="">Selecione o Trimestre</option>
                            <option value="1º Trimestre">1º Trimestre</option>
                            <option value="2º Trimestre">2º Trimestre</option>
                            <option value="3º Trimestre">3º Trimestre</option>
                            <option value="4º Trimestre">4º Trimestre</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                    <button class="btn btn-primary" type="submit" value="Gerar" style="background: #3F45EA; color: #FFFFFF;">Gerar</button>
                </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="modal fade modal-content" id="evaluation-follow-up-students" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Acompanhamento Avaliativo dos Alunos</h4>
        </div>
        <form class="form-vertical" action="<?php echo Yii::app()->createUrl('reports/EvaluationFollowUpStudentsReport'); ?>" method="post" target="_blank">
            <div class="modal-body">
                <div class="row-fluid">
                    <div class=" span12">
                        <?php
                        echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                        ?>
                        <select name="evaluation_follow_up_classroom" id="evaluation_follow_up_classroom" style="width: 100%;" required>
                            <option value="">Selecione a Turma</option>
                            <?php
                            foreach ($classrooms as $classroom) {
                                echo "<option value='" . $classroom->id . "'>" . $classroom->name . "</option>";
                            }
                            ?>
                        </select>
                        <div class="evaluation-follow-up-disciplines-container">
                            <?php
                            echo CHtml::label(yii::t('default', 'Discipline'), 'discipline', array('class' => 'control-label'));
                            ?>
                            <select name="evaluation_follow_up_disciplines" id="evaluation_follow_up_disciplines" placeholder="Selecione a disciplina" style="width:100%" required>
                                <option value="" selected>Selecione a disciplina</option>
                                <option value="10">Arte (Educação Artística, Teatro, Dança, Música, Artes Plásticas e outras)</option>
                                <option value="11">Educação Física</option>
                            </select>
                        </div>
                        <label for="" class="control-label">Trimestre</label>
                        <select name="quarterly" id="quarterly" style="width: 100%;" required>
                            <option value="">Selecione o Trimestre</option>
                            <option value="1º">1º Trimestre</option>
                            <option value="2º">2º Trimestre</option>
                            <option value="3º">3º Trimestre</option>
                            <option value="4º">4º Trimestre</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                    <button class="btn btn-primary" type="submit" value="Gerar" style="background: #3F45EA; color: #FFFFFF;">Gerar</button>
                </div>
        </form>
    </div>
</div>

</div>
</div>

<script>
    var containerFollowUp = $('.quarterly-follow-up');
    var containerEvaluation = $('.evaluation-follow-up');
    var containerBoxOut = $('.container-box');
    containerFollowUp.appendTo('.student-report-container');
    containerEvaluation.appendTo('.student-report-container');
    containerBoxOut.appendTo('.main');
    $('')
</script>
<?php

$cs = Yii::app()->getClientScript();
$cs->registerScript('buildReport', "  
    $('#buildReport').on('click', function(event) {
        var url = $(this).attr('url');
        var id = $('#classroom').val();
        $(this).attr('url', url + '&id='+ id);
    });
    registerAndOpenTab('#buildReport');", CClientScript::POS_END);

$cs->registerScript('buildReportBF', "  
    $('#buildReportBF').on('click', function(event) {
        var url = $(this).attr('url');
        var id = $('#classroom2').val();
        $(this).attr('url', url + '&id='+ id);
    });
    registerAndOpenTab('#buildReportBF');", CClientScript::POS_END);

?>