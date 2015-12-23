<?php
/* @var $this ReportsController */
/* @var $enrollment StudentEnrollment */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentGradesReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?>
                <i></i></a>
        </div>
    </div>
</div>


<div class="innerLR boquim">
    <div>
        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function ($) {
                    jQuery.ajax({
                        'type': 'GET',
                        'data': {'enrollment_id':<?php echo $enrollment->id; ?>},
                        'url': '<?php echo Yii::app()->createUrl('reports/getStudentsFileBoquimInformation') ?>',
                        'success': function (data) {
                            gerarRelatorio(data);
                        }, 'error': function () {
                        }, 'cache': false
                    });
                    return false;
                }
            );
            /*]]>*/
        </script>
        <br>
        <?php $column = $column2 = $row1 = $row2 = $row3 = $row4 = $row5 = ''; $colspan = $colspan2 = 0;?>
        <?php foreach ( $enrollment->grades as $grade ) {
            if($grade->disciplineFk->id < 99){
                $colspan++;
                $column .= "<td class='vertical-text'><div>".$grade->disciplineFk->name."</div></td>";
                $row1 .= "<td>".$grade->grade1."</td>";
                $row2 .= "<td>".$grade->grade2."</td>";
                $row3 .= "<td>".$grade->grade3."</td>";
                $row4 .= "<td>".$grade->grade4."</td>";
                $row5 .= "<td>".($grade->grade1+$grade->grade2+$grade->grade3+$grade->grade4)/4;
            }else{
                $colspan2++;
                $column2 .= "<td class='vertical-text'><div>".$grade->disciplineFk->name."</div></td>";
                @$row_1 .= "<td>".$grade->grade1."</td>";
                @$row_2 .= "<td>".$grade->grade2."</td>";
                @$row_3 .= "<td>".$grade->grade3."</td>";
                @$row_4 .= "<td>".$grade->grade4."</td>";
                @$row_5 .= "<td>".($grade->grade1+$grade->grade2+$grade->grade3+$grade->grade4)/4;
            }

        }
        ?>
        <div id="report">

            <div style="margin: 0px 0 0 50px; width: calc(100% - 51px); text-align:center">

                <div style="float: left; text-align: justify;margin: 5px 0 5px -20px;line-height: 14px;">
                    <div class="span9"><b>ALUNO: </b><?= $enrollment->studentFk->name ?></div>
                    <div class="span9"><b>TURMA: </b><?= $enrollment->classroomFk->name ?></div>
                    <div class="span9"><b>ANO LETIVO: </b><?= $enrollment->classroomFk->school_year ?></div>
                </div>
            </div>
            <br>
            <table style="margin: 0px 0 0 50px; font-size: 8px; width: calc(100% - 51px);" class="table table-bordered report-table-empty">
              <tr>
                <th colspan="18" style="text-align: center">RENDIMENTO ESCOLAR POR ATIVIDADES</th>
              </tr>
              <tr>
                <td></td>
                <td style="text-align: center;">PARTES&nbsp;DO&nbsp;CURRÍCULO</td>
                <td colspan="<?php echo $colspan?>" style="text-align: center; font-weight: bold">BASE NACIONAL COMUM</td>
                <td colspan="<?php echo $colspan2?>" style="text-align: center; font-weight: bold">PARTE DIVERSIFICADA</td>
                <td rowspan="9" class="vertical-text"><div>DIAS&nbsp;LETIVOS</div></td>
                <td rowspan="9" class="vertical-text"><div>CARGA&nbsp;HORÁRIA</div></td>
                <td rowspan="9" class="vertical-text"><div>Nº&nbsp;DE&nbsp;FALTAS</div></td>
              </tr>
              <tr>
                <td style="vertical-align: bottom;"><div style="transform: translate(5px, 0px) rotate(270deg);width: 0;line-height: 53px;margin: 0px 10px 0px 0px;">BIMESTRES</div></td>
                <td class="vertical-text"><canvas width="100%" height="100%"></canvas></td>
                <?php echo $column; ?><?php echo $column2; ?>
              </tr>
              <tr>
                <td>1º</td>
                <td style="text-align: center;">AVALIAÇÃO</td>
                  <?php echo $row1; ?>
                  <?php echo $row_1; ?>
              </tr>
              <tr>
                <td>2º</td>
                <td style="text-align: center;">AVALIAÇÃO</td>
                  <?php echo $row2; ?>
                  <?php echo $row_2; ?>
              </tr>
              <tr>
                <td>3º</td>
                  <td style="text-align: center;">AVALIAÇÃO</td>
                  <?php echo $row3; ?>
                  <?php echo $row_3; ?>
              </tr>
              <tr>
                <td>4º</td>
                  <td style="text-align: center;">AVALIAÇÃO</td>
                  <?php echo $row4; ?>
                  <?php echo $row_4; ?>
              </tr>
              <tr>
                <td colspan="2">MÉDIA ANUAL</td>
                  <?php echo $row5; ?>
                  <?php echo $row_5; ?>
              </tr>
              <tr>
                <td colspan="2">NOTA DA PROVA FINAL</td>
                  <?php for($i =0; $i < ($colspan+$colspan2); $i++) {
                    echo '<td></td>';
                  }
                  ?>
              </tr>
              <tr>
                <td colspan="2">MÉDIA FINAL</td>
                  <?php echo $row5; ?>
                  <?php echo $row_5; ?>
              </tr>
              <tr>
                <td style="text-align:right;" colspan="<?php echo $colspan+$colspan2+2?>">TOTAL DE AULAS DADAS</td>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                <td style="text-align:right;" colspan="<?php echo $colspan+$colspan2+2?>">TOTAL DE FALTAS</td>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                <td style="text-align:right;" colspan="<?php echo $colspan+$colspan2+2?>">FREQUÊNCIAS %</td>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
            </table>
            <br/>
            <div style="text-align:right;">Resultado Final _____________________________</div>
            <div style="text-align:center">APTO PARA CURSAR O _____________ ANO DO ENSINO FUNDAMENTAL<div>
                    <div style="text-align: center;line-height: 15px;">_________________________________________________________<br>Local e data<div>
                     <div style="float: left;line-height: 15px; width:50%">_________________________________________________________<br>Assinatura do(a) Secretário (a)</div>
                     <div style="float: right;line-height: 15px;width:50%">_________________________________________________________<br>Assinatura do(a) Diretor(a)</div>
        </div>
    </div>
</div>
</div>
<style>
    @media print {
        #container-header {
            width: 425px !important;
        }

        table, td, tr, th {
            border-color: black !important;
        }

        .report-table-empty td {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .vertical-text {
            height: 110px;
            vertical-align: bottom !IMPORTANT;
        }

        .vertical-text div {
            transform: translate(5px, 0px) rotate(270deg);
            width: 5px;
            line-height: 13px;
            margin: 0px 10px 0px 0px;
        }

        #canvas-td {
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' preserveAspectRatio='none' viewBox='0 0 10 10'> <path d='M0 0 L0 10 L10 10' fill='black' /></svg>");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 100% 100%, auto;
        }
    }
</style>