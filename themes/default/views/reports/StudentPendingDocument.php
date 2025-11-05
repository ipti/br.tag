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
    <h3><?php echo Yii::t('default', 'Documentos Pendentes') . ' - ' . Yii::app()->user->year; ?></h3>
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
                <th colspan="8" style="text-align: center;"> Documentos Pendentes</th>
            </tr>
            <?php
            $rows = "";
            $icon = '<i class="fa fa-close" style="color: black"></i>';
            foreach ($report as $key=>$r){
                //validacoes
                $receivedCc = ($r['received_cc'] == 0) ? $icon : '';
                $receivedAddress = ($r['received_address'] == 0) ? $icon : '';
                $receivedPhoto = ($r['received_photo'] == 0) ? $icon : '';
                $receivedNis = ($r['received_nis'] == 0) ? $icon : '';
                $receivedResponsableRg = ($r['received_responsable_rg'] == 0) ? $icon : '';
                $receivedResponsableCpf  = ($r['received_responsable_cpf'] == 0) ? $icon : '';

                $rows .= "<tr>"
                    . "<td style='text-align: center;'>" . ($key + 1) . "</td>"
                    . "<td style='text-align: center;'>" . $r['inep_id'] . "</td>"
                    . "<td>" . $r['nome_aluno'] . "</td>"
                    . "<td style='text-align: center;'>" . $r['birthday'] . "</td>"
                    .       "<td style='text-align: center;'>Certidão de Nascimento</td>
                            <td style='text-align: center;'>Comprovante de Residência</td>
                            <td style='text-align: center;'>Foto</td>
                            <td style='text-align: center;'>NIS</td>
                            <td style='text-align: center;'>Rg do Responsável</td>
                            <td style='text-align: center;'>CPF do Responsável</td>
                            <tr>
                            <th colspan='4'>
                            <td style='text-align: center;'>".$receivedCc."</td>
                            <td style='text-align: center;'>".$receivedAddress."</td>
                            <td style='text-align: center;'>".$receivedPhoto."</td>
                            <td style='text-align: center;'>".$receivedNis."</td>
                            <td style='text-align: center;'>".$receivedResponsableRg."</td>
                            <td style='text-align: center;'>".$receivedResponsableCpf."</td>
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
