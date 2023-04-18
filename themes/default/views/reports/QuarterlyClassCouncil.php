<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/QuartelyClassCouncil/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>

<div class="pageA4H" style="height: auto;">
    <?php $this->renderPartial('headBuzios'); ?>
    <h3><?php echo Yii::t('default', 'Quarterly Class Council Report'); ?> EDUCAÇÃO INFANTIL - TRIMESTRAL</h3>
    <p style="font-size: 19px;">Aos 13 dias do mês de Maio de 2023 às 8:30hs, realizou-se a reunião de Conselho de Classe referente ao 1º Trimestre,
        Creche - IV da Educação Infantil, turma RAPOSA, do turno Matutino, presidido por _________________________________________
        desta Unidade Escolar</p>

    <div class="container-box global-analysis">
        <p style="margin: 20px 0;">1 - Análise Global da turma:</p>
        <p>
            <span style="margin-right: 350px;">1.1 Frequência</span>
            <span>(<span style="color: white;">___</span>) Excelente</span>
            <span>(<span style="color: white;">___</span>) Boa</span>
            <span>(<span style="color: white;">___</span>) Regular</span>
            <span>(<span style="color: white;">___</span>) Ruim</span>
        </p>
        <p>
            <span style="margin-right: 334px;">1.2 Pontualidade</span>
            <span>(<span style="color: white;">___</span>) Excelente</span>
            <span>(<span style="color: white;">___</span>) Boa</span>
            <span>(<span style="color: white;">___</span>) Regular</span>
            <span>(<span style="color: white;">___</span>) Ruim</span>
        </p>
    </div>
    <div class="container-box line-box box-aspects cognitive-aspects">
        <p>1.3 Aspectos cognitivos (Conhecimento de mundo):</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
    </div>
    <div class="container-box line-box box-aspects socio-affective-aspects">
        <p>1.3 Aspectos socioafetivos (Formação pessoal e social):</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
    </div>
    <div class="container-box individual-aspects">
        <p>1.5 Aspectos individuais</p>
        <table class="students-list-table">
            <thead style="height: 150px;">
                <tr>
                    <th rowspan="2">Nº</th>
                    <th rowspan="2">NOME DO ALUNO</th>
                    <th class="vertical-head" rowspan="2">PCD</th>
                    <th class="vertical-head" rowspan="2">INFREQ.</th>
                    <th colspan="2">Área Cognitiva (AS)</th>
                    <th colspan="2">Área Socioafetiva (AS)</th>
                    <th rowspan="2">Observações</th>
                </tr>
                <tr>
                    <th><span class="vertical-head">Destaque</span></th>
                    <th><span class="vertical-head">Dificuldade</span></th>
                    <th><span class="vertical-head">Destaque</span></th>
                    <th><span class="vertical-head">Dificuldade</span></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count_std = 1;
                foreach ($classroom as $c) { ?>
                    <tr>
                        <td style="text-align: center;"><?= "0" . $count_std ?></td>
                        <td><?= $c['name'] ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: center;">
                            <?php
                            $create_date =  date('d-m-Y', strtotime($c['create_date']));
                            if ($c['status'] == 1) {
                                echo 'Matri. ' . $create_date;
                            } else if ($c['status'] == 2) {
                                echo 'Trans. ' . $create_date;
                            } else if ($c['status'] == 2) {
                                echo 'Cancel. ' . $create_date;
                            } else if ($c['status'] == 2) {
                                echo 'Evad. ' . $create_date;
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                    $count_std++;
                }
                ?>
            </tbody>
        </table>
        <p style="margin-top: 10px;">Legenda: PCD - Pessoa com Defeciência. CAAPE - Centro de Atendimento e Apoio Pedagógico ao Educando. </p>
        <div class="container-box line-box individual-observations">
            <p>1. Observações individuais</p>
            <p>_________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
        </div>
        <div class="container-box line-box difficulties-found">
            <p>2. Dificuldades encontradas para o desenvolvimento das atividades pedagógicas:</p>
            <p>_________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
        </div>
        <div class="container-box line-box developed-actions">
            <p>3. Ações desenvolvidas para minimizar as dificuldades encontradas (professores, equipe pedagógica e diretiva):</p>
            <p>_________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
        </div>
        <div class="container-box line-box final-deliberations">
            <p>4. Deliberações Finais (ações e/ou propostas para o próximo Trimestre):</p>
            <p>_________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
        </div>
        <div class="container-box">
            <p>Nada mais havendo a declarar, lavramos a presente Ata</p>
        </div>
        <table class="instructors-list-table">
            <thead>
                <tr>
                    <th>Disciplina</th>
                    <th>Nome do Professor</th>
                    <th>Assinatura</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($classroom as $c) {
                    // @todo fazer a tabela de professores
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .global-analysis p:not(p:first-child) {
        margin: 10px 0;
    }

    .global-analysis p span:not(span:first-child) {
        margin-inline: 30px;
    }

    .line-box p:not(p:first-child) {
        margin: 10px 0;
    }

    .container-box {
        font-size: 14px;
        margin: 40px 0;
    }

    .vertical-head {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
    }

    table th {
        text-align: center !important;
        vertical-align: inherit !important;
    }

    .students-list-table {
        width: 100%;
        margin-top: 10px;
    }

    table th,
    table td {
        border: 1px solid #C0C0C0;
    }
</style>