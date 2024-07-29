array
(
    '1º ANO INTEGRAL' => array
    (
        'school_year' => '2023'
        'base_disciplines' => array
        (
            0 => array
            (
                'discipline_id' => '5'
                'discipline_name' => 'Ciências'
                'final_media' => '18'
            )
            1 => array
            (
                'discipline_id' => '13'
                'discipline_name' => 'Geografia'
                'final_media' => '1.8'
            )
            2 => array
            (
                'discipline_id' => '10'
                'discipline_name' => 'Arte (Educação Artística, Teatro, Dança, Música, Artes Plásticas e outras)'
                'final_media' => '4.3'
            )
            3 => array
            (
                'discipline_id' => '3'
                'discipline_name' => 'Matemática'
                'final_media' => '16.3'
            )
            4 => array
            (
                'discipline_id' => '11'
                'discipline_name' => 'Educação Física'
                'final_media' => '18.5'
            )
            5 => array
            (
                'discipline_id' => '6'
                'discipline_name' => 'Língua /Literatura Portuguesa '
                'final_media' => '0'
            )
        )
        'diversified_disciplines' => array()
    )
    'TESTE ESTRUTURA DE NOTAS' => array
    (
        'school_year' => '2023'
        'base_disciplines' => array
        (
            0 => array
            (
                'discipline_id' => '10'
                'discipline_name' => 'Arte (Educação Artística, Teatro, Dança, Música, Artes Plásticas e outras)'
                'final_media' => '15'
            )
        )
        'diversified_disciplines' => array()
    )
    'EDUCACAO INFANTIL B' => array
    (
        'school_year' => '2023'
        'base_disciplines' => array
        (
            0 => array
            (
                'discipline_id' => '3'
                'discipline_name' => 'Matemática'
                'final_media' => '0'
            )
            1 => array
            (
                'discipline_id' => '5'
                'discipline_name' => 'Ciências'
                'final_media' => '0'
            )
            2 => array
            (
                'discipline_id' => '6'
                'discipline_name' => 'Língua /Literatura Portuguesa '
                'final_media' => '0'
            )
            3 => array
            (
                'discipline_id' => '10'
                'discipline_name' => 'Arte (Educação Artística, Teatro, Dança, Música, Artes Plásticas e outras)'
                'final_media' => '0'
            )
        )
        'diversified_disciplines' => array
        (
            0 => array
            (
                'discipline_id' => '4'
                'final_media' => '0'
            )
            1 => array
            (
                'discipline_id' => '8'
                'final_media' => '0'
            )
            2 => array
            (
                'discipline_id' => '2'
                'final_media' => '0'
            )
            3 => array
            (
                'discipline_id' => '10008'
                'final_media' => '0'
            )
        )
    )
    'TURMA ENSINO MEDIO ' => array
    (
        'school_year' => '2024'
        'base_disciplines' => array()
        'diversified_disciplines' => array
        (
            0 => array
            (
                'discipline_id' => '25'
                'final_media' => 0
            )
        )
    )
    '5º ANO MANHÃ' => array
    (
        'school_year' => '2024'
        'base_disciplines' => array
        (
            0 => array
            (
                'discipline_id' => '5'
                'discipline_name' => 'Ciências'
                'final_media' => '66'
            )
            1 => array
            (
                'discipline_id' => '13'
                'discipline_name' => 'Geografia'
                'final_media' => '35'
            )
            2 => array
            (
                'discipline_id' => '11'
                'discipline_name' => 'Educação Física'
                'final_media' => 0
            )
            3 => array
            (
                'discipline_id' => '10'
                'discipline_name' => 'Arte (Educação Artística, Teatro, Dança, Música, Artes Plásticas e outras)'
                'final_media' => 0
            )
            4 => array
            (
                'discipline_id' => '12'
                'discipline_name' => 'História'
                'final_media' => '60.1'
            )
            5 => array
            (
                'discipline_id' => '3'
                'discipline_name' => 'Matemática'
                'final_media' => '73'
            )
            6 => array
            (
                'discipline_id' => '7'
                'discipline_name' => 'Língua /Literatura estrangeira - Inglês '
                'final_media' => 0
            )
            7 => array
            (
                'discipline_id' => '6'
                'discipline_name' => 'Língua /Literatura Portuguesa '
                'final_media' => '76'
            )
        )
        'diversified_disciplines' => array
        (
            0 => array
            (
                'discipline_id' => '4'
                'final_media' => 0
            )
        )
    )
)
*****************************************************

<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentPerClassroomReport/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);

if (!isset($school)) {
    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
}
list($day, $month, $year) = explode('/', $student['birthday']);
$months = array(
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
);
$monthName = $months[$month];

