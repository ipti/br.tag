<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/QuartelyClassCouncil/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>

<div class="pageA4H page" style="height: auto;">
    <div class="cabecalho" style="margin: 30px 0;">
        <?php $this->renderPartial('buzios/headers/headBuziosII'); ?>
    </div>
    <h3><?php echo "Acompanhamento Avaliativo dos Alunos em ".$discipline->name." ".
        Yii::app()->user->year."<br> Ciclo de Alfabetização - ".$anosTitulo.".<br>" ?>
        <?php if ($anosVerify == 1) {?>
                <span>(<?php echo $anosPosition == 1 ? "&nbspX&nbsp" : "&nbsp&nbsp&nbsp&nbsp"?>)&nbsp1º</span>
                <span>(<?php echo $anosPosition == 2 ? "&nbspX&nbsp" : "&nbsp&nbsp&nbsp&nbsp"?>)&nbsp2º</span>
                <span>(<?php echo $anosPosition == 3 ? "&nbspX&nbsp" : "&nbsp&nbsp&nbsp&nbsp"?>)&nbsp3º</span>
            <?php }else {?>
                <span>(<?php echo $anosPosition == 4 ? "&nbspX&nbsp" : "&nbsp&nbsp&nbsp&nbsp"?>)&nbsp4º</span>
                <span>(<?php echo $anosPosition == 5 ? "&nbspX&nbsp" : "&nbsp&nbsp&nbsp&nbsp"?>)&nbsp5º</span>
            <?php }?>
    </h3>
    <div class="title-container">
        <p><?php echo "Professor: ".$instructor[0]['instructor_name']?></p>
        <span><?php echo "Turma: ".$classroom->name?></span>
        <span><?php echo "Total de alunos: ".count($students)?></span>
        <span><?php echo "Trimestre: ".$quarterly?></span>
    </div>
    <div class="students-list-container">
        <table aria-labelledby="Students List">
            <thead>
                <tr>
                    <th scope="col" rowspan="2">Nº</th>
                    <th scope="col" rowspan="2" style="width: 300px;">Alunos</th>
                    <th scope="col" class="vertical-head" colspan="3">1. Apresenta evolução motora<br> adequada a idade/ano?</th>
                    <th scope="col" class="vertical-head" colspan="3">2. Percebe comandos e os<br> contextualiza para executar as<br> atividades propostas corretamente?</th>
                    <th scope="col" class="vertical-head" colspan="3">3. Possui coordenação motora extensa<br> sendo capaz de dominá-la para<br> executar tarefas?</th>
                    <th scope="col" class="vertical-head" colspan="3">4. Reproduz, contextualiza e cria as<br> atividades propostas com domínio?</th>
                    <th scope="col" class="vertical-head" colspan="3">5. Domina e manipula as várias ferramentas e<br> suportes usados na arte para a produção<br> plástica nos diferentes eixos?</th>
                    <th scope="col" class="vertical-head" colspan="3">6. Domina leitura visual e verbal<br> relativos à idade/ano?</th>
                    <th scope="col" class="vertical-head" colspan="3">7. Possui escuta adequada e atenta nas<br> atividades propostas?</th>
                    <th scope="col" class="vertical-head" colspan="3">8. Convive em grupo de forma<br> harmônica e participativa?</th>
                    <th scope="col" rowspan="2">* Observações individuais significativas para auxiliar no preenchimento do quadro descritivo:</th>
                </tr>
                <tr>
                    <?php for ($i=0; $i < 8; $i++) { 
                        echo "<th scope='col'>S</th>
                        <th scope='col'>P</th>
                        <th scope='col'>N</th>";
                    }?> 
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 1;
                foreach ($students as $student) { ?>
                    <tr>
                        <td><?= $count < 10 ? "0".$count : $count?></td>
                        <td><?= $student['student_name']?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php
                $count++;
                }?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .title-container {
        width: 100%;
        font-size: 16px;
    }
    .title-container p {
        margin-bottom: 20px;
    }
    .title-container span {
        margin-right: 200px;
    }
    .vertical-head {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        font-size: 10px !important;
    }
    .students-list-container {
        margin-top: 30px;
    }

    table th {
        text-align: center !important;
        vertical-align: inherit !important;
    }

    table {
        width: 100%;
        margin-top: 10px;
        page-break-inside: auto;
    }

    table th,
    table td {
        border: 2px solid #C0C0C0;
        padding: 10px;
    }
</style>