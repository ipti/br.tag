<style>
    @media print {
        table, .receipt {
            width: 100% !important;
        }
    }
    body {
        font-family: "Helvetica Neue", Arial, "Lucida Grande", sans-serif;
        text-align: center;
    }
    .upper-title {
        font-size: 18px;
        font-weight: bold
    }
    .body-title {
        font-weight: bold;
        font-size: 26px;
    }
    .header, .body-title {
        margin-bottom: 40px;
    }
    table, td, th {
        border: 1px solid #aaaaaa;
    }
    table {
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
        width: 50%;
    }
    .receipt {
        line-height: 28px;
        margin: 7px 0 7px 0;
        width: 50%;
        border: 1px solid #aaaaaa;
        font-size: 12px;
        margin-left: auto;
        margin-right: auto;
    }
    .bold {
        font-weight: bold;
    }
    .grades {
        text-align: center;
        font-weight: bold;
    }
    .grades td:nth-child(1) {
        text-align: left;
    }
    .grades tr:nth-child(1), .grades td:nth-child(1) {
        font-weight: normal;
    }

</style>
<?php
/* @var $enrollment string
 * @var $student StudentEnrollment
 * @var $school_address
 * @var $school_year string
 * @var $grade */
?>
<body>
<div class="header">
    <div class="upper-title">ESCOLAS REUNIDAS ORATÓRIO FESTIVO "SÃO JOÃO BOSCO"</div>
    <div class="lower-title">Rua Ribeirópolis, S/N - Fone: (79)3211-2480 - Aracaju/SE<br/>CNPJ: 13.099.870/0001-01. Fones: (79)3211-2480</div>
