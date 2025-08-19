<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/BFReport/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>


<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Relatório do Bolsa Família') . ' - ' . Yii::app()->user->year; ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <div>
        <table class="table table-bordered table-striped">
            <?php
            $html = "";
            $nameClassroom = [];
            if (!empty($reports)) {
                foreach ($reports as $classroom => $report) {
                    $html .= "<tr><th colspan='3'>{$classroom}</th></tr>";
                    foreach ($report as $name => $r) {

                        $html .= "<tr>"
                            . "<td rowspan='" . (count($r['Classes']) + 1) . "' colspan='1'>"
                            . $name
                            . "<br> Nascimento: " . $r['Info']['birthday']
                            . "<br> NIS: " . $r['Info']['NIS']
                            . "<br> Turma: " . $r['Info']['Classroom']
                            . "</td>"
                            . "<th>Mês:</th>"
                            . "<th>Frequência:</th>"
                            . "</tr>";
                        foreach ($r['Classes'] as $month => $classes) {
                            setlocale(LC_ALL, NULL);
                            setlocale(LC_ALL, "pt_BR.utf8", "pt_BR", "ptb", "ptb.utf8");
                            $time = mktime(0, 0, 0, $month);
                            $monthName = strftime("%B", $time);

                            $html .= "<tr>"
                                . "<td class='center'>"
                                . Yii::t('default', $monthName)
                                . "</td>"
                                . "<td class='center' >"
                                . $classes
                                . "</td>"
                                . "</tr>";
                        }
                    }
                }
            } else {
                $html .= "<div class='no-enrollments'>Não há alunos matriculados na turma.</div>";
            }
            echo $html;
            ?>
        </table>
    </div>
    <?php $this->renderPartial('footer'); ?>
</div>

<script>
    function imprimirPagina() {
      window.print();
    }
</script>

<style>
    @media print {
        .hidden-print {
            display: none;
        }
        @page {
            size: portrait;
        }
    }
</style>
