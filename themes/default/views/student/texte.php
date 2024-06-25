Observe esse código abaixo:
<table style="margin: 0 0 0 25px; font-size: 8px; width: calc(100% - 51px);"
                   class="table table-bordered report-table-empty">
                <thead>
                    <tr>
    <td colspan="<?= $diciplinesColumnsCount ?>" style="text-align: center">VIDA ESCOLAR</td>
    <td colspan="4">&nbsp;</td>
    <td colspan="2" style="text-align: center">NOME DO ESTABELECIMENTO</td>
</tr>
                    <tr>
                        <td style="text-align: center; min-width: 90px !important;">PARTESDO CURRÍCULO</td>
                        <?php if (count($baseDisciplines) > 0) {?>
                        <td colspan="<?= count($baseDisciplines) ?>" style="text-align: center; font-weight: bold; min-width:150px;">BASE
                            NACIONAL
                            COMUM
                        </td>
                        <?php } ?>
                        <?php if (count($diversifiedDisciplines) > 0) {?>
                        <td colspan="<?= count($diversifiedDisciplines) ?>" style="text-align: center; font-weight: bold; min-width:100px;">PARTE
                            DIVERSIFICADA
                        </td>
                        <?php } ?>
                        <td rowspan="2" class="vertical-text">
                            <div>DIAS&nbsp;LETIVOS</div>
                        </td>
                        <td rowspan="2" class="vertical-text">
                            <div>CARGA&nbsp;HORÁRIA</div>
                        </td>
                        <td rowspan="2" class="vertical-text">
                            <div>Nº&nbsp;DE&nbsp;FALTAS</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="vertical-text">
                            <?php if(TagUtils::isInstance("BUZIOS")): ?>
                                <div>TRIMESTRES</div>
                            <?php else: ?>
                                <div>UNIDADES</div>
                            <?php endif; ?>
                        </td>
                        <?php foreach ($baseDisciplines as $name): ?>
                            <td class="vertical-text">
                                <div><?= classroomDisciplineLabelResumeArray($name) ?></div>
                            </td>
                        <?php endforeach; ?>
                        <?php foreach ($diversifiedDisciplines as $name): ?>
                            <td class="vertical-text">
                                <div><?= classroomDisciplineLabelResumeArray($name) ?></div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conceptUnities = false;
                    for($i=1;$i<=count($unities);$i++) {?>
                        <tr>
                            <td><?= strtoupper($unities[$i-1]->name) ?></td>
                            <?php
                            $gradeResultFaults = 0;
                            if ($unities[$i-1]->type == 'UC') {
                                $conceptUnities = true;

                            }
                            for($j=0; $j < $diciplinesColumnsCount; $j++) {
                                $gradeResultFaults += $result[$j]['grade_result']['grade_faults_'.$i];
                                ?>
                                <?php if ($unities[$i-1]->type == 'RF') { ?>
                                    <td style="text-align: center;"><?= $result[$j]['grade_result']['rec_final'] ?></td>
                                <?php } else if ($unities[$i-1]->type == 'UC') { ?>
                                    <td style="text-align: center;"><?= $result[$j]['grade_result']['grade_concept_'.$i] ?></td>
                                <?php } else if ($result[$j]['grade_result']['grade_'.$i] < $result[$j]['grade_result']['rec_bim_'.$i]) { ?>
                                    <td style="text-align: center;"><?= $result[$j]['grade_result']['rec_bim_'.$i] ?></td>
                                <?php } else { ?>
                                    <td style="text-align: center;"><?= $result[$j]['grade_result']['grade_'.$i] ?></td>
                                <?php } ?>
                            <?php }?>
                            <?php if ($unities[$i-1]->type != 'RF') { ?>
                                <td style="text-align: center;"><?= $school_days[$i-1]?></td>
                                <td style="text-align: center;"><?= $workload[$i-1]?></td>
                                <td style="text-align: center;"><?= $gradeResultFaults == 0 ? $faults[$i-1] : $gradeResultFaults ?></td>
                            <?php } else { ?>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                            <?php } ?>
                        </tr>
                    <?php }?>
                </tbody>

                <!-- <tr>
                    <td colspan="1">MÉDIA ANUAL</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['final_media']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->
                <!-- <tr>
                    <td colspan="1">NOTA DA PROVA FINAL</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;"><?= end($result[$i]['grades'])->grade?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->
                <tr>
                    <td colspan="1">MÉDIA FINAL</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;font-weight:bold;"><?= ($conceptUnities ? '' : $result[$i]['final_media']) ?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">TOTAL DE AULAS DADAS</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['total_number_of_classes']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">TOTAL DE FALTAS</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['total_faults']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">FREQUÊNCIAS %</td>
                    <?php for ($i=0; $i < $diciplinesColumnsCount; $i++) {?>
                        <td style="text-align: center;"><?= is_nan($result[$i]['frequency_percentage']) || $result[$i]['frequency_percentage'] < 0 ? "" : ceil($result[$i]['frequency_percentage']) . "%" ?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
        
    </table>

    Como modificar o código:<tr>
                        <td colspan="<?= $diciplinesColumnsCount+4 ?>" style="text-align: center">FICHA DE NOTAS</td>
                    </tr>


    para deixar a tabela assim:

| vida escolar|_____|_____|___________________________________________________________________________________|_____________________________________|________________________________________________________|____________|__________|nome do estabelecimento|




Como fazer para adicionar uma tabela com as seguintes configurações, observe que existe uma separa~]ao entre linhas ___ e entre colunas |

