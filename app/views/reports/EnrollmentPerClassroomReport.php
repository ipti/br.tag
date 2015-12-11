<?php
/* @var $this ReportsController */
/* @var $report mixed */
/* @var $classroom Classroom*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentPerClassroomReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$classroom = Classroom::model()->findByPk($cid);
$school = SchoolIdentification::model()->findByPk($classroom->school_inep_fk);

?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div>

        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function ($) {
                    jQuery.ajax({'type': 'GET',
                        'data': {'cid':<?php echo $cid; ?>},
                        'url': '<?php echo Yii::app()->createUrl('reports/getEnrollmentPerClassroomInformation') ?>',
                        'success': function (data) {
                            gerarRelatorio(data);
                        }, 'error': function () {
                            limpar();
                        }, 'cache': false});
                    return false;
                }
            );
            /*]]>*/
        </script>

        <div id="report">
            <div id="container-header" style="width: 100%; margin: auto; text-align: center">
                <?php
                    if(isset($school->act_of_acknowledgement)){
                ?>
                <span style="display:block;clear:both;width: 40px;margin:0 auto;">
                <?= CHtml::image(Yii::app()->controller->createUrl('school/displayLogo', array('id'=>$school->inep_id)), 'logo', array('width'=>40, 'display:block;width:40px; margin:0 auto')) ?>
                </span>
                <p style="font-weight:bold;font-size:15px"><?php echo $school->name ?></p>
                <p style="font-size:10px;font-weight: bold"><?php echo $school->act_of_acknowledgement ?></p>
                <p style="font-size:8px;font-weight: bold">
                    <?= $school->address ?>,
                    <?= $school->edcensoCityFk->name ?>/
                    <?= $school->edcensoUfFk->name ?> - CEP: <?= $school->cep ?>
                </p>
                <span style="display: block; clear: both"></span>
                <?php
                    }else{
                ?>
                <img src="<?php echo yii::app()->baseUrl; ?>/images/boquim.png" width="40px" style="float: left; margin-right: 5px;">
                <span style="text-align: center; float: left; margin-top: 5px;">PREFEITURA MUNICIPAL DE BOQUIM<br>
                SECRETARIA MUNICIPAL DE EDUCAÇÃO, CULTURA, ESPORTE, LAZER E TURISMO</span>
                <span style="clear:both;display:block"></span>
                <?php }?>
            </div>
            <p style="text-align: center">
                <br>
                <span style="font-weight: bold; font-size: 14px">
                    RELATÓRIO DE MATRÍCULA / <?= $this->year; ?>
                </span>
            </p>

            <span style="clear:both;display:block"></span>
            <br><br>

            <div style="width: 100%; margin: 0 auto; text-align:center;margin-top: -15px;">
                <div style="float: left; text-align:left; margin: 5px 0;line-height: 14px;">
                    <div class="span4"><b>INEP: </b>
                        <?= isset($classroom->inep_id) ? $classroom->inep_id : 'Não informado' ?>
                    </div>
                    <div class="span4"><b>TURNO: </b>
                        <?php
                            switch ($classroom->turn){
                                case 'M':
                                    echo 'Manhã';
                                    break;
                                case 'T':
                                    echo 'Tarde';
                                    break;
                                case 'N':
                                    echo 'Noite';
                                    break;
                                case 'I':
                                    echo 'Integral';
                                    break;
                                default:
                                    echo 'Não informado';
                                    break;
                            }
                        ?>
                    </div>

                    <?php
                        $c;
                        //$stage = '7';
                        //$class = '41';
                        switch ($classroom->edcenso_stage_vs_modality_fk) {
                            case '4':
                                $c = '1º';
                                break;
                            case '5':
                                $c = '2º';
                                break;
                            case '6':
                                $c = '3º';
                                break;
                            case '7':
                                $c = '4º';
                                break;
                            case '8':
                                $c = '5º';
                                break;
                            case '9':
                                $c = '6º';
                                break;
                            case '10':
                                $c = '7º';
                                break;
                            case '11':
                                $c = '8º';
                                break;
                            case '14':
                                $c = '1º';
                                break;
                            case '15':
                                $c = '2º';
                                break;
                            case '16':
                                $c = '3º';
                                break;
                            case '17':
                                $c = '4º';
                                break;
                            case '18':
                                $c = '5º';
                                break;
                            case '19':
                                $c = '6º';
                                break;
                            case '20':
                                $c = '7º';
                                break;
                            case '21':
                                $c = '8º';
                                break;
                            case '41':
                                $c = '9º';
                                break;
                            case '25':
                            case '30':
                            case '35':
                                $c = '1º';
                                break;
                            case '26':
                            case '31':
                            case '36':
                                $c = '2º';
                                break;
                            case '27':
                            case '32':
                            case '37':
                                $c = '3º';
                                break;
                            case '28':
                            case '33':
                            case '38':
                                $c = '4º';
                                break;
                        }
                    ?>

                    <div class="span4"><b>SÉRIE/ANO: </b><?php echo (isset($c) ? $c : 'Não informado') ?></div>
                    <div class="span4"><b>TURMA: </b><?php echo (isset($classroom->name) ? $classroom->name : 'Não informado') ?></div>
                    <br>

                    <!--

                     ABAIXO: cruzar informações da turma pra puxar e imprimir o nome do professor automaticamente

                     -->
                    <div class="span10"><b>PROFESSOR(A): </b><?php echo 'Não informado' ?></div>
                </div>
            </div>

            <span style="clear:both;display:block"></span>
            <br>

            <table>
                <tr>
                    <th rowspan="2" style="width: 30px">Nº</th>
                    <th rowspan="2" style="width: 280px">Nome do aluno</th>
                    <th colspan="2" style="width: 40px">Gênero</th>
                    <th rowspan="2" style="width: 70px">Data de nascimento</th>
                    <th rowspan="2" style="width: 100px">Naturalidade</th>
                    <th colspan="3" style="width: 80px">Tipo de matrícula</th>
                    <th colspan="2" style="width: 60px">Situação na série</th>
                    <th rowspan="2" style="width: 280px">Endereço</th>
                </tr>
                <tr>
                    <th>M</th>
                    <th>F</th>
                    <th>P</th>
                    <th>PC</th>
                    <th>T</th>
                    <th>N</th>
                    <th>P</th>
                </tr>
            </table>
        </div>
    </div>
</div>

<style>
    table, td, tr, th {
        border: 1px solid black;
        border-collapse: collapse;
    }

    @page {
        size:auto;
        margin-bottom: 25mm;
    }

    @media print {
        #report{
            width: 100%;
        }
        #container-header {
            width: 425px !important;
        }

        table, td, tr, th {
            border-color: black !important;
        }
        table {
            page-break-inside: auto;
        }
        thead {
            display: table-header-group;
        }
        .report-table-empty td {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        #canvas-td {
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' preserveAspectRatio='none' viewBox='0 0 10 10'> <path d='M0 0 L0 10 L10 10' fill='black' /></svg>");
            background-repeat:no-repeat;
            background-position:center center;
            background-size: 100% 100%, auto;
        }
    }
</style>