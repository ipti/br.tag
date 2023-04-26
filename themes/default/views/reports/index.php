<?php
/* @var $this ReportsController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/reports.css');

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
            <?php if (Yii::app()->user->hasFlash('error')): ?>
                <div class="alert alert-error">
                    <?php echo Yii::app()->user->getFlash('error') ?>
                </div>
            <?php endif ?>
            <div class="container-box">

<<<<<<< HEAD
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/educationalassistantperclassroomreport', array('id' => Yii::app()->user->school)) ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-handshake-o fa-4x"></i></div>
                        <span class="report-title">Auxiliar/Assistente Educacional por Turma</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/disciplineandinstructorrelationreport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-podcast fa-4x"></i></div>
                        <span class="report-title">Relação Disciplina/Docente</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/complementaractivityassistantbyclassroomreport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-dot-circle-o fa-4x"></i></div>
                        <span class="report-title">Monitores de Atividade Complementar por Turma</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/classroomwithoutinstructorrelationreport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-ban fa-4x"></i></div>
                        <span class="report-title">Relação Turmas sem Instrutor</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/studentinstructornumbersrelationreport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-users fa-4x"></i></div>
                        <span class="report-title">Número de Alunos e Professores por Turma</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/studentsbyclassroomreport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-address-book fa-4x"></i></div>
                        <span class="report-title">Relação de alunos por turma</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
            <div class="span12" style="margin: 10px 0 0 0">
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/studentsinalphabeticalorderrelationreport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-sort-alpha-asc fa-4x"></i></div>
                        <span class="report-title">Relaçao de Alunos em ordem Alfabética</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/studentswithdisabilitiesrelationreport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-wheelchair fa-4x"></i></div>
                        <span class="report-title">Relação Acessibilidade</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/studentsusingschooltransportationrelationreport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-bus fa-4x"></i></div>
                        <span class="report-title">Relação Transporte Escolar</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/incompatiblestudentagebyclassroomreport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-street-view fa-4x"></i></div>
                        <span class="report-title">Alunos com Idade Incompatível por Turma</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/bfrstudentReport') ?>" class="widget-stats" target="_blank">
                        <div><span class="glyphicons justify"><i></i></span></div>
                        <span class="report-title">Alunos participantes Bolsa Familia</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/studentpendingdocument') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-list-ol fa-4x"></i></div>
                        <span class="report-title">Alunos com Documentos Pendentes</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
            <div class="span12" style="margin: 10px 0 0 0">
                <div class="span2">
                    <a href="#" data-toggle="modal" data-target="#studentperclassroom" class="widget-stats" target="_blank">
                        <div><span class="glyphicons signal"><i></i></span></div>
                        <span class="report-title">Alunos por turma</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/studentspecialfood') ?>" class="widget-stats" target="_blank">
                        <span class="glyphicons fast_food"><i></i></span>
                        <span class="txt">Alunos - Cardápios Especiais</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/electronicdiary') ?>" class="widget-stats">
                        <span class="glyphicons briefcase"><i></i></span>
                        <span class="txt">Diário Eletrônico</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/outoftownstudentsreport') ?>" class="widget-stats">
                        <span class="glyphicons signal"><i></i></span>
                        <span class="txt">Relação de alunos fora da cidade</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
            <div class="modal fade modal-content" id="studentperclassroom" tabindex="-1" role="dialog">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Selecione a turma</h4>
                </div>
                <form class="form-vertical"  action="" method="post">
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
=======
                <p>Alunos</p>

                <a href="<?php echo Yii::app()->createUrl('reports/NumberStudentsPerClassroomReport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/NumberStudentsPerClassroomReport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Número de alunos por turma</span><br>
                            <span class="subtitle">Quantidade de alunos em cada turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/incompatiblestudentagebyclassroomreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/incompatiblestudentagebyclassroomreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
>>>>>>> d4c8487098dd8fe00dbd2ed32a571722ed607430
                        </div>
                        <div class="pull-left">
                            <span class="title">Alunos com idade incompatível por turma</span><br>
                            <span class="subtitle">Alunos com idades incompatíveis com as recomendadas</span>
                        </div>
                    </button>
                </a>

                <button type="button" class="report-box-container" data-toggle="modal" data-target="#studentperclassroom" target="_blank">
                    <div class="pull-left" style="margin-right: 20px;">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/studentperclassroom.svg" />
                        <!-- <div class="t-icon-schedule report-icon"></div> -->
                    </div>
                    <div class="pull-left">
                        <span class="title">Alunos por turma</span><br>
                        <span class="subtitle">Informações dos alunos de uma turma</span>
                    </div>
                </button>

                <button type="button" class="report-box-container" data-toggle="modal" data-target="#reportFamilyBag" target="_blank">
                    <div class="pull-left" style="margin-right: 20px;">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/reportFamilyBag.svg" />
                        <!-- <div class="t-icon-schedule report-icon"></div> -->
                    </div>
                    <div class="pull-left">
                        <span class="title">Participantes do bolsa familia</span><br>
                        <span class="subtitle">Alunos beneficiários do bolsa família</span>
                    </div>
                </button>

                <a href="<?php echo Yii::app()->createUrl('reports/studentpendingdocument') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/studentpendingdocument.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Alunos com documentos pendentes</span><br>
                            <span class="subtitle">Relação de alunos com documentos pendentes de entrega.</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsbyclassroomreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/studentsbyclassroomreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
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
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/quarterly-class-council.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Ata de conselho de classe</span><br>
                            <span class="subtitle">Apresenta as matrículas, professores e avaliações da turma.</span>
                        </div>
                    </button>
                <?php } ?>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsbetween5and14yearsoldreport', array('id' => Yii::app()->user->school)) ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/studentsbetween5and14yearsoldreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Alunos com idade entre 5 e 14 Anos (SUS)</span><br>
                            <span class="subtitle">Todos os alunos que possuem idade entre 5 e 14 anos</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsinalphabeticalorderrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/studentsinalphabeticalorderrelationreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação de alunos em ordem alfabética</span><br>
                            <span class="subtitle">Alunos em ordem alfabética</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentspecialfood') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/studentspecialfood.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Cardápios especiais</span><br>
                            <span class="subtitle">Alunos com restrições alimentares</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/outoftownstudentsreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/outoftownstudentsreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação de alunos fora da cidade</span><br>
                            <span class="subtitle">Alunos que estudam fora da sua cidade natal</span>
                        </div>
                    </button>
                </a>

                <a href="<<?php echo Yii::app()->createUrl('reports/electronicdiary') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/electronicdiary.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Diário eletrônico</span><br>
                            <span class="subtitle">Relatório de notas por aluno e frequência da turma</span>
                        </div>
                    </button>
                </a>

                <?php if (INSTANCE == "BUZIOS" || INSTANCE == "TREINAMENTO" || INSTANCE == "DEMO" || INSTANCE == "LOCALHOST") { ?>
                    <button type="button" class="report-box-container" data-toggle="modal" data-target="#quarterly-report" target="_blank">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/quarterly-report.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <d  iv class="pull-left">
                            <span class="title">Relatório trimestral do aluno</span><br>
                            <span class="subtitle">Avaliação do aluno no trimestre</span>
                        </div>
                    </button>
                <?php } ?>
            </div>
            <div class="container-box">

                <p>Professores</p>

                <a href="<?php echo Yii::app()->createUrl('reports/schoolprofessionalnumberbyclassroomreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/schoolprofessionalnumberbyclassroomreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Número de professores por turma</span><br>
                            <span class="subtitle">Quantidade de professores por turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/disciplineandinstructorrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/disciplineandinstructorrelationreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação disciplina por docente</span><br>
                            <span class="subtitle">Informações do docente e disciplina ministrada em cada turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/InstructorsPerClassroomReport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/InstructorsPerClassroomReport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Professores por turma </span><br>
                            <span class="subtitle">Informações do docente por turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/classroomwithoutinstructorrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/classroomwithoutinstructorrelationreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação de turmas sem instrutor</span><br>
                            <span class="subtitle">Todas as turmas que não possuem docente</span>
                        </div>
                    </button>
                </a>
            </div>
            <div class="container-box">

                <p>Escola</p>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsusingschooltransportationrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/studentsusingschooltransportationrelationreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação transporte escolar</span><br>
                            <span class="subtitle">Alunos que utilizam transporte escolar</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/educationalassistantperclassroomreport', array('id' => Yii::app()->user->school)) ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/educationalassistantperclassroomreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Auxiliar educacional por turma</span><br>
                            <span class="subtitle">Professores auxiliares por turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/enrollmentcomparativeanalysisreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/enrollmentcomparativeanalysisreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Analise comparativa de matrículas </span><br>
                            <span class="subtitle">Comparação de matrículas</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentswithdisabilitiesrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/studentswithdisabilitiesrelationreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação acessibilidade</span><br>
                            <span class="subtitle">Alunos que possuem deficiência</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/complementaractivityassistantbyclassroomreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/complementaractivityassistantbyclassroomreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Monitores de atividade complementar</span><br>
                            <span class="subtitle">Monitores de atividade complementar por turma</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentinstructornumbersrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="margin-right: 20px;">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/reportsIcon/studentinstructornumbersrelationreport.svg" />
                            <!-- <div class="t-icon-schedule report-icon"></div> -->
                        </div>
                        <div class="pull-left">
                            <span class="title">Número de alunos e professores por turma</span><br>
                            <span class="subtitle">Quantidade de alunos e professores por turma</span>
                        </div>
                    </button>
                </a>

            </div>
        </div>
    </div>
<<<<<<< HEAD

    <div class="modal fade modal-content" id="reportFamilyBag" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Frequência Bolsa Família - Escolha a Turma</h4>
        </div>
        <form class="form-vertical"  action="" method="post">
            <div class="modal-body">
                <div class="row-fluid">
                    <div class=" span12">
                        <?php
                        echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                        ?>
                        <select name="classroom2" id="classroom2" placeholder="Selecione a turma" style="width:100%">
=======
    <!-- Modais -->
    <div class="row">
        <div class="modal fade modal-content" id="reportFamilyBag" tabindex="-1" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title" id="myModalLabel">Frequência Bolsa Família - Escolha a Turma</h4>
            </div>
            <form class="form-vertical" action="" method="post">
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class=" span12">
>>>>>>> d4c8487098dd8fe00dbd2ed32a571722ed607430
                            <?php
                            echo "<option value='' selected>Selecione a turma</option>";
                            foreach ($classrooms as $classroom) {
                                echo "<option value='" . $classroom->id . "'>" . $classroom->name . "</option>";
                            }
                            ?>
                        </select>
                    </div>
<<<<<<< HEAD
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                    <button class="btn btn-primary" url="<?php echo Yii::app()->createUrl('reports/BFReport'); ?>" type="button" value="Gerar" id="buildReportBF" style="background: #3F45EA; color: #FFFFFF;"> Selecionar turma </button>
                </div>
        </form>
=======
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
        <div class="modal fade modal-content" id="quarterly-class-council" tabindex="-1" role="dialog" style="height: auto !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title" id="myModalLabel">Ata de Conselho de Classe - Escolha a Turma</h4>
            </div>
            <form class="form-vertical" action="<?php echo Yii::app()->createUrl('reports/ClassCouncilReport'); ?>" method="post">
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
                            <label for="count_days" class="control-label" style="width: 65%;"> Dia da reunião de conselho de classe</label>
                            <input type="number" name="count_days" placeholder="Digite o número de dias" style="width: 65%;" min="1" max="99" required>
                            <label for="hour" class="control-label" style="width: 30%;">Horário das reuniões</label>
                            <input type="time" id="hour" name="hour" min="00:00" max="23:59" style="width: 30%;" required>
                            <label for="year" class="control-label" style="width: 30%;">Ano das reuniões</label>
                            <select name="year" id="year" placeholder="Selecione o ano" style="width:100%" required>
                                <?php
                                $years = range(date('Y'), 2014);
                                echo "<option value='' selected>Selecione o ano</option>";
                                for ($i = 0; $i < count($years); $i++) {
                                    echo "<option value=" . $years[$i] . ">" . $years[$i] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="mounth" class="control-label" style="width: 30%;">Mês das reuniões</label>
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
                            <label for="mounth" class="control-label" style="width: 30%;">Trimestre</label>
                            <select name="quarterly" id="quarterly" style="width:100%" required>
                                <option value='' selected>Selecione o trimestre</option>
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
>>>>>>> d4c8487098dd8fe00dbd2ed32a571722ed607430
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
        <form class="form-vertical" action="<?php echo Yii::app()->createUrl('reports/QuarterlyReport'); ?>" method="post">
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
</div>
</div>
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