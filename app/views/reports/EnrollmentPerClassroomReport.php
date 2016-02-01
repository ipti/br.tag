<?php
/* @var $this ReportsController */
/* @var $report mixed */
/* @var $classroom Classroom*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentPerClassroomReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$stage = EdcensoStageVsModality::model()->findByPk($classroom->edcenso_stage_vs_modality_fk)->name;
$school = SchoolIdentification::model()->findByPk($classroom->school_inep_fk)

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
        <div id="container-header" style="text-align: center; width: 100%; margin: 0 auto;margin-top: -30px;">
            <div>
                <img src="<?php echo yii::app()->baseUrl; ?>/images/boquim.png" width="40px" style="margin: 35px 0 5px 0">
            </div>
                <span style="font-size: 10px">
                    ESTADO DE SERGIPE<br>
                    SECRETARIA MUNICIPAL DE EDUCAÇÃO, CULTURA, ESPORTE, LAZER E TURISMO
                </span>
            <br>
            <span style="font-size: 14px;">RELATÓRIO DE MATRÍCULA / <?= $classroom->school_year?></span>
            <span style="clear:both;display:block"></span>
        </div>

        <br>

        <table class="table">
            <tr>
                <td colspan="3">ESCOLA: <?= $school->name?></td>
                <td>CÓDIGO: <?= $classroom->school_inep_fk?></td>
            </tr>
            <tr>
                <td colspan="1">ENDEREÇO: <?= $school->address . (strlen($school->address_number) != 0 ? ", " . $school->address_number : "")?></td>
                <td colspan="1">TURNO:
                    <?php
                        switch($classroom->turn){
                            case "M":
                                echo "MANHÃ";
                                break;
                            case "T":
                                echo "TARDE";
                                break;
                            case "N":
                                echo "NOITE";
                                break;
                        }
                    ?>
                </td>
                <td colspan="1">SÉRIE/ANO:
                    <?php
                        $stage = "";
                        switch ($classroom->edcenso_stage_vs_modality_fk) {
                            case '4':
                                $stage = '1ª SÉRIE';
                                break;
                            case '5':
                                $stage = '2ª SÉRIE';
                                break;
                            case '6':
                                $stage = '3ª SÉRIE';
                                break;
                            case '7':
                                $stage = '4ª SÉRIE';
                                break;
                            case '8':
                                $stage = '5ª SÉRIE';
                                break;
                            case '9':
                                $stage = '6ª SÉRIE';
                                break;
                            case '10':
                                $stage = '7ª SÉRIE';
                                break;
                            case '11':
                                $stage = '8ª SÉRIE';
                                break;
                            case '14':
                                $stage = '1º ANO';
                                break;
                            case '15':
                                $stage = '2º ANO';
                                break;
                            case '16':
                                $stage = '3º ANO';
                                break;
                            case '17':
                                $stage = '4º ANO';
                                break;
                            case '18':
                                $stage = '5º ANO';
                                break;
                            case '19':
                                $stage = '6º ANO';
                                break;
                            case '20':
                                $stage = '7º ANO';
                                break;
                            case '21':
                                $stage = '8º ANO';
                                break;
                            case '41':
                                $stage = '9º ANO';
                                break;
                            case '25':
                            case '30':
                            case '35':
                                $stage = '1ª SÉRIE';
                                break;
                            case '26':
                            case '31':
                            case '36':
                                $stage = '2ª SÉRIE';
                                break;
                            case '27':
                            case '32':
                            case '37':
                                $stage = '3ª SÉRIE';
                                break;
                            case '28':
                            case '33':
                            case '38':
                                $stage = '4ª SÉRIE';
                                break;
                        }
                        echo $stage;
                    ?>
                </td>
                <td colspan="1">TURMA: <?= $classroom->name?></td>
            </tr>
            <tr>
                <td>PROFESSOR(A): </td>
            </tr>
        </table>

        <br>

        <table class="table table-bordered table-striped" style="font-size: 11px">
            <tr>
                <th rowspan="2">Nº</th>
                <th rowspan="2">ALUNO</th>
                <th colspan="2">GÊNERO</th>
                <th rowspan="2">DATA DE NASCIMENTO</th>
                <th rowspan="2">NATURALIDADE</th>
                <th colspan="4">TIPO DE MATRÍCULA</th>
                <th colspan="3">SITUAÇÃO NA SÉRIE</th>
                <th rowspan="2">ENDEREÇO</th>
            </tr>
            <tr>
                <th>M</th>
                <th>F</th>
                <th>MI</th>
                <th>MC</th>
                <th>MR</th>
                <th>MT</th>
                <th>N</th>
                <th>P</th>
                <th>R</th>
            </tr>
            <?php
                $rows = "";
                foreach ($report as $key=>$r){
                    $rows .= "<tr>"
                            . "<td>" . ($key + 1) . "</td>"
                            . "<td>" . $r['name'] . "</td>"
                            . "<td>" . ($r['sex'] == 'M' ? 'X' : '') . "</td>"
                            . "<td>" . ($r['sex'] == 'F' ? 'X' : '') . "</td>"
                            . "<td>" . $r['birthday'] . "</td>"
                            . "<td>" . $r['city'] . "</td>"
                            . "<td>" . ($r['admission_type'] == '0' ? 'X' : '') . "</td>"
                            . "<td>" . ($r['admission_type'] == '1' ? 'X' : '') . "</td>"
                            . "<td>" . ($r['admission_type'] == '0' ? 'X' : '') . "</td>"
                            . "<td>" . (($r['admission_type'] == '2' || $r['admission_type'] == '3') ? 'X' : '') . "</td>"
                            . "<td>" . ($r['situation'] == '0' ? 'X' : '') . "</td>"
                            . "<td>" . ($r['situation'] == '1' ? 'X' : '') . "</td>"
                            . "<td>" . ($r['situation'] == '2' ? 'X' : '') . "</td>"
                            . "<td>" . $r['address'] . (strlen($r['number']) != 0 ? ", " . $r['number'] : '') . "</td>"
                            . "</tr>";
                }
                echo $rows;
            ?>
        </table>
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>

<style>
    @media print{
        @page {
            size: landscape;
        }
    }
</style>