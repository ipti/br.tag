<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/QuartelyClassCouncil/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$tableWhiteFieldsComplete = "<td></td><td></td><td></td><td></td><td></td>
                            <td></td><td></td><td></td><td></td><td></td>
                            <td></td><td></td><td></td><td></td><td></td>
                            <td></td><td></td><td></td><td></td><td></td>
                            <td></td><td></td><td></td><td></td><td></td>"
?>

<div class="pageA4H page" style="height: auto;">
    <div class="cabecalho" style="margin: 30px 0;">
        <?php $this->renderPartial('buzios/headers/headBuziosII'); ?>
    </div>
    <h3><?php echo "Acompanhamento Avaliativo dos Alunos em ".$discipline->name." ".
        Yii::app()->user->year."<br> Ciclo de Alfabetização - ".$anosTitulo.".<br>" ?>
        <?php if ($anosVerify == 1) {?>
                <span style="color: black">(<?php echo $anosPosition == 1 ? "&nbspX&nbsp" : "&nbsp&nbsp&nbsp&nbsp"?>)&nbsp1º</span>
                <span style="color: black">(<?php echo $anosPosition == 2 ? "&nbspX&nbsp" : "&nbsp&nbsp&nbsp&nbsp"?>)&nbsp2º</span>
                <span style="color: black">(<?php echo $anosPosition == 3 ? "&nbspX&nbsp" : "&nbsp&nbsp&nbsp&nbsp"?>)&nbsp3º</span>
            <?php }else {?>
                <span style="color: black">(<?php echo $anosPosition == 4 ? "&nbspX&nbsp" : "&nbsp&nbsp&nbsp&nbsp"?>)&nbsp4º</span>
                <span style="color: black">(<?php echo $anosPosition == 5 ? "&nbspX&nbsp" : "&nbsp&nbsp&nbsp&nbsp"?>)&nbsp5º</span>
            <?php }?>
    </h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <div class="title-container">
        <p><?php echo "Professor(a): ".$instructor[0]['instructor_name']?></p>
        <span><?php echo "Turma: ".$classroom->name?></span>
        <span><?php echo "Total de alunos: ".count($students)?></span>
        <span><?php echo "Trimestre: ".$quarterly?></span>
    </div>
    <div class="students-list-container">
        <table aria-labelledby="Students List">
            <thead>
                <tr>
                    <th scope="col" rowspan="2">Nº</th>
                    <th scope="col" rowspan="2" style="width: 300px;font-size:20px;">Alunos</th>
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
                        <?= $tableWhiteFieldsComplete?>
                    </tr>
                <?php
                $count++;
                }?>
                <td></td>
                <td style="font-weight: bold;font-size: 14px;"><?php echo "TOTAL: ".count($students)?></td>
                <?= $tableWhiteFieldsComplete?>
            </tbody>
        </table>
        <div class="subtitle-container">
            <p>Legenda: S - Sim / P - Parcialmente / N - Não</p>
            <p>Observações significativas: Características principal da criança.</p>
        </div>
    </div>
    <div class="comments-container">
        <h4 style="color: black;">Observações descritas a respeito do trabalho executado no trimestre:</h4>
        <div class="line-container">
            <div class="title-comments">1.&nbsp&nbsp&nbsp Objetivos da aprendizagem para este trimestre:</div>
            <div class="subtitle-comments">(Conteúdos dados)</div>
            <div class="content-comments">
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            </div>
        </div>

        <div class="line-container">
            <div class="title-comments">2.&nbsp&nbsp&nbsp Atividades aplicadas neste processo:</div>
            <div class="subtitle-comments">(Práticas usadas)</div>
            <div class="content-comments">
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            </div>
        </div>

        <div class="line-container">
            <div class="title-comments">3.&nbsp&nbsp&nbsp Observações do desenvolvimento do trabalho junto à turma:</div>
            <div class="content-comments">
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            </div>
        </div>

        <div class="line-container">
            <div class="title-comments">3.&nbsp&nbsp&nbsp Observações individuais significativas:</div>
            <div class="subtitle-comments">(alunos PNE, especificidades, intervenções e/ou acompanhamento)</div>
            <div class="content-comments">
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_______________________________________________________________________________________________________________________________________________________________________________________________________________________________________
            </p>
            </div>
        </div> 
    </div>
    <p style="font-size: 14px;">
        *Segundo os artigos 82, 112, e 114, parágrafo 3º, o processo de avaliação das disciplinas 
        de Arte e Cultura e Educação Física será através de um relatório
        trimestral da turma, elaborado pelo professor com observações individuais, 
        em formulário específico enviado pela Secretaria Municipal de Educação, Esporte, Ciência e Tecnologia.
    </p>
    <div class="container-box signatures-container">
         <p>
            <span>_______________________________________</span>
            <span>_______________________________________</span>
            <span>_______________________________________</span>
            <span>_______________________________________</span>
        </p>
        <p>
            <span>Professor(a)</span>
            <span>Professor(a) Supervisor(a) Escolar</span>
            <span>Professor(a) Orientador(a) Educacional</span>
            <span>Professor(a) Inspetor(a) Escolar</span>
        </p>
        <p style="margin-top: 50px;">
            <span>_______________________________________</span>
        </p>
        <p>
            <span>Diretor(a)</span>
        </p>
    </div>
</div>

<style>
    @media print {
        .hidden-print {
            display: none;
        }
        @page {
            size: portrait;
        }
    }
    .signatures-container {
        margin-top: 80px !important;
    }

    .signatures-container p {
        margin: 10px 0px;
        width: 100%;
        align-items: center;
        display: flex;
    }

    .signatures-container p span {
        width: 100%;
        align-items: center;
        display: flex;
        justify-content: center;
        font-size: 12px;
    }
    .page {
        width: 1050px;
    }
    .comments-container {
        width: 100%;
        margin-top: 20px;
    }
    .line-container {
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .line-container .title-comments {
        font-size: 14px;
        font-weight: bold;
    }
    .line-container .subtitle-comments {
        font-size: 10px;
        margin-left: 20px;
    }
    .line-container .content-comments {
        margin-top: 10px;
    }
    .line-container .content-comments p {
        margin-bottom: 10px;
    }
    .title-container {
        width: 100%;
        font-size: 16px;
    }
    .title-container p {
        margin-bottom: 20px;
    }
    .title-container span {
        margin-right: 100px;
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

    .subtitle-container {
        margin-top: 20px;
    }
    .subtitle-container p {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>

<script>
    function imprimirPagina() {
      window.print();
    }
</script>