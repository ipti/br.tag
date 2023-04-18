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
                <div class="span2">
                    <a href="#" data-toggle="modal" data-target="#quarterly-report" class="widget-stats" target="_blank">
                        <span class="glyphicons signal"><i></i></span>
                        <span class="txt">Relatório Trimestral</span>
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
        <div class="modal fade modal-content" id="quarterly-report" tabindex="-1" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title" id="myModalLabel">Relatório Trimestral - Escolha a Turma</h4>
            </div>
            <form class="form-vertical" action="" method="post">
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class=" span12">
                            <?php
                            echo CHtml::label(yii::t('default', 'Classroom'), 'year', array('class' => 'control-label'));
                            ?>
                            <select name="classroom3" id="classroom3" placeholder="Selecione a turma" style="width:100%">
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