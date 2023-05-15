<?php
/* @var $this ReportsController */
/* @var $report mixed */
/* @var $classroom Classroom*/
/* @var $students StudentEnrollment[]*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/AtaSchoolPerformance/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$stage = EdcensoStageVsModality::model()->findByPk($classroom->edcenso_stage_vs_modality_fk)->name;

    function valorPorExtenso( $valor = 0){
        $old = $valor;
        $valor = intval($valor);
        $pre = "Ao";
        $texto = array("NaN", "primeiro", "segundo", "terceiro", "quarto", "quinto", "sexto","sétimo", "oitavo", "nono",
            "décimo", "décimo primeiro", "décimo segundo", "décimo terceiro", "décimo quarto", "décimo quinto", "décimo sexto", "décimo sétimo", "décimo oitavo", "décimo nono",
            "vigésimo", "vigésimo primeiro", "vigésimo segundo", "vigésimo terceiro", "vigésimo quarto", "vigésimo quinto", "vigésimo sexto", "vigésimo sétimo", "vigésimo oitavo", "vigésimo nono",
            "trigésimo", "trigésimo primeiro");
        $pos = "dia ($old)";
        return($pre." <b>".$texto[$valor]." ".$pos."</b>");

    }

    // Tratamento do cabeçalho das
    function classroomDisciplineLabelResumeArray($discipline_id)
    {
        $label = '';
        if ($discipline_id == 1)  $label = "Química";
        if ($discipline_id == 2)  $label = "Física";
        if ($discipline_id == 3)  $label = "Matemática";
        if ($discipline_id == 4)  $label = "Biologia";
        if ($discipline_id == 5)  $label = "Ciências";
        if ($discipline_id == 6)  $label = "Português";
        if ($discipline_id == 7)  $label = "Inglês";
        if ($discipline_id == 8)  $label = "Espanhol";
        if ($discipline_id == 9)  $label = "Outro Idioma";
        if ($discipline_id == 10)  $label = "Artes";
        if ($discipline_id == 11)  $label = "Edicação Física";
        if ($discipline_id == 12)  $label = "História";
        if ($discipline_id == 13)  $label = "Geografia";
        if ($discipline_id == 14)  $label = "Filosofia";
        if ($discipline_id == 16)  $label = "Informática";
        if ($discipline_id == 17)  $label = "Disc. Profissionalizante";
        if ($discipline_id == 20)  $label = "Educação Especial";
        if ($discipline_id == 21)  $label = "Sociedade&nbspe Cultura";
        if ($discipline_id == 23)  $label = "Libras";
        if ($discipline_id == 25)  $label = "Pedogogia";
        if ($discipline_id == 26)  $label = "Ensino Religioso";
        if ($discipline_id == 27)  $label = "Língua Nativa";
        if ($discipline_id == 28)  $label = "Estudo Social";
        if ($discipline_id == 29)  $label = "Sociologia";
        if ($discipline_id == 30)  $label = "Francês";
        if ($discipline_id == 99)  $label = "Outras";
        if ($discipline_id == 10001)  $label = "Redação";
        if ($discipline_id == 10002)  $label = "Linguagem oral e escrita";
        if ($discipline_id == 10003)  $label = "Natureza e sociedade";
        if ($discipline_id == 10004)  $label = "Movimento";
        if ($discipline_id == 10005)  $label = "Música";
        if ($discipline_id == 10006)  $label = "Artes visuais";
        if ($discipline_id == 10007)  $label = "Escuta, Fala, Pensamento e Imaginação";
        if ($discipline_id == 10008)  $label = "Espaço, Tempo, Quantidade, Relações e Transformações";
        if ($discipline_id == 10009)  $label = "Corpo, Gesto e Movimento";
        if ($discipline_id == 10010)  $label = "Traços, Sons, Cores e Formas";
        if ($discipline_id == 10011)  $label = "O Eu, O Outro e o Nós";
        if ($discipline_id == 10012)  $label = "Manifestações Culturais e Artísticas Globais e Regionais";
        if ($discipline_id == 10013)  $label = "Gestão Sustentável de Detinos Turísticos";
        if ($discipline_id == 10014)  $label = "Lazer, Esporte e Trabalho";
        if ($discipline_id == 10015)  $label = "Inglês Instrumental";
        if ($discipline_id == 10016)  $label = "Espanhol Instrumental";
        if ($discipline_id == 10017)  $label = "Georgrafia Turística";
        if ($discipline_id == 10018)  $label = "Desafios contemporâneos: Do Global ao Local";
        if ($discipline_id == 10019)  $label = "História Regional";
        if ($discipline_id == 10020)  $label = "Sociedade Buziana";
        if ($discipline_id == 10021)  $label = "Antropologia Sociocultural";
        if ($discipline_id == 10022)  $label = "Patrimônios Culturais";
        if ($discipline_id == 10023)  $label = "Literatura na Era Digital";
        if ($discipline_id == 10024)  $label = "Educação Financeira";
        if ($discipline_id == 10025)  $label = "Expressão Oral e Escrita";
        if ($discipline_id == 10026)  $label = "Projeto de Vida II";
        if ($discipline_id == 10027)  $label = "Língua Espanhola";
        if ($discipline_id == 10028)  $label = "Análise e Experimentação científica";
        if ($discipline_id == 10029)  $label = "Consciência Ecológica e Educação Ambiental";
        if ($discipline_id == 10030)  $label = "Ciências, Tecnologia e Sociedade";
        if ($discipline_id == 10031)  $label = "Pensamento Lógico-Matemático";
        if ($discipline_id == 10032)  $label = "Cálculo";
        if ($discipline_id == 10033)  $label = "Sustentabilidade";
        if ($discipline_id == 10034)  $label = "Eficiência Energética";
        if ($discipline_id == 10035)  $label = "Corpo e Movimento";
        if ($discipline_id == 10036)  $label = "Geometria";
        if ($discipline_id == 10037)  $label = "Corpo e Saúde";
        if ($discipline_id == 10038)  $label = "Ciências Biológicas, Agrárias e da Saúde";
        if ($discipline_id == 10039)  $label = "Projeto de vida I";
        if ($discipline_id == 10040)  $label = "Projeto de Leitura";
        if ($discipline_id == 10041)  $label = "Pluralidade Cultural";
        if ($discipline_id == 10042)  $label = "Estudo Dirigido: Atividade de Lingua Portuguesa e de Matmática";
        if ($discipline_id == 10043)  $label = "Atividades Esportivas e Motoras";
        
        return $label;
    }

$actual_day = valorPorExtenso(date('d'));
$actual_mounth = date('M');
$actual_year = date('Y');

$turno =  $classroom->turn;
if ($turno == 'M') {
    $turno = "Matutino";
}else if ($turno == 'T') {
    $turno = "Tarde";
}else if ($turno == 'N') {
    $turno = "Noite";
}else if ($turno == '' || $turno == null) {
    $turno = "______________________";
}
?>

<style>
    hr.hrline{
        margin: 1px;
        border-top-color: black;
    }
    div.hrline{
        margin-right:20px; 
        float:left; 
        display:block; 
        width:30%;
    }
    div.hrline, div.hrlastline{
        text-align: center; 
    }
    div.divlines{
        width:100%; margin:0 auto;
    }
    div.hrline span{
        font-size: 10px;
    }    
    th{
        vertical-align: middle !IMPORTANT;
        text-align: center !IMPORTANT;
    }
    th.vertical-text {
        height: 100px;
        vertical-align: bottom !IMPORTANT;
        font-size: 11px;
        width: 50px;
      }

    th.vertical-text > div {
        transform: translate(5px, 0px) rotate(270deg);
        width: 10px;
        line-height: 12px;
        font-size: 11px;
        margin: 0 auto;
        position: relative;
        left: -6px;
    }
    .text-center{
        text-align: center;
    }
    
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

<?php ?>


<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Ata School Performance of ').$this->year; ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div>
        <?php $this->renderPartial('head'); ?>
        
        <br><br>
        <div class="divlines">
            <div class="hrline">
                <hr class="hrline">
                <span>ATO DE CRIAÇÃO</span>
            </div>
            <div class="hrline">
                <hr class="hrline">
                <span>ATO DE AUTORIZAÇÃO DE FUNCIONAMENTO</span>
            </div>
            <div class="hrlastline">
                <hr class="hrline">
                <span>ATO DE RECONHECIMENTO</span>
            </div>
        </div>
        <br>
        <br>
        <p style="text-align: justify;"><?php echo $actual_day?> do mês de <b><?php echo Yii::t('default', $actual_mounth) ?></b> do ano de <b><?php echo $actual_year?></b>. 
            Realizou-se o processo de apuração do rendimento escolar dos alunos da série de <b><?php echo $report["serie"]?></b>, turma <b><?php echo $classroom->name?></b>,
            turno <b><?php echo $turno?></b> do <b><?php echo $classroom->edcensoStageVsModalityFk->name?></b> deste estabelecimento, com a Carga Horária anual de _________ horas 
        e um total de _________ dias letivos, conforme os resultados abaixo.</p>
        
        <?php 
            //$disciplines = explode('|',$report['disciplines']); 
            $qtde = count($disciplines);
        ?>
        <br>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col" rowspan="4" class='vertical-text'><div>Ordem</div></th>
                    <th scope="col" rowspan="4" class='vertical-text'><div>ID INEP</div></th>
                    <th scope="col" rowspan="4" style="width: 300px;">Nome do Aluno</th>
                    <th scope="col" colspan="25">Componentes Curriculares</th></tr>
                <tr>
                    <th scope="col" colspan="25">Rendimento Escolar</th>
                </tr>
                <tr>
                    <th scope="col" colspan="<?php echo $qtde?>">Disciplinas</th>
                    <th scope="col" colspan="3">Resultado Final</th>
                    <th scope="col" colspan="2">Dependência</th>
                </tr>
                <tr>
                    <?php foreach($disciplines as $discipline) {
                        $discipline = classroomDisciplineLabelResumeArray($discipline['discipline_id']);
                        if (strlen($discipline) <= 20) {
                            echo "<th scope='col' class='vertical-text'><div>".$discipline."</div></th>";
                        }else {
                            echo "<th scope='col' class='vertical-text'>".$discipline."</th>";
                        }
                    }
                    ?>
                    <th scope="col" class='vertical-text'><div>Aprovado</div></th>
                    <th scope="col" class='vertical-text'><div>Prom.&nbsp;com Dependência</div></th>
                    <th scope="col" class='vertical-text'><div>Reprovado</div></th>
                    <th scope="col" class='vertical-text'><div>Disciplina</div></th>
                    <th scope="col" class='vertical-text'><div>Nota</div></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach($students as $s) {
                    if ($count < 10) {
                        $label_id = "0".$count;
                    }else {
                        $label_id = $count;
                    }
                    echo "<tr>"
                    ."<td>".$label_id."</td>"
                    ."<td>".$s->studentFk->inep_id."</td>"
                    ."<td>".$s->studentFk->name."</td>";
                    foreach ($grades as $grade) {
                        if($grade['student_id'] == $s->studentFk->id) {
                            echo "<td>".$grade['final_media']."</td>";
                        }
                    }
                    echo "<td></td>".
                    "<td></td>".
                    "<td></td>".
                    "<td></td>".
                    "<td></td>";
                    echo "</tr>";
                    $count++;
                }
                ?>
            </tbody>
        </table>        
        <br>
        <div class="divlines">
            <div class="hrline" style="width:100%;text-align:left;">
                Obs.:
                <hr class="hrline">
                <br>
                <hr class="hrline">
                <br>
            </div>
        </div>
        <p style="text-align: justify;">
            E, para contar, eu ___________________________________________________________________ lavrei a presente ata que vai por mim 
            assinada e pelo(a) do estabelecimento.
        </p>
        <p style="text-align: right;">
            __________________,____ de ______________________ de ________
        </p>
        <br>
        <div class="divlines">
            <div class="hrline" style="width:45%;">
                <hr class="hrline">
                <span>SECRETÁRIO(A) Nº ATO AUTORIZAÇÃO</span>
            </div>
            <div class="hrlastline">
                <hr class="hrline">
                <span>DIRETOR(A) Nº ATO AUTORIZAÇÃO</span>
            </div>
        </div>
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>