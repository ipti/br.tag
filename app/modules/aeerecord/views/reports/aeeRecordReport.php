<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/sass/css/main.css');

if(!isset($school)){
    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
}
?>
<style>
    #info li {text-align:center;}
    #addinfo li{text-align: center}

    table {
        border-collapse: collapse;
    }

    th,
    td {
        text-align: center !important;
        vertical-align: middle !important;
        font-size: 14px;
        border: 1px solid black;
    }

    td {
        padding: 8px !important;
        max-width: 250px;
    }

    h2 {
        font-size: 16px;
        color: #252A31;
        text-align: center;
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
    <a id="print" class="t-button-secondary" onclick="imprimirPagina()">
        <span class="t-icon-printer"></span>imprimir
    </a>
</div>
<div class="pageA4H">
    <div id="header-report">
        <ul id="info">
            <?php if (isset($school->act_of_acknowledgement) && !empty($school->act_of_acknowledgement)) { ?>
                <li><?php echo $school->name ?></li>
            <?php } else { ?>
                <li><?php echo $school->name ?></li>
            <?php } ?>
        </ul>

        <ul id="addinfo">

            <?php
                $cep = $school->cep;

                if (empty($cep)){
                    $fieldCep = '';
                } else if (ctype_digit($cep) && strlen($cep) === 8) {
                    $formatted_cep = substr($cep, 0, 2) . '.' . substr($cep, 2, 3) . '-' . substr($cep, 5, 3);
                    $fieldCep = ', CEP: '. $formatted_cep;
                } else {
                    $fieldCep = '';
                }
            ?>

            <li>
                <?php
                    echo $school->address.', '.(!empty($school->address_number) ? $school->address_number.', ':'' ).$school->address_neighborhood;
                ?>,
                <?php
                echo $school->edcensoCityFk->name . " - " . $school->edcensoUfFk->acronym . $fieldCep ?> </li>
            <li><?php echo $school->act_of_acknowledgement ?></li>
            <!--<?php echo 'Email: '.(!empty($school->email) ? $school->email.' - ': (!empty($school->manager_email) ? $school->manager_email.' - ':'' ) ).'Tel: '.(!empty($school->phone_number) ? $school->phone_number:'' )?>-->
        </ul>
        <span class="clear"></span>

    </div>
    <h2 id="title-page">
        FICHA AEE - Detalhe
    </h2>

</div>
<script>
    function imprimirPagina() {
        window.print();
    }
</script>
