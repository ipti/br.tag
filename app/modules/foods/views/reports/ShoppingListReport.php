<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/sass/css/main.css');

$turns = ["Manhã", "Tarde", "Noite", "Integral"];
?>
<style>
     table {
        border-collapse: collapse;
    }

    th,
    td {
        font-size: 14px;
        border: 1.5px solid black;
        padding: 8px !important;
    }
    .background-dark {
        background-color: lightgray;
    }

    .font-bold {
        font-weight: bold;
    }
    h2 {
        font-size: 16px;
        color: #252A31;
    }
    .pageA4H {
        width: 1122px;
        height: auto;
        margin: 0 auto;
    }
    .text-center {
        text-align: center !important;
    }
    .table .subtitle {
        font-size: 14px;
        color: #252A31;
        background-color: lightgray !important;
    }

    @media print {
        #print {
            display: none;
        }
    }
</style>
<div class="row buttons">
    <a id="print" class="t-button-secondary" onclick="imprimirPagina()">
        <span class="t-icon-printer"></span>imprimir
    </a>
</div>
<div id="page" class="pageA4H">
    <h2 id="title-page" class="text-center">
        SECRETARIA MUNICIPAL DE EDUCAÇÃO <br>
        DEPARTAMENTO DA ALIMENTAÇÃO ESCOLAR - DAE
    </h2>
    <div class="row t-margin-medium--top">
        <table aria-describedby="title-page" class="column table table-bordered">
            <thead class="background-dark font-bold">
            <tr>
                    <th id="title" colspan="2" class="text-center">
                        <div>SECRETARIA MUNICIPAL DE
                            <?= $schoolCity ?>
                        </div>
                        <br />
                        <div>PROGRAMA NACIONAL DE ALIMENTAÇÃO ESCOLAR - PNAE</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-bold subtitle">Alimento</td>
                    <td class="font-bold subtitle">Quantidade</td>
                </tr>


                    <?php
                        foreach ($foodIngredientsList as $foodItem):
                            if($foodItem["total"] != 0):
                    ?>


                        <tr>
                            <td><?= $foodItem["name"]?></td>
                            <td><?= $foodItem["total"] . $foodItem["measure"] ?></td>
                        </tr>
                    <?php
                            endif;
                        endforeach;
                    ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function imprimirPagina() {
        window.print();
    }
</script>
