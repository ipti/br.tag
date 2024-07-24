<?php
// Inclua o script para inicialização
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentPerClassroomReport/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);

// Obtenha os dados da escola
if (!isset($school)) {
    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
}

// Extraia a data de nascimento
list($day, $month, $year) = explode('/', $student['birthday']);
$months = array(
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
);
$monthName = $months[$month];

CVarDumper::dump(($day), 10, true);
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
        <table class="school-record-table">
            <tr>
                <th rowspan="11" class="vertical-header vida-escolar">VIDA ESCOLAR</th>
                <th colspan="10" style="text-align: center">DISCIPLINAS</th>
                <th rowspan="1" class="estabelecimento">NOME DO ESTABELECIMENTO</th>
            </tr>
            <tr>
                <th class="vertical-header">IDADE</th>
                <th class="vertical-header">SÉRIE</th>

                <?php foreach ($student['disciplines'] as $discipline): ?>
                    <th class="vertical-header"><?php echo CHtml::encode($discipline); ?></th>
                <?php endforeach; ?>

                <th class="vertical-header">MÉDIA ANUAL</th>
                <th class="vertical-header">ANO</th>
            </tr>
            <tr>
                <td></td>
                <td><?php echo substr($student['class_name'], 0, strpos($student['class_name'], ' ')); ?></td>
                <?php foreach ($student['grades'] as $grade): ?>
                    <td><?php echo CHtml::encode($grade); ?></td>
                <?php endforeach; ?>
                <td><?php echo isset($student['annual_average']) ? number_format($student['annual_average'], 1) : '---'; ?></td>
                <td><?php echo CHtml::encode($student['school_year']); ?></td>
            </tr>
            <!-- Adicione linhas adicionais se necessário -->
            <tr>
                <td></td>
                <td colspan="10"></td>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td colspan="10"></td>
                <th rowspan="1">Autentificação</th>
            </tr>
        </table>
    </div>
</div>
<br>

<script>
    function imprimirPagina() {
        window.print();
    }
</script>

<style>
    .pageA4H {
        border-radius: 10px;
        padding: 10px;
        border: 2px solid #000;
        font-family: 'Arial', sans-serif;
        width: 90%;
        height: 100%;
        position: relative;
        box-sizing: border-box;
        margin: 23px 60px 23px 60px;
    }

    h1, h4, h5 {
        margin-top:5px;
    }
    h4 {
        font-size: 13.99px;
        font-weight: 700;
        color: #252A31;
    }
    h5 {
        font-size: 13.99px;
        font-weight: 400;
        color: #252A31;
    }
    h1 {
        font-weight: 900;
        font-size: 35.13px;
        color: #16205B;
        margin: 20px;
    }
    .content-data {
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    p {
        margin: 5px 0;
        font-size: 14px;
        font-weight: 500;
    }
    .signature-section {
        margin-top: 25px;
        text-align: center;
    }

    .signature-section p {
        margin: 20px 0;
    }
    .container-certificate {
        display: flex;
        justify-content: center;
        flex-direction: column;
        text-align: justify;
        padding: 10px 60px;
    }
    .content-data-signature {
        display: flex;
        justify-content: space-around;
        gap: 200px;
        margin-top: 20px;
    }
    .school-record-table {
        width: 90%;
        border-collapse: collapse;
        margin: 50px;
        /* table-layout: fixed; */
        border: 2px solid #000;
    }

    .school-record-table th, .school-record-table td {
        border: 1px solid #000;
        text-align: center;
        padding: 5px;
        width: 40px;
    }

    .school-record-table th.vertical-header {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        min-width: 10px;
    }

    .school-record-table thead th {
        background-color: #f0f0f0;
        font-weight: bold;
        vertical-align: middle;
    }

    .school-record-table tbody td {
        height: 11px;
    }

    .container-school-record {
        page-break-before: always;
        margin-top: 20px;
        display: flex;

    }

    .signature-section {
        text-align: center;
        margin-top: 20px;
    }

    .signature-section p {
        margin: 5px 0;
        font-weight: bold;
    }

    .school-record-table thead th[rowspan="2"] {
        height: 80px;
    }

    .school-record-table thead th[colspan="12"] {
        text-align: center;
    }

    .school-record-table th.estabelecimento {
        width: 387px;
    }
    .school-record-table th.th-disciplinas {
        width: 300px;
    }

    .school-record-table th.vida-escolar {
        width: 42px;
    }

    @media print {
        .hidden-print {
            display: none;
        }

        @page {
            size: landscape;
        }
    }
</style>
