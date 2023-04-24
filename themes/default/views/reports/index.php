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
            <div class="container-box">

                <p>Alunos</p>

                <a href="<?php echo Yii::app()->createUrl('reports/NumberStudentsPerClassroomReport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Número de Alunos por Turma</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/incompatiblestudentagebyclassroomreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Alunos com Idade Incompatível por Turma</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <button type="button" class="report-box-container" data-toggle="modal" data-target="#studentperclassroom" target="_blank">
                    <div class="pull-left" style="height: 100%;margin-right: 10px;">
                        <div class="t-icon-schedule report-icon"></div>
                    </div>
                    <div class="pull-left">
                        <span class="title">Alunos por turma</span><br>
                        <span class="subtitle">Como funciona a página inicial do tag?</span>
                    </div>
                </button>

                <button type="button" class="report-box-container" data-toggle="modal" data-target="#reportFamilyBag" target="_blank">
                    <div class="pull-left" style="height: 100%;margin-right: 10px;">
                        <div class="t-icon-schedule report-icon"></div>
                    </div>
                    <div class="pull-left">
                        <span class="title">Participantes do bolsa familia</span><br>
                        <span class="subtitle">Como funciona a página inicial do tag?</span>
                    </div>
                </button>

                <a href="<?php echo Yii::app()->createUrl('reports/studentpendingdocument') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Alunos com Documentos Pendentes</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsbyclassroomreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação de alunos por turma</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <?php if (INSTANCE == "BUZIOS" || INSTANCE == "TREINAMENTO" || INSTANCE == "LOCALHOST") { ?>
                    <button type="button" class="report-box-container" data-toggle="modal" data-target="#quarterly-class-council" target="_blank">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Ata de Conselho de Classe</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                <?php } ?>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsbetween5and14yearsoldreport', array('id' => Yii::app()->user->school)) ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Alunos com Idade entre 5 e 14 Anos (SUS)</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsinalphabeticalorderrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relaçao de Alunos em ordem Alfabética</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentspecialfood') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Alunos - Cardápios Especiais</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/outoftownstudentsreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação de alunos fora da cidade</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <?php if (INSTANCE == "BUZIOS" || INSTANCE == "TREINAMENTO" || INSTANCE == "LOCALHOST") { ?>
                    <button type="button" class="report-box-container" data-toggle="modal" data-target="#quarterly-report" target="_blank">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relatório Trimestral do Aluno</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                <?php } ?>
            </div>
            <div class="container-box">

                <p>Professores</p>

                <a href="<?php echo Yii::app()->createUrl('reports/studentinstructornumbersrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Nº de professores por turma</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/disciplineandinstructorrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação disciplina por docente</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/InstructorsPerClassroomReport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Professores por turma </span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/classroomwithoutinstructorrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação Turmas sem Instrutor</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>
            </div>
            <div class="container-box">

                <p>Escola</p>

                <a href="<?php echo Yii::app()->createUrl('reports/studentsusingschooltransportationrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação Transporte Escolar</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/educationalassistantperclassroomreport', array('id' => Yii::app()->user->school)) ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Auxiliar educacional por turma</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/enrollmentcomparativeanalysisreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Analise comparativa de matrículas </span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/schoolprofessionalnumberbyclassroomreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Número de Profissionais por turma</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/complementaractivityassistantbyclassroomreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Monitores de Atividade Complementar por Turma</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentinstructornumbersrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Número de Alunos e Professores por Turma</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

                <a href="<?php echo Yii::app()->createUrl('reports/studentswithdisabilitiesrelationreport') ?>">
                    <button type="button" class="report-box-container">
                        <div class="pull-left" style="height: 100%;margin-right: 10px;">
                            <div class="t-icon-schedule report-icon"></div>
                        </div>
                        <div class="pull-left">
                            <span class="title">Relação Acessibilidade</span><br>
                            <span class="subtitle">Como funciona a página inicial do tag?</span>
                        </div>
                    </button>
                </a>

            </div>
        </div>
    </div>
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
                            <label for="count_days" class="control-label" style="width: 65%;">Quantidade de dias de reunião de conselho de classe</label>
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
                        echo CHtml::label(yii::t('default', 'Student Fk'), 'year', array('class' => 'control-label'));
                        ?>
                        <select name="student" id="student" placeholder="Selecione o aluno" style="width:100%" required>
                            <?php
                            echo "<option value='' selected>Selecione o aluno</option>";
                            foreach ($students as $student) {
                                echo "<option value='" . $student->id . "'>" . $student->name . "</option>";
                            }
                            ?>
                        </select>
                        <div class="classroom-student-error" style="display:none;color:#D21C1C;margin-left:5px;font-size:12px;">
                            <span>O aluno não está matriculado em nenhuma turma nesse ano.
                            </span>
                            <a href="#" data-toggle="modal" data-target="#change-year" target="_blank" data-dismiss="modal">
                                Clique aqui para mudar o ano.
                            </a>
                        </div>
                        <div class="classroom-student-container" style="display: none;">
                            <?php
                            echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                            ?>
                            <select name="classroom_student" id="classroom_student" style="width: 100%;" required>
                                <option value="">Selecione a Turma</option>
                            </select>
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