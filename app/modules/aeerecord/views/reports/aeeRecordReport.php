<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/sass/css/main.css');

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>
<style>
    #info li {text-align:center;}
    #addinfo li{text-align: center}

    h2 {
        font-size: 20px;
        color: #252A31;
        text-align: center;
    }

    .content-text {
        font-size: 15px;
    }

    .is-three-twentieths {
        max-width: 15%;
    }

    .background-dark {
        background-color: lightgray;
    }

    .font-bold {
        font-weight: bold;
    }

    .nowrap {
        white-space: nowrap;
    }

    .pageA4H {
        width: 1122px;
        height: 555px;
        margin: 0 auto;
    }

    /* Hidden the print button */
    @media print {
        #print {
            display: none;
        }

        .background-dark {
            background-color: lightgray !important;
            print-color-adjust: exact;
        }


    }
</style>
<div class="row buttons">
    <button id="print" class="t-button-secondary" onclick="imprimirPagina()">
        <span class="t-icon-printer"></span>imprimir
    </button>
</div>
<div id="page" class="pageA4H">
    <div id="header-report">
        <ul id="info">
            <li><?php echo $school->name ?></li>
        </ul>

        <ul id="addinfo">

            <?php
                $cep = $school->cep;

if (empty($cep)) {
    $fieldCep = '';
} elseif (ctype_digit($cep) && strlen($cep) === 8) {
    $formattedCep = substr($cep, 0, 2) . '.' . substr($cep, 2, 3) . '-' . substr($cep, 5, 3);
    $fieldCep = ', CEP: ' . $formattedCep;
} else {
    $fieldCep = '';
}
?>

            <li>
                <?php
        echo $school->address . ', ' . (!empty($school->address_number) ? $school->address_number . ', ' : '') . $school->address_neighborhood;
?>,
                <?php
echo $school->edcensoCityFk->name . ' - ' . $school->edcensoUfFk->acronym . $fieldCep ?> </li>
            <li><?php echo $school->act_of_acknowledgement ?></li>
        </ul>
        <span class="clear"></span>

    </div>
    <h2 id="title-page">
        FICHA AEE - Detalhe
    </h2>
    <div id="aeeContent" class="content-text">
        <div class="row t-margin-medium--bottom">
            <div class="column is-one-tenth font-bold">ID:</div>
            <div class="column"><?php echo $aeeRecord['id']; ?></div>
        </div>
        <div class="row t-margin-medium--bottom">
            <div class="column is-one-tenth font-bold">Data:</div>
            <div class="column"><?php echo date('d/m/Y', strtotime($aeeRecord['date']))?></div>
        </div>
        <div class="row t-margin-medium--bottom">
            <div class="column is-one-tenth font-bold">Aluno:</div>
            <div class="column"><?php echo $aeeRecord['studentName']?></div>
        </div>
        <div class="row t-margin-medium--bottom">
            <div class="column is-one-tenth font-bold">Necessidades de aprendizagem:</div>
            <div class="column text-align--justify"><?php echo $aeeRecord['learning_needs']?></div>
        </div>
        <div class="row t-margin-medium--bottom">
            <div class="column is-one-tenth font-bold">Caracterização pedagógica:</div>
            <div class="column text-align--justify"><?php echo $aeeRecord['characterization']?></div>
        </div>
        <div class="row t-margin-medium--bottom">
            <div class="column is-one-tenth font-bold">Escola:</div>
            <div class="column"><?php echo $school->name?></div>
        </div>
        <div class="row t-margin-medium--bottom">
            <div class="column is-one-tenth font-bold">Turma:</div>
            <div class="column"><?php echo $aeeRecord['classroomName']?></div>
        </div>
        <div class="row t-margin-medium--bottom">
            <div class="column is-one-tenth font-bold">Professor:</div>
            <div class="column"><?php echo $aeeRecord['instructorName']?></div>
        </div>
    </div>
</div>
<script>
    function imprimirPagina() {
        window.print();
    }
</script>
