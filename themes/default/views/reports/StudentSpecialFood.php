<?php

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentPerClassroomReport/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$stage = EdcensoStageVsModality::model()->findByPk($classroom->edcenso_stage_vs_modality_fk)->name;

$school = SchoolIdentification::model()->findByPk($classroom->school_inep_fk);
?>



<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Alunos Cardápios Especiais') . ' - ' . Yii::app()->user->year; ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <div>
        <table class="table table-bordered table-striped" style="font-size: 11px">
            <tr>
                <th rowspan="" style="text-align: center;">Nº</th>
                <th rowspan="" style="text-align: center;">ID INEP</th>
                <th rowspan="">ALUNO</th>
                <th rowspan="" style="text-align: center;">DATA DE NASCIMENTO</th>
                <th colspan="9" style="text-align: center;"> Restrições </th>
            </tr>
            <?php
            $rows = "";
            foreach ($report as $key=>$r){
                //validacoes
                if($r['celiac'] == 1){ $celiac =  '<i class="fa fa-close" style="color: black"></i>';}else{
                    $celiac =  '';};
                if($r['diabetes'] == 1){ $diabetes =  '<i class="fa fa-close" style="color: black"></i>';}else{
                    $diabetes =  '';};
                if($r['hypertension'] == 1){ $hypertension =  '<i class="fa fa-close" style="color: black"></i>';}else{
                    $hypertension =  '';};
                if($r['iron_deficiency_anemia'] == 1){ $iron_deficiency_anemia =  '<i class="fa fa-close" style="color: black"></i>';}else{
                    $iron_deficiency_anemia =  '';};
                if($r['sickle_cell_anemia'] == 1){ $sickle_cell_anemia =  '<i class="fa fa-close" style="color: black"></i>';}else{
                    $sickle_cell_anemia =  '';};
                if($r['lactose_intolerance'] == 1){ $lactose_intolerance =  '<i class="fa fa-close" style="color: black"></i>';}else{
                    $lactose_intolerance =  '';};
                if($r['malnutrition'] == 1){ $malnutrition =  '<i class="fa fa-close" style="color: black"></i>';}else{
                    $malnutrition =  '';};
                if($r['obesity'] == 1){ $obesity =  '<i class="fa fa-close" style="color: black"></i>';}else{
                    $obesity =  '';};
                if($r['others'] != null && $r['others'] != "") {
                    $others = $r['others'];
                }else {
                    $others = "Não possui";
                }
                $rows .= "<tr>"
                    . "<td style='text-align: center;'>" . ($key + 1) . "</td>"
                    . "<td style='text-align: center;'>" . $r['inep_id'] . "</td>"
                    . "<td>" . $r['nome_aluno'] . "</td>"
                    . "<td style='text-align: center;'>" . $r['birthday'] . "</td>"
                    . "<td style='text-align: center;'>Doença celíaca</td>
                        <td style='text-align: center;'>Diabetes</td>
                        <td style='text-align: center;'>Hipertensão</td>
                        <td style='text-align: center;'>Anemia ferropriva</td>
                        <td style='text-align: center;'>Anemia falciforme</td>
                        <td style='text-align: center;'>Intolerância à lactose</td>
                        <td style='text-align: center;'>Desnutrição</td>
                        <td style='text-align: center;'>Obesidade</td>
                        <td style='text-align: center;'>Outros</td>
                        <tr>
                        <th colspan='4'>
                                <td style='text-align: center;'>".$celiac."</td>
                                <td style='text-align: center;'>".$diabetes."</td>
                                <td style='text-align: center;'>".$hypertension."</td>
                                <td style='text-align: center;'>".$iron_deficiency_anemia."</td>
                                <td style='text-align: center;'>".$sickle_cell_anemia."</td>
                                <td style='text-align: center;'>".$lactose_intolerance."</td>
                                <td style='text-align: center;'>".$malnutrition."</td>
                                <td style='text-align: center;'>".$obesity."</td>
                                <td style='text-align: center;'>".$others."</td>
                            </th>
                            </tr>";
                $rows .= "</tr>";
            }
            echo $rows;
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
    @media print{
        .hidden-print {
            display: none;
        }
        @page {
            size: landscape;
        }
    }
</style>
