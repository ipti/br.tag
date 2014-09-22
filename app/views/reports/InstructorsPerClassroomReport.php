<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/InstructorsPerClassroomReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$this->breadcrumbs = array(
    Yii::t('default', 'Reports') => array('/reports'),
    Yii::t('default', 'Instructors per Classroom'),
);

$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>
<style>
    #report-logo{
        margin: auto auto 10px;
        width: 200px;
    }
</style>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Instructors per Classroom'); ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div style="margin-top: 8px;">
        <div id="report-logo" class="visible-print">
            <img src="../../../images/sntaluzia.png">
        </div>
        <h3 class="heading visible-print"><?php echo Yii::t('default', 'Instructors per Classroom'); ?></h3> 
        <table class="table table-bordered table-striped">

            <tbody>
                <tr>
                    <th>Escola:</th><td colspan="5"><?php echo $school->inep_id . " - " . $school->name ?></td>
                <tr>
                <tr>
                    <th>Estado:</th><td colspan="2"><?php echo $school->edcensoUfFk->name . " - " . $school->edcensoUfFk->acronym ?></td>
                    <th>Municipio:</th><td colspan="2"><?php echo $school->edcensoCityFk->name ?></td>
                <tr>
                <tr>
                    <th>Localização:</th><td colspan="2"><?php echo ($school->location == 1 ? "URBANA" : "RURAL") ?></td>
                    <th>Dependência Administrativa:</th><td colspan="2"><?php
                    $ad = $school->administrative_dependence;
                    echo ($ad == 1 ? "FEDERAL" :
                            ($ad == 2 ? "ESTADUAL" :
                                    ($ad == 3 ? "MUNICIPAL" :
                                            "PRIVADA" )));
                    ?></td>
                <tr>
                 <?php
                $html = "";
                $i = 0;
                $id = 0;
                foreach ($report as $r) {
                    if ($id != $r['id']){
                        $i = 0;
                        $id = $r['id'];
                        $html .= "<tr><th colspan='6'><br></th><tr>"
                            ."</td><tr>"
                            . "<th>Código da Turma:</th>"
                            . "<td>" . $r["id"] . "</td>"
                            . "<th>Nome da Turma:</th>"
                            . "<td colspan='3'>" . $r["name"] . "</td>"
                            . "</tr>"
                            . "<tr>"
                            . "<th>Tipo de Atendimento:</th>"
                            . "<td>" . $r["assistance_type"] . "</td>"
                            . "<th>Dias da Semana da Turma:</th>"
                            . "<td colspan='3'>" . $r["week_days"] . "</td>"
                            . "</tr>"
                            . "<tr>"
                            . "<th>Horário de Funcionamento:</th>"
                            . "<td>" . $r["time"] . "</td>"
                            . "<th>Modalidade:</th>"
                            . "<td colspan='3'>" . $r["modality"] . "</td>"
                            . "</tr>"
                            . "<tr>"
                            . "<th>Etapa:</th>"
                            . "<td colspan='5'>" . $r["stage"] . "</td>"
                            . "</tr>"
                            . "<tr><th>Ordem</th><th>Identificação Única</th><th>Data de Nascimento</th><th>Nome do Doscente</th><th>Escolaridade</th><th>Disciplinas Ministradas</th></tr>";
                    
                    }
                    $i++;
                   $html .= "<tr>"
                            . "<td>".$i."</td>"
                            . "<td>".$r['instructor_id']."</td>"
                            . "<td>".$r['birthday_date']."</td>"
                            . "<td>".$r['instructor_name']."</td>"
                            . "<td>".$r['scholarity']."</td>"
                            . "<td>".$r['disciplines']."</td>"
                            . "</tr>";
                }
                echo $html;
                ?>
            </tbody>
        </table>
        <p>Emitido em <?php echo date('d/m/Y à\s h:i'); ?></p>
        <p class="visible-print">URL: <?php echo Yii::app()->getBaseUrl(true) . Yii::app()->request->requestUri ?></p>
    </div>
</div>