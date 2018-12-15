<style>
    @media print {
        body {
            width: 100% !important;
        }
    }
    body {
        margin-right: auto;
        margin-left: auto;
        width: 50%;
    }
    .upper-title {
        font-size: 18px;
        font-weight: bold
    }
    .header {
        margin-bottom: 40px;
    }
    body {
        font-family: "Helvetica Neue", Arial, "Lucida Grande", sans-serif;
        text-align: center;
    }
    .small-title {
        font-weight: bold;
        font-size: 18px;
        text-align: center;
        margin-bottom: 10px;
    }
    .enrollment-data, .filiation-data, .responsable-data, .reserve {
        border: 1px solid #aaa;
        margin-bottom: 10px;
        padding: 10px 30px;
    }
    .responsable-data {
        margin-bottom: 4px;
    }
    .reserve {
        margin-right: 20px;
        padding: 2px 300px 2px 2px;
    }
    .bold {
        font-weight: bold;
    }
    .body {
        text-align: justify;
        line-height: 25px;
    }
    .signatures {
        border-top: 1px solid #111;
        margin-left: 15px;
    }

</style>
<?php
/* @var $enrollment string
 * @var $student StudentEnrollment
 * @var $school_address
 * @var $school_year string
 * @var $add_data */
?>
<body>
<div class="main-container">
    <div class="header">
        <div class="upper-title">ESCOLAS REUNIDAS ORATÓRIO FESTIVO "SÃO JOÃO BOSCO"</div>
        <div class="lower-title">Rua Ribeirópolis, S/N - Fone: (79)3211-2480 - Aracaju/SE<br/>CNPJ: 13.099.870/0001-01. Fones: (79)3211-2480</div>
    </div>
    <div class="body">
        <div class="enrollment-data">
            <div class="small-title">Ficha de Matrícula</div>
            <div>Aluno: <span class="bold"><?php echo $student->studentFk->name?></span> <span>Matrícula: <span class="bold"><?php echo $student->enrollment_id ?></span></span></div>
            <div>Curso/Turma: <span class="bold"><?php echo $student->classroomFk->name?></span>  <span>Telefone:__________  Celular: __________ </span></div>
            <div>Endereço: <span class="bold"><?php echo $add_data["address"]; if ($add_data["number"] != null || $add_data["number"] != "" ) { echo ", " . $add_data["number"];} if ($add_data["neighborhood"] != null || $add_data["neighborhood"] != "" ) { echo ", " . $add_data["neighborhood"];} ?></span></div>
            <div>Cidade: <span class="bold"><?php echo $city["name"]?></span> Estado: <span class="bold"><?php echo strtoupper($state["name"]);?></span><span> CEP: <span class="bold"><?php if ($add_data["cep"] != null || $add_data["cep"] != "" ) { echo $add_data["cep"];} else { echo "_____________________"; } ?></span></span></div>
            <div>Escola de Origem: ___________________ <span>Cidade: ____________ Estado: _____________ </span></div>
            <div style="clear:both;">RG: <?php if ($add_data["rg_number"] != null || $add_data["rg_number"] != "" ) { echo "<span class=\"bold\">" . $add_data["rg_number"] . "</span>"; } else { echo "_________ " ;}?> Org.Exp.: ________ <span>Data Exp.: <?php if ($add_data["rg_number_expediction_date"] != null || $add_data["rg_number_expediction_date"] != "" ) { echo "<span class=\"bold\">" . $add_data["rg_number_expediction_date"] . "</span>" ;} else { echo "________ " ;}?> Data de Nascimento: <span class="bold"><?php echo $student->studentFk->birthday?></span></span></div>
            <div style="clear:both;">Certidão de Nascimento:____________ Email: ________________ <span>Estado Civil: ________</span></div>
            <div>CPF: <?php if ($add_data["cpf"] != null || $add_data["cpf"] != "" ) { echo "<span class=\"bold\">" . $add_data["cpf"] . "</span>"; } else { echo "___________ " ;}?> Naturalidade: <span class="bold"><?php echo strtoupper($student->studentFk->edcensoUfFk->name)?></span> <span>Nacionalidade: <span class="bold"><?php echo $student->studentFk->edcensoNationFk->name?></span></span></div>
        </div>
        <div class="filiation-data">
            <div class="small-title">Dados da Filiação</div>
            <div>Nome do Pai: <span class="bold"><?php echo $student->studentFk->filiation_2?></span></div>
            <div>Endereço: _________________________________________________________________</div>
            <div>Cidade: _____________ Estado: ___________ CEP: __________ Telefone: ____________</div>
            <div>Data de Nascimento: __/__/__ Nacionalidade: ________________ Instrução: ___________ </div>
            <div>CPF: _______________ Celular: __________________ Email: ______________________</div>
            <div>Identidade: ____________ Profissão: _______________ Telefone Trabalho: ____________</div>
            <div>Nome da mãe: <span class="bold"><?php echo $student->studentFk->filiation_1?></span></div>
            <div>Endereço: _________________________________________________________________</div>
            <div>Cidade: _____________ Estado: ___________ CEP: __________ Telefone: ____________</div>
            <div>Data de Nascimento: __/__/__ Nacionalidade: ________________ Instrução: ___________ </div>
            <div>CPF: _______________ Celular: __________________ Email: ______________________</div>
            <div>Identidade: ____________ Profissão: _______________ Telefone Trabalho: ____________</div>
        </div>
        <div class="responsable-data">
            <div class="small-title">Dados do Responsável Financeiro</div>
            <div>Nome do Responsável: <?php if ($student->studentFk->responsable_name != null || $student->studentFk->responsable_name != "" ) { echo "<span class=\"bold\">" . $student->studentFk->responsable_name . "</span>"; } else { echo "______________________________________________________ " ;}?></div>
            <div>Endereço: _________________________________________________________________</div>
            <div>Cidade: _____________ Estado: _____________ CEP: ________ Telefone: <?php if ($student->studentFk->responsable_telephone != null || $student->studentFk->responsable_telephone != "" ) { echo "<span class=\"bold\">" . $student->studentFk->responsable_telephone . "</span>"; } else { echo "____________" ;}?></div>
            <div>Data de Nascimento: __/__/__ Nacionalidade: _____________ Instrução: ______________ </div>
            <div>CPF: <?php if ($student->studentFk->responsable_cpf != null || $student->studentFk->responsable_cpf != "" ) { echo "<span class=\"bold\">" . $student->studentFk->responsable_cpf . "</span>"; } else { echo "______________ " ;}?> Celular: _________________ Email: ________________________</div>
            <div>Identidade: <?php if ($student->studentFk->responsable_rg != null || $student->studentFk->responsable_rg != "" ) { echo "<span class=\"bold\">" . $student->studentFk->responsable_rg . "</span>"; } else { echo "_____________ " ;}?> Profissão:<?php if ($student->studentFk->responsable_job != null || $student->studentFk->responsable_job != "" ) { echo "<span class=\"bold\">" . $student->studentFk->responsable_job . "</span>"; } else { echo "______________ " ;}?> Telefone Trabalho: _____________</div>
        </div>
    </div>
</div>
<br/>
<div><span class="reserve">Reserva?</span><span>Data: <?php echo date("d/m/Y") ?></span></div>
<div style="margin-top:27px;"><span class="signatures">Assinatura do(a) Responsável</span><span class="signatures">Assinatura do(a) Secretário(a)</span><span class="signatures">Assinatura do(a) Diretor(a)</span></div>

</body>