<?php
?>

<style>
    body {
        padding: 10px 75px 10px 75px;
        text-align: justify;
        font-family: "Helvetica Neue", Arial, "Lucida Grande", sans-serif;
        border: 1px solid;
    }
    .centered {
        margin-left: auto;
        margin-right: auto;
    }
    .header {
        border: 1px solid;
        padding: 5px;
        margin-bottom: 50px;
        font-weight: bold;
        font-size: 18px;
    }
    .signature-line {
        border-top: 1px solid;
        width: 500px;
    }
    .header, .title, .signature-line {
        text-align: center;
    }
    .title {
        margin-bottom: 10px;
    }
    .third-box {
        margin-bottom: 60px;
    }
    .first-box, .third-box {
        text-indent: 6.7%;
    }
    .main {
        height: 100%;
        width: 100%;
        display: table;
    }
    .container {
        display: table-cell;
        height: 100%;
        vertical-align: middle;
    }
    .bold {
        font-weight: bold;
    }

</style>
<?php
    /** 
     * @var $enrollment string
     * @var StudentEnrollment $student StudentEnrollment
     * @var $school_address
     * @var $school_year string 
     * 
     **/
?>
<div>
    <div class="main">
        <div class="container">
            <div class="header centered"><?php echo $student->schoolInepIdFk->name;?><br/><span style="font-size: 12px; font-weight: normal"><?php echo $school_address; ?> </span></div>
            <h3 class="title">TERMO DE COMPROMISSO</h3>
            <br/>
            <div class="first-box">Eu ______________________________________________________, responsável pelo(a) aluno(a) <span class="bold"><?php echo $student->studentFk->name;?></span>, matriculado(a) no <span class="bold"><?php echo $student->classroomFk->name?></span> do ano letivo de <span class="bold"><?php echo $school_year;?></span>, ciente de que as aulas serão oferecidas em horário integral (das 7h às 16h30m), comprometo-me em:</div>
            <ul>
                <li>Encaminhá-lo com uniforme completo doado pelo Instituto, meia branca e tênis preto de livre escolha sob minha responsablidade;</li>
                <li>Fazê-lo(a) cumprir diariamente o horário de entrada 7h, com tolerância máxima de 10min e em caso de atraso deverei me dirigir à Direção para apresentar as justificativas e em caso contrário serei advertido.</li>
                <li>Orientá-lo no cumprimento das tarefas de casa diárias</li>
                <li>Buscá-lo na escola impreterivelmente até às 16h20m, com tolerância máxima de 10min, ciente de que não deverei chegar após o horário, visto que o expediente de trabalho das educadoras encerra-se no referido horário.</li>
                <li>Participar das programações da escola e atender ao chamado sempre que for convocado(a)</li>
                <li>Adquirir o material pedagógico básico solicitado pela professora, cuidar dos livros didáticos (forrar e identificar com o nome da criança).</li>
                <li>Zelar pela conservação do patrimônio físico, mobiliários, equipamentos e demais bens, responsabilizando-nos pela reparação de quaisquer danos e/ou prejuízos eventualmente causados.</li>
                <li>Informar a Escola em caso de necessidade de falta por motivo de doença ou outros motivos justificáveis, não podendo ultrapassar o número de três faltas durante o ano, sem justificativa.</li>
                <li>Não permitir que o meu filho(a) leve lanche para Escola</li>
            </ul>
            <br/>
            <div class="third-box">Declaro que estou de acordo com as obrigações necessárias elencadas, ciente de que a omissão e ou descumprimento nas regras estabelecidas, podem resultar na perda da vaga nesta Escola no processo de renovação de matrícula.</div>
            <br/>
            <div class="signature-line centered">Assinatura do Pai/Mãe ou Responsável</div>
        </div>
    </div>
</div>