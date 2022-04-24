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




<div>
    <div>
        <table class="table" style="font-size:16px">
            <!--<tr>
                <td colspan="3">LOCAL REALIZAÇÃO: ESCOLA ESTADUAL COMENDADOR CALAZANS</td>
                <td>CÓDIGO: <?= $classroom->school_inep_fk?></td>
            </tr>-->
            <!--<tr>
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
                    ?> - 08:00h às 11:00h
                </td>
                <td colspan="1">CARGA HORÁRIA: 3h
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
                <td colspan="1">CURSO/TURMA: <?= $classroom->name?></td>
            </tr>-->
            <tr>
                <td>CURSO/TURMA: <?= $classroom->name?></td>
                <td>HORÁRIO: <?= $classroom->initial_hour?>:00 as <?= $classroom->final_hour?>:00</td>
            </tr>
            <tr>
                <td>INSTRUTOR(A): </td>
                <br>
                <td>DATA: </td>
            </tr>
            <tr>
                
            </tr>
        </table>
    </div>
    <div>
        <br/>
        <table class="table table-bordered table-striped" style="font-size: 11px">
            <tr>
                <th rowspan="" style="text-align: center;">Nº</th>
                <th rowspan="">ALUNO</th>
                <th rowspan="" style="text-align: center; width:50px">DATA DE NASCIMENTO</th>
                <th rowspan="" style="text-align: center;">TELEFONE</th>
                <th rowspan="" style="text-align: center;">NOME RESPONSÁVEL</th>
                <th rowspan="" style="text-align: center;">ENDEREÇO</th>
                <th rowspan="" style="text-align: center;">ASSINATURA</th>
                <?php
                  /*  $days = array(9,11,16,18,23,25,30);
                    foreach ($days as $key => $value) {
                        echo '<td>'.$value.'</td>';
                    }*/
                ?>
            </tr>
            <?php
                $rows = "";
                
                foreach ($report as $key=>$r){
                    $rows .= "<tr>"
                            . "<td style='text-align: center;'>" . ($key + 1) . "</td>"
                            . "<td style='width:230px'> " . $r['name'] . "</td>"
                            . "<td style='text-align: center;'>" . $r['birthday'] . "</td>"
                            . "<td style='text-align: center;'>" . $r['phone'] . "</td>"
                            . "<td style='text-align: center;'>" . $r['mother'] . "</td>"
                            . "<td style='text-align: center;'>" . $r['neighborhood'] . "</td>"
                            . "<td style='text-align: center; width:300px'>
                            
                            </td>";
                            $rows .= "</tr>";
                }
                echo $rows;
            ?>
        </table>
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>

<style>
    .table-bordered th, .table-bordered td, .table-bordered {border-color: #6d6c6c !important; border-collapse: collapse;}

    @media print{
        @page {
            size: landscape;
        }
        table { page-break-inside:auto;}
        tr    { page-break-inside:avoid; page-break-after:auto;}
        td    { page-break-inside:avoid; page-break-after:auto;}
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }
</style>
