<?php
/* @var $this ReportsController */
/* @var $report mixed */
/* @var $classroom Classroom*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentPerClassroomReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$stage = EdcensoStageVsModality::model()->findByPk($classroom->edcenso_stage_vs_modality_fk)->name;

?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Enrollment per Classroom'); ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div>
        <?php $this->renderPartial('head'); ?>
        <table class="table table-bordered table-striped">
            <tr><th>Ano Letivo: <?php echo $classroom->school_year ?></th><th>Etapa: <?php echo $stage ?></th><th>Turma: <?php echo $classroom->name?></th></tr>
        </table>
        <br>
        <table class="table table-bordered table-striped">
            <tr><th>Matrícula</th><th>Nome</th><th>Sexo</th><th>Data Nascimento</th><th>Nac.</th><th>Naturalidade</th><th>Residência</th><th>Certidão Civil</th><th>Pais</th></tr>
            <?php
            $html = "";
            foreach ($report as $r) {
                $html .= "<tr>"
                        . "<td>" . $r["enrollment"] . "</td>"
                        . "<td>" . $r["name"] . "</td>"
                        . "<td>" . $r["sex"] . "</td>"
                        . "<td>" . $r["birthday"] . "</td>"
                        . "<td>" . $r["nation"] . "</td>"
                        . "<td>" . $r["city"] . "</td>"
                        . "<td>" . $r["address"] . "</td>"
                        . "<td>" . ($r["cc"] == 1 ? ('Nº'.$r["cc_number"].'<br>L.'.$r["cc_book"].'<br>F.'.$r["cc_sheet"]):($r["cc_new"])) . "</td>"
                        . "<td>" . $r["parents"] . "</td>"
                        . "</tr>";
            }
            echo $html;
            ?>
        </table>
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>