$disciplinas = array(
    1 => 'Química',
    2 => 'Física',
    3 => 'Matemática',
    4 => 'Biologia',
    5 => 'Ciências',
    6 => 'Português',
    7 => 'Inglês',
    8 => 'Espanhol',
    9 => 'Outro Idioma',
    10 => 'Artes',
    11 => 'Educação Física',
    12 => 'História',
    13 => 'Geografia',
    14 => 'Filosofia',
    16 => 'Informática',
    17 => 'Disc. Profissionalizante',
    20 => 'Educação Especial',
    21 => 'Sociedade e Cultura',
    23 => 'Libras',
    25 => 'Disciplinas pedagógicas',
    26 => 'Ensino religioso',
    27 => 'Língua indígena',
    28 => 'Estudos Sociais',
    29 => 'Sociologia',
    30 => 'Francês',
    99 => 'Outras Disciplinas',
    10001 => 'Redação',
    10002 => 'Linguagem oral e escrita',
    10003 => 'Natureza e sociedade',
    10004 => 'Movimento',
    10005 => 'Música',
    10006 => 'Artes visuais',
    10007 => 'Acompanhamento Pedagógico',
    10008 => 'Teatro',
    10009 => 'Canteiro Sustentável',
    10010 => 'Dança',
    10011 => 'Cordel',
    10012 => 'Física'
);

$disciplineData = $student['schoolData'];

CVarDumper::dump($disciplineData, 10, true);


$disciplineIds = [];

// Varrer cada turma e coletar os discipline_id
foreach ($disciplineData as $classData) {
    foreach ($classData['base_disciplines'] as $discipline) {
        if (!in_array($discipline['discipline_id'], $disciplineIds)) {
            $disciplineIds[] = $discipline['discipline_id'];
        }
    }
}



?>


<div class="pageA4H">
    <div style="text-align: center;">
        <div style="position: relative; display: inline-block;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/brasao.png" alt="Brasão" style="width: 80px; position: absolute; top: -60px; left: 50%; transform: translateX(-50%);" />
        </div>
        <h4>ESTADO DO <?php echo strtoupper($school->edcensoUfFk->name); ?></h4>
        <h5>PREFEITURA MUNICIPAL DE <?php echo $school->edcensoCityFk->name; ?></h5>
        <h5>SECRETARIA MUNICIPAL DE EDUCAÇÃO</h5>
        <h1>CERTIFICADO</h1>
    </div>

    <div class="container-certificate">
        <p>O(A) Diretor(a) da Escola <?php echo $school->name ?>,
        no uso de suas atribuições legais, confere o presente Certificado do <?php echo $student['school_year']; ?> do <?php echo $student['etapa_name']; ?> a <b><?php echo $student['name']; ?></b>
       filho(a) de <?php echo $student['filiation_1']; ?>
        e de <?php echo $student['filiation_2']; ?>.</p>
        <p>Nascido(a) em <?php echo $day; ?> de <?php echo $monthName; ?> de <?php echo $year; ?>, no Município de <?php echo $student['city_name']; ?>
        Estado de <?php echo $student['uf_name']; ?>.</p>
    </div>

    <div class="content-data">
        <div style="display: inline-block; width: 45%; text-align: center;">
            <p>_______________________________</p>
            <p>Secretário(a)</p>
        </div>
        <div style="display: inline-block; width: 45%; text-align: center;">
            <p>______________ (MA) ______________ de ______________ de _____________</p>
        </div>
    </div>

    <div class="signature-section">
        <p>_______________________________________________</p>
        <p>Aluno(a)</p>
    </div>
    <div class="content-data-signature">
        <div>
            <p>Reconhecida pela Resolução nº 005/2023-CME de 28/09/2023</p>
            <p>Reconhecida pela Resolução do CME Conselho Municipal de Educação</p>
        </div>

        <div style="text-align: center;">
            <p>_______________________________</p>
            <p>Diretor(a) da Unidade de Ensino</p>
        </div>
    </div>
    <div class="row-fluid hidden-print" style="margin-top: 20px;">
        <div class="span12">
            <div class="buttons" style="text-align: center;">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;">
                    <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i>
                </a>
            </div>
        </div>
    </div>
    <?php $this->renderPartial('footer'); ?>
</div>

<div class="container-school-record">
    <div class="table-contant" style="display: flex; align-items: center; justify-content: center;">
    <table>
        <tr>
            <th class="vertical-header">IDADE</th>
            <th class="vertical-header">SÉRIE</th>

            <?php foreach ($disciplineIds as $disciplineId): ?>
                <th class="vertical-header">
                    <?php echo isset($disciplinas[$disciplineId]) ? $disciplinas[$disciplineId] : "Disciplina $disciplineId"; ?>
                </th>
            <?php endforeach; ?>

            <th class="vertical-header">MÉDIA ANUAL</th>
            <th class="vertical-header">ANO</th>
        </tr>

        <?php
        foreach ($disciplineData as $className => $classData): ?>
            <tr>
                <td><?php echo $className; ?></td>

                <?php foreach ($disciplineIds as $disciplineId): ?>
                    <?php
                    $found = false;
                    foreach ($classData['base_disciplines'] as $discipline) {
                        if ($discipline['discipline_id'] == $disciplineId) {
                            echo "<td>{$discipline['final_media']}</td>";
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        echo "<td>0</td>";
                    }
                    ?>
                <?php endforeach; ?>

                <td><!-- Média anual, se necessário --></td>
                <td><?php echo $classData['school_year']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    </div>
</div>
<br>