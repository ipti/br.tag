<?php
/* @var $this ReportsController */
/* @var $report mixed*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/BFReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default','Reports'));

$this->breadcrumbs=array(
	Yii::t('default', 'Reports')=>array('/reports'),
	Yii::t('default', 'Bolsa Família'),
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
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Relatório do Bolsa Família'); ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">

    <div class="widget" id="widget-frequency" style="margin-top: 8px;">
        <div id="report-logo" class="visible-print">
            <img src="../../../images/sntaluzia.png">
        </div>
            <table id="frequency" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Escola:</th><td colspan="3"><?php echo $school->inep_id . " - " . $school->name ?></td>
                    <tr>
                    <tr>
                        <th>Estado:</th><td ><?php echo $school->edcensoUfFk->name . " - " . $school->edcensoUfFk->acronym ?></td>
                        <th>Municipio:</th><td colspan='2'><?php echo $school->edcensoCityFk->name ?></td>
                    <tr>
                    <tr>
                        <th>Localização:</th><td><?php echo ($school->location == 1 ? "URBANA" : "RURAL") ?></td>
                        <th>Dependência Administrativa:</th><td colspan='2'><?php
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
                    <?php
                        $html = "";
                        foreach($report as $name => $r){
                            $html .= "<tr>"
                                        . "<td rowspan='4' colspan='3'>"
                                            . $name
                                            . "<br> Nascimento: ".$r['Info']['birthday']
                                            . "<br> NIS: ".$r['Info']['NIS']
                                            . "<br> Turma: ".$r['Info']['Classroom']
                                        . "</td>"
                                        . "<th>Mês:</th>"
                                        . "<th>Frequência:</th>"
                                    . "</tr>";
                            foreach($r['Frequency'] as $month => $frequency){
                                setlocale(LC_ALL, NULL);
                                setlocale(LC_ALL, "pt_BR.utf8","pt_BR", "ptb", "ptb.utf8");
                                $time = mktime(0, 0, 0, $month);
                                $monthName = strftime("%B", $time);

                                $html .= "<tr>"
                                        . "<td class='center'>"
                                            . $monthName
                                        . "</td>"
                                        . "<td class='center' >"
                                            . $frequency
                                        . "</td>"
                                        . "</tr>";
                            }
                            $html .= "<tr><td colspan='3'></td></tr>";
                        }
                        echo $html;
                    ?>
                </tbody>
            </table>
    </div>
</div>
<script>
    var baseUrl = "<?php echo Yii::app()->baseUrl; ?>";
</script>