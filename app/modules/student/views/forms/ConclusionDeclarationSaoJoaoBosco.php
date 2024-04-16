<style>
    @media print {
        body {
            width: 100% !important;
            border: none !important;
            padding: 0px !important;
            margin-top: 50px !important;
        }
    }
    body {
        margin: 0px auto 0 auto;
        font-family: "Helvetica Neue", Arial, "Lucida Grande", sans-serif;
        width: 50%;
        border: 1px solid #333;
        padding: 10px;
    }
    .upper-title {
        font-weight: bold;
        font-size: 18px;
    }
    .header, .body-title, .footer {
        text-align:center;
    }
    .header {
        margin-bottom: 100px;
    }
    .body-title {
        font-size: 28px;
        margin-bottom: 150px;
    }
    .body-content {
        text-align: justify;
        text-indent: 6.7%;
        margin-bottom: 150px;
        font-size: 17px;
        line-height: 35px;
    }
    .date {
        margin-bottom: 250px;
    }
    .informer {
        text-align: justify;
        font-size: 12px;
    }
    .lower-title {
        font-size: 14px;
    }
</style>
<?php
/* @var $enrollment string
 * @var $student StudentEnrollment
 * @var $school_address
 * @var $school_year string */
?>
<body>
<div class="main-container">
    <div class="header">
        <div class="upper-title">ESCOLAS REUNIDAS ORATÓRIO FESTIVO "SÃO JOÃO BOSCO"</div>
        <div class="lower-title">Rua Ribeirópolis, S/N - Fone: (79)3211-2480 - Aracaju/SE<br/>Autorização de Funcionamento: Resolução n° 378 de 20/09/2007 do C.E.E.</div>
    </div>
</div>
<div class="body">
    <div class="body-title">DECLARAÇÃO DE CONCLUSÃO DE CURSO</div>
    <div class="body-content">Atesto para os devidos fins, que o aluno <?php echo $student->studentFk->name ?>, filha de <?php echo $student->studentFk->filiation_1?> e de <?php echo $student->studentFk->filiation_2?>, nascida em <?php echo $student->studentFk->birthday?>, na cidade de <?php echo $city["name"] . " - " . strtoupper($state["name"]);?>, concluiu o(a) <?php echo $student->classroomFk->name; ?> no ano de <?php echo $school_year?></div>
</div>
<div class="footer">
    <div class = "date">Aracaju-SE, <?php echo $today_date["mday"] ?> de <?php echo Yii::t('default', $today_date["month"]) ?> de <?php echo $today_date["year"]?></div>
    <div class="informer">
        <div>O presente atestado não tem validade para matrícula.</div>
        <div>Informante: Administrador do sistema.</div>
    </div>
</div>
</body>