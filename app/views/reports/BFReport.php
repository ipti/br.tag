<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/BFReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$this->breadcrumbs = array(
    Yii::t('default', 'Reports') => array('/reports'),
    Yii::t('default', 'Bolsa Família'),
);

$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>


<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Relatório do Bolsa Família'); ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div>
        <?php $this->renderPartial('head'); ?>
        <table class="table table-bordered table-striped">
            <?php
            $html = "";
            foreach ($report as $name => $r) {
                $html .= "<tr>"
                        . "<td rowspan='4' colspan='3'>"
                        . $name
                        . "<br> Nascimento: " . $r['Info']['birthday']
                        . "<br> NIS: " . $r['Info']['NIS']
                        . "<br> Turma: " . $r['Info']['Classroom']
                        . "</td>"
                        . "<th>Mês:</th>"
                        . "<th>Frequência:</th>"
                        . "</tr>";
                foreach ($r['Frequency'] as $month => $frequency) {
                    setlocale(LC_ALL, NULL);
                    setlocale(LC_ALL, "pt_BR.utf8", "pt_BR", "ptb", "ptb.utf8");
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
        </table>
    </div>
</div>
<script>
    var baseUrl = "<?php echo Yii::app()->baseUrl; ?>";
</script>