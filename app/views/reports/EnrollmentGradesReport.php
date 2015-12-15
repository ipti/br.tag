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
                        'data': {'enrollment_id':<?php echo $enrollment_id; ?>},
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

        <div id="report">

            <div style="margin: 0px 0 0 50px; width: calc(100% - 51px); text-align:center">

                <div style="float: left; text-align: justify;margin: 5px 0 5px -20px;line-height: 14px;">
                    <div class="span9"><b>ALUNO: </b><?= $school->name ?></div>
                    <div class="span2"><b>TURMA: </b><?= $school->inep_id ?></div>
                    <br>

                    <div class="span10"><b>ENDEREÇO: </b><?= $school->address ?></div>
                    <br>

                </div>
            </div>
            <br>


            <div style="page-break-before: always;"></div>

            <div style="margin: 50px 0 15px 0;text-align: center;line-height: 15px;";>_______________________<br>Ano Letivo</div>

            <table style="margin: 0px 0 0 50px; font-size: 8px; width: calc(100% - 51px);" class="table table-bordered report-table-empty">
              <tr>
                <th colspan="18" style="text-align: center">RENDIMENTO ESCOLAR POR ATIVIDADES</th>
              </tr>
              <tr>
                <td></td>
                <td style="text-align: center;">PARTES&nbsp;DO&nbsp;CURRÍCULO</td>
                <td colspan="9" style="text-align: center; font-weight: bold">BASE NACIONAL COMUM</td>
                <td colspan="4" style="text-align: center; font-weight: bold">PARTE DIVERSIFICADA</td>
                <td rowspan="2" class="vertical-text"><div>DIAS&nbsp;LETIVOS</div></td>
                <td rowspan="2" class="vertical-text"><div>CARGA&nbsp;HORÁRIA</div></td>
                <td rowspan="2" class="vertical-text"><div>Nº&nbsp;DE&nbsp;FALTAS</div></td>
              </tr>
              <tr>
                <td style="vertical-align: bottom;"><div style="transform: translate(5px, 0px) rotate(270deg);width: 0;line-height: 53px;margin: 0px 10px 0px 0px;">BIMESTRES</div></td>
                <td class="vertical-text"><canvas width="100%" height="100%"></canvas></td>
                <td class="vertical-text"><div>LÍNGUA&nbsp;PORTUGUESA</div></td>
                <td class="vertical-text"><div>MATEMÁTICA</div></td>
                <td class="vertical-text"><div>HISTÓRIA</div></td>
                <td class="vertical-text"><div>GEOGRAFIA</div></td>
                <td class="vertical-text"><div>ARTES</div></td>
                <td class="vertical-text"><div>EDUCAÇÃO&nbsp;FÍSICA</div></td>
                <td class="vertical-text"><div>ENSINO&nbsp;RELIGIOSO</div></td>
                <td class="vertical-text"><div>CIÊNCIAS&nbsp;NATURAIS</div></td>
                <td class="vertical-text"><div></div></td>
                <td class="vertical-text"><div>REDAÇÃO</div></td>
                <td class="vertical-text"><div>SOCIEDADE&nbsp;BRASILEIRA</div></td>
                <td class="vertical-text"><div></div></td>
                <td class="vertical-text"><div></div></td>
              </tr>
              <tr>
                <td>1º</td>
                <td style="text-align: center;">AVALIAÇÃO</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td>2º</td>
                <td style="text-align: center;">AVALIAÇÃO</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td>3º</td>
                <td style="text-align: center;">AVALIAÇÃO</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td>4º</td>
                <td style="text-align: center;">AVALIAÇÃO</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td colspan="2">MÉDIA ANUAL</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td colspan="2">NOTA DA PROVA FINAL</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td colspan="2">MÉDIA FINAL</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td colspan="2">TOTAL DE AULAS DADAS</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td colspan="2">TOTAL DE FALTAS</td>
               <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td colspan="2">FREQUÊNCIAS %</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
            </table>
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