</div>
<div class="body">
    <div class="body-title">BOLETIM DO ALUNO</div>
    <table>
        <tr>
            <td colspan="11">Aluno: <span class="bold"><?php echo $student->studentFk->name?></span></td>
            <td colspan="2">Matrícula: <span class="bold"><?php echo $student->enrollment_id ?></span></td>
        </tr>
        <tr>
            <td colspan="6">Curso: <span class="bold"><?php echo $student->classroomFk->name?></span></td>
            <td>Turma:</td>
            <td>Turno:</td>
            <td colspan="3">Referência:</td>
            <td colspan="2">Período:</td>
        </tr>
    </table>
    <br/>
    <table class="grades">
        <tr>
            <td>Disciplina</td>
            <td>1°Av</td>
            <td>1°Re</td>
            <td>1°Md</td>
            <td>2°Av</td>
            <td>2°Re</td>
            <td>2°Md</td>
            <td>3°Av</td>
            <td>3°Re</td>
            <td>3°Md</td>
            <td>4°Av</td>
            <td>4°Re</td>
            <td>4°Md</td>
            <td>M.A</td>
            <td>PF2</td>
            <td>M.F</td>
            <td>TF&nbsp;</td>
            <td>Sit</td>
        </tr>
        <tr>
            <td style="font-size:15px;">L. Portuguesa</td>
            <td><?php echo $grade[6][1]?></td>
            <td><?php echo $grade[6][5]?></td>
            <td><?php echo $grade[6][10]?></td>
            <td><?php echo $grade[6][2]?></td>
            <td><?php echo $grade[6][6]?></td>
            <td><?php echo $grade[6][11]?></td>
            <td><?php echo $grade[6][3]?></td>
            <td><?php echo $grade[6][7]?></td>
            <td><?php echo $grade[6][12]?></td>
            <td><?php echo $grade[6][4]?></td>
            <td><?php echo $grade[6][8]?></td>
            <td><?php echo $grade[6][13]?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><div>Matemática<br></td>
            <td><?php echo $grade[3][1]?></td>
            <td><?php echo $grade[3][5]?></td>
            <td><?php echo $grade[3][10]?></td>
            <td><?php echo $grade[3][2]?></td>
            <td><?php echo $grade[3][6]?></td>
            <td><?php echo $grade[3][11]?></td>
            <td><?php echo $grade[3][3]?></td>
            <td><?php echo $grade[3][7]?></td>
            <td><?php echo $grade[3][12]?></td>
            <td><?php echo $grade[3][4]?></td>
            <td><?php echo $grade[3][8]?></td>
            <td><?php echo $grade[3][13]?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Ciências</td>
            <td><?php echo $grade[5][1]?></td>
            <td><?php echo $grade[5][5]?></td>
            <td><?php echo $grade[5][10]?></td>
            <td><?php echo $grade[5][2]?></td>
            <td><?php echo $grade[5][6]?></td>
            <td><?php echo $grade[5][11]?></td>
            <td><?php echo $grade[5][3]?></td>
            <td><?php echo $grade[5][7]?></td>
            <td><?php echo $grade[5][12]?></td>
            <td><?php echo $grade[5][4]?></td>
            <td><?php echo $grade[5][8]?></td>
            <td><?php echo $grade[5][13]?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Geografia</td>
            <td><?php echo $grade[13][1]?></td>
            <td><?php echo $grade[13][5]?></td>
            <td><?php echo $grade[13][10]?></td>
            <td><?php echo $grade[13][2]?></td>
            <td><?php echo $grade[13][6]?></td>
            <td><?php echo $grade[13][11]?></td>
            <td><?php echo $grade[13][3]?></td>
            <td><?php echo $grade[13][7]?></td>
            <td><?php echo $grade[13][12]?></td>
            <td><?php echo $grade[13][4]?></td>
            <td><?php echo $grade[13][8]?></td>
            <td><?php echo $grade[13][13]?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>História</td>
            <td><?php echo $grade[12][1]?></td>
            <td><?php echo $grade[12][5]?></td>
            <td><?php echo $grade[12][10]?></td>
            <td><?php echo $grade[12][2]?></td>
            <td><?php echo $grade[12][6]?></td>
            <td><?php echo $grade[12][11]?></td>
            <td><?php echo $grade[12][3]?></td>
            <td><?php echo $grade[12][7]?></td>
            <td><?php echo $grade[12][12]?></td>
            <td><?php echo $grade[12][4]?></td>
            <td><?php echo $grade[12][8]?></td>
            <td><?php echo $grade[12][13]?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Redação</td>
            <td><?php echo $grade[10001][1]?></td>
            <td><?php echo $grade[10001][5]?></td>
            <td><?php echo $grade[10001][10]?></td>
            <td><?php echo $grade[10001][2]?></td>
            <td><?php echo $grade[10001][6]?></td>
            <td><?php echo $grade[10001][11]?></td>
            <td><?php echo $grade[10001][3]?></td>
            <td><?php echo $grade[10001][7]?></td>
            <td><?php echo $grade[10001][12]?></td>
            <td><?php echo $grade[10001][4]?></td>
            <td><?php echo $grade[10001][8]?></td>
            <td><?php echo $grade[10001][13]?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="font-size: 13px;">Ling. Est. Inglês</td>
            <td><?php echo $grade[7][1]?></td>
            <td><?php echo $grade[7][5]?></td>
            <td><?php echo $grade[7][10]?></td>
            <td><?php echo $grade[7][2]?></td>
            <td><?php echo $grade[7][6]?></td>
            <td><?php echo $grade[7][11]?></td>
            <td><?php echo $grade[7][3]?></td>
            <td><?php echo $grade[7][7]?></td>
            <td><?php echo $grade[7][12]?></td>
            <td><?php echo $grade[7][4]?></td>
            <td><?php echo $grade[7][8]?></td>
            <td><?php echo $grade[7][13]?></td>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Média Global</td>
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
        <tr>
            <td style="font-size:13px;">Média da Turma</td>
            <td>&nbsp;</td>
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
        <tr>
            <td>Classificação</td>
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
    </table>
</div>

<br/>
<br/>
<span style="font-size:12px;">Corte</span><span> ----------------------------------------------------------------------------------------------------------------------------</span>
<div class="receipt">
    <div style="text-align:justify;">COMPROVANTE DE ENTREGA: Recebi o boletim do aluno: <?php echo $student->studentFk->name ?> matriculado no(a) <?php echo $student->classroomFk->name?>, turma ___.</div>
    <div>Data do Recebimento: <?php echo date("d/m/Y")?> Assinatura do Responsável: _______________________________________________</div>
</div>
<span>----------------------------------------------------------------------------------------------------------------------------------</span>
</body>