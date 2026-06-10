<?php
/* @var $this ReportsController */
/* @var $report mixed */
/* @var $classroom Classroom*/
/* @var $students StudentEnrollment[]*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/AtaSchoolPerformance/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);

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
        return $pre." <b>".$texto[$valor]." ".$pos."</b>";

    }

    // Tratamento do cabeçalho das
    function classroomDisciplineLabelResumeArray($disciplineId)
    {
        static $disciplines = [
            1     => "Química",
            2     => "Física",
            3     => "Matemática",
            4     => "Biologia",
            5     => "Ciências",
            6     => "Português",
            7     => "Inglês",
            8     => "Espanhol",
            9     => "Outro Idioma",
            10    => "Artes",
            11    => "Educação Física",
            12    => "História",
            13    => "Geografia",
            14    => "Filosofia",
            16    => "Informática",
            17    => "Disc. Profissionalizante",
            20    => "Educação Especial",
            21    => "Sociedade&nbspe Cultura",
            23    => "Libras",
            25    => "Pedagogia",
            26    => "Ensino Religioso",
            27    => "Língua Nativa",
            28    => "Estudo Social",
            29    => "Sociologia",
            30    => "Francês",
            99    => "Outras",
            10001 => "Redação",
            10002 => "Linguagem oral e escrita",
            10003 => "Natureza e sociedade",
            10004 => "Movimento",
            10005 => "Música",
            10006 => "Artes visuais",
            10007 => "Escuta, Fala, Pensamento e Imaginação",
            10008 => "Espaço, Tempo, Quantidade, Relações e Transformações",
            10009 => "Corpo, Gesto e Movimento",
            10010 => "Traços, Sons, Cores e Formas",
            10011 => "O Eu, O Outro e o Nós",
            10012 => "Manifestações Culturais e Artísticas Globais e Regionais",
            10013 => "Gestão Sustentável de Detinos Turísticos",
            10014 => "Lazer, Esporte e Trabalho",
            10015 => "Inglês Instrumental",
            10016 => "Espanhol Instrumental",
            10017 => "Georgrafia Turística",
            10018 => "Desafios contemporâneos: Do Global ao Local",
            10019 => "História Regional",
            10020 => "Sociedade Buziana",
            10021 => "Antropologia Sociocultural",
            10022 => "Patrimônios Culturais",
            10023 => "Literatura na Era Digital",
            10024 => "Educação Financeira",
            10025 => "Expressão Oral e Escrita",
            10026 => "Projeto de Vida II",
            10027 => "Língua Espanhola",
            10028 => "Análise e Experimentação científica",
            10029 => "Consciência Ecológica e Educação Ambiental",
            10030 => "Ciências, Tecnologia e Sociedade",
            10031 => "Pensamento Lógico-Matemático",
            10032 => "Cálculo",
            10033 => "Sustentabilidade",
            10034 => "Eficiência Energética",
            10035 => "Corpo e Movimento",
            10036 => "Geometria",
            10037 => "Corpo e Saúde",
            10038 => "Ciências Biológicas, Agrárias e da Saúde",
            10039 => "Projeto de vida I",
            10040 => "Projeto de Leitura",
            10041 => "Pluralidade Cultural",
            10042 => "Estudo Dirigido: Atividade de Lingua Portuguesa e de Matmática",
            10043 => "Atividades Esportivas e Motoras",
        ];

        return $disciplines[$disciplineId] ?? '';
    }

$actualDay = valorPorExtenso(date('d'));
$actualMounth = date('M');
$actualYear = date('Y');

$turnoSelect =  $classroom->turn;
$turno = match ($turnoSelect) {
    'M' => "Matutino",
    'T' => "Tarde",
    'N' => "Noite",
    default => "----------------",
};

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
        <p style="text-align: justify;"><?php echo $actualDay?> do mês de <b><?php echo Yii::t('default', $actualMounth) ?></b> do ano de <b><?php echo $actualYear?></b>.
            Realizou-se o processo de apuração do rendimento escolar dos alunos da série de <b><?php echo $report["serie"]?></b>, turma <b><?php echo $classroom->name?></b>,
            turno <b><?php echo $turno?></b> do <b><?php echo $classroom->edcensoStageVsModalityFk->name?></b> deste estabelecimento, com a Carga Horária anual de _________ horas
        e um total de _________ dias letivos, conforme os resultados abaixo.</p>

        <?php
            $qtde = count($disciplines);
        ?>
        <br>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th scope="col" rowspan="5" class='vertical-text'><div>Ordem</div></th>
                <th scope="col" rowspan="5" class='vertical-text'><div>ID INEP</div></th>
                <th scope="col" rowspan="5" style="width: 300px;">Nome do Aluno</th>
                <th scope="col" colspan="<?php echo $qtde + 1?>">Componentes Curriculares</th></tr>
            <tr>
                <th scope="col" colspan="<?php echo $qtde + 1?>">Rendimento Escolar</th>
            </tr>
            <tr>
                <th scope="col" colspan="<?php echo $qtde?>">Disciplinas</th>
                <th scope="col" rowspan="3">Resultado Final</th>
            </tr>
            <tr>
                <?php if (count($baseDisciplines) > 0): ?>
                    <th scope="col" colspan="<?= count($baseDisciplines) ?>" style="text-align: center; font-weight: bold; min-width:150px;">Base Nacional Comum
                    </th>
                <?php endif ?>
                <?php if (count($diversifiedDisciplines) > 0): ?>
                    <th scope="col" colspan="<?= count($diversifiedDisciplines) ?>" style="text-align: center; font-weight: bold; min-width:150px;">Parte Diversificadora
                    </th>
                <?php endif ?>
            </tr>
            <tr>
                <?php foreach($baseDisciplines as $discipline) {
                    $name = $discipline["discipline_abbreviation"] == null ? $discipline["discipline_name"] : $discipline["discipline_abbreviation"];
                    if (strlen($name) <= 20) {
                        echo "<th scope='col' class='vertical-text'><div>".$name."</div></th>";
                    }else {
                        echo "<th scope='col' class='vertical-text'>".$name."</th>";
                    }
                }
                ?>
                <?php foreach($diversifiedDisciplines as $discipline) {
                    $name = $discipline["discipline_abbreviation"] == null ? $discipline["discipline_name"] : $discipline["discipline_abbreviation"];
                    if (strlen($name) <= 20) {
                        echo "<th scope='col' class='vertical-text'><div>".$name."</div></th>";
                    }else {
                        echo "<th scope='col' class='vertical-text'>".$name."</th>";
                    }
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            foreach($students as $s) {
                if ($count < 10) {
                    $labelId = "0".$count;
                }else {
                    $labelId = $count;
                }
                echo "<tr>"
                    ."<td>".$labelId."</td>"
                    ."<td>".$s->studentFk->inep_id."</td>"
                    ."<td>".$s->studentFk->name."</td>";
                $finalSituation = '';
                foreach ($grades as $grade) {
                    if($grade['student_id'] == $s->studentFk->id) {
                        echo "<td>".$grade['final_media']."</td>";
                        $finalSituation = $grade['situation'];
                    }
                }
                echo "<td>".$finalSituation."</td>";
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
