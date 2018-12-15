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
        padding: 20px;
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
        font-size: 30px;
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
        <div class="lower-title"><?php echo $school_address ?></div>
    </div>

    <div class="body">
        <div class="body-title">ATESTADO DE TRANSFERÊNCIA</div>
        <div class="body-content">Atesto para os devidos fins, que o aluno <?php echo $student->studentFk->name ?>, do curso <?php echo $student->classroomFk->name; ?> deste estabelecimento de ensino, desta capital, no ano letivo de <?php echo $school_year; ?>, requereu nesta data, a transferência para outro estabelecimento e, oportunamente, a mesma será expedida.<p>O documento legal de transferência, deverá ser procurado pelo interessado, em nossa Secretaria, no prazo máximo de 30 (trinta) dias, período em que o atestado, de acordo com a lei, perde sua validade.</p></div>
    </div>
    <div class="footer">
        <div class = "date">Aracaju-SE, <?php echo $today_date["mday"] ?> de <?php echo Yii::t('default', $today_date["month"]) ?> de <?php echo $today_date["year"]?></div>

        <div class="informer">
            <div>Informante: Administrador do sistema.</div>
        </div>
    </div>
</div>
</body>