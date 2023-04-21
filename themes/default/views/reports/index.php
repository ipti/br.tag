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
        <div class="span12">
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
            <div class="span12">
                <div class="span2">
                    <a href="#" data-toggle="modal" data-target="#reportFamilyBag" class="widget-stats" target="_blank">
                        <!-- <a  href="<?php //echo Yii::app()->createUrl('reports/BFReport')
                                        ?>" class="widget-stats"> -->
                        <div><i class="fa-percent fa fa-4x"></i></div>
                        <span class="report-title">Frequência para o Bolsa Família</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/NumberStudentsPerClassroomReport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-sort-numeric-asc fa-4x"></i></div>
                        <span class="report-title">Número de Alunos por Turma</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/InstructorsPerClassroomReport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-graduation-cap fa-4x"></i></div>
                        <span class="report-title">Professores por Turma</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/studentsbetween5and14yearsoldreport', array('id' => Yii::app()->user->school)) ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-heartbeat fa-4x"></i></div>
                        <span class="report-title">Alunos com Idade entre 5 e 14 Anos (SUS)</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/enrollmentcomparativeanalysisreport') ?>" class="widget-stats" target="_blank">
                        <div><i class="fa fa-exchange fa-4x"></i></div>
                        <span class="report-title">Análise Comparativa de Matrículas</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('reports/schoolprofessionalnumberbyclassroomreport') ?>" class="widget-stats" target="_blank">
                        <div><span class="glyphicons signal"><i></i></span></div>
                        <span class="report-title">Número de Profissionais por turma</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
            <div class="span12" style="margin: 10px 0 0 0">

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
                <?php if(INSTANCE == "BUZIOS" || INSTANCE == "TREINAMENTO" || INSTANCE == "LOCALHOST") {?>
                <div class="span2">
                    <a href="#" data-toggle="modal" data-target="#quarterly-class-council" class="widget-stats" target="_blank">
                        <div><span class="glyphicons signal"><i></i></span></div>
                        <span class="report-title">Ata de Conselho de Classe</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <?php }?>
                <?php if(INSTANCE == "BUZIOS" || INSTANCE == "TREINAMENTO" || INSTANCE == "LOCALHOST") {?>
                <div class="span2">
                    <a href="#" data-toggle="modal" data-target="#quarterly-report" class="widget-stats" target="_blank">
                        <div><span class="glyphicons signal"><i></i></span></div>
                        <span class="report-title">Relatório Trimestral do Aluno</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <?php }?>
            </div>
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
    </div>

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