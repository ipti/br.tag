<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsPerClassroomReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$this->breadcrumbs = array(
    Yii::t('default', 'Reports') => array('/reports'),
    Yii::t('default', 'Students per Classroom'),
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
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Students per Classroom'); ?></h3>  
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
        <h3 class="heading visible-print"><?php echo Yii::t('default', 'Students per Classroom'); ?></h3> 
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Escola:</th><td colspan="7"><?php echo $school->inep_id . " - " . $school->name ?></td>
                <tr>
                <tr>
                    <th>Estado:</th><td colspan="3"><?php echo $school->edcensoUfFk->name . " - " . $school->edcensoUfFk->acronym ?></td>
                    <th>Municipio:</th><td colspan="3"><?php echo $school->edcensoCityFk->name ?></td>
                <tr>
                <tr>
                    <th>Localização:</th><td colspan="3"><?php echo ($school->location == 1 ? "URBANA" : "RURAL") ?></td>
                    <th>Dependência Administrativa:</th><td colspan="3"><?php
                    $ad = $school->administrative_dependence;
                    echo ($ad == 1 ? "FEDERAL" :
                            ($ad == 2 ? "ESTADUAL" :
                                    ($ad == 3 ? "MUNICIPAL" :
                                            "PRIVADA" )));
                    ?></td>
                <tr>
                <tr>
                    <td colspan="8"></td>
                <tr>
            </thead>
            <tbody>
                <tr><th>Ordem</th><th>Código da Turma</th><th>Nome da Turma</th><th>Horário de Funcionamento</th><th>Tipo de Atendimento</th><th>Modalidade</th><th>Etapa</th><th>Número de Alunos</th></tr>
                <?php
                $html = "";
                $i = 0;
                foreach ($report as $r) {
                    $i++;
                    $html .= "<tr>"
                            . "<td>" . $i . "</td>"
                            . "<td>" . $r["inep_id"] . "</td>"
                            . "<td>" . $r["name"] . "</td>"
                            . "<td>" . $r["time"] . "</td>"
                            . "<td>" . $r["assistance_type"] . "</td>"
                            . "<td>" . $r["modality"] . "</td>"
                            . "<td>" . $r["stage"] . "</td>"
                            . "<td>" . $r["students"] . "</td>"
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