| vida escolar|_____|_____|___________________________________________________________________________________|_____________________________________|________________________________________________________|____________|__________|nome do estabelecimento|
|             |idade|série|Língua Portuguesa| matemática|ciências| história | Geografia|LÍNGUA ESTRANGEIRA    | arte | filosofia |ÉTICA E CIDADANIA | ENSINO RELIGIOSO | EDUCAÇÃO FÍSICA |   |   |   |   |   |Média Anual |Ano       |                       |
|             |_____|_____|_________________|___________|________|__________|__________|______________________|______|___________|__________________|__________________|_________________|___|___|___|___|___|____________|__________|_______________________|
|             |_____|_____|_________________|___________|________|__________|__________|______________________|______|___________|__________________|__________________|_________________|___|___|___|___|___|____________|__________|_______________________|
|             |_____|_____|_________________|___________|________|__________|__________|______________________|______|___________|__________________|__________________|_________________|___|___|___|___|___|____________|__________|_______________________|
|             |_____|_____|_________________|___________|________|__________|__________|______________________|______|___________|__________________|__________________|_________________|___|___|___|___|___|____________|__________|_______________________|
|             |_____|_____|_________________|___________|________|__________|__________|______________________|______|___________|__________________|__________________|_________________|___|___|___|___|___|____________|__________|_______________________|
|             |_____|_____|_________________|___________|________|__________|__________|______________________|______|___________|__________________|__________________|_________________|___|___|___|___|___|____________|__________|_______________________|
|             |_____|_____|_________________|___________|________|__________|__________|______________________|______|___________|__________________|__________________|_________________|___|___|___|___|___|____________|__________|_______________________|
|_____________|_____|_____|_________________|___________|________|__________|__________|______________________|______|___________|__________________|__________________|_________________|___|___|___|___|___|____________|__________|_______________________|
|_____________|______________________________________________________________________________________________________________________________________________________________________________________________________________________|_______________________|
|_____________|______________________________________________________________________________________________________________________________________________________________________________________________________________________|  Autentificação       |
|_____________|______________________________________________________________________________________________________________________________________________________________________________________________________________________|                       |
|_____________|______________________________________________________________________________________________________________________________________________________________________________________________________________________|                       |
|_____________|______________________________________________________________________________________________________________________________________________________________________________________________________________________|                       |
|_____________|______________________________________________________________________________________________________________________________________________________________________________________________________________________|_______________________|

Colocar<div class="container-school-record" style="page-break-before: always;">
    <p>-----------------------------------------------hello word---------------------------------------------</p>
</div>