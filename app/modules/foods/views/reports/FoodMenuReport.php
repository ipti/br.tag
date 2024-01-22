<?php
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($baseUrl . '/sass/css/main.css');

    $days  = ["monday", "tuesday", "wednesday", "thursday", "friday"]
?>
<style>
    th, td {
        text-align: center !important;
        vertical-align: middle !important;
        font-size: 14px;
    }
    td {
        padding: 8px !important;
    }
    h2 {
        font-size:16px;
        color:#252A31;
        text-align: center;
    }

    /* Hidden the print button */
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
<div class="pageA4H">
    <h2>DEPARTAMENTO DA ALIMENTAÇÃO ESCOLAR - DAE</h2>
    <div class="row t-margin-medium--top">
        <table class="column table table-bordered">
            <thead>
                <tr>
                    <th colspan="6" class="text-align--center" style="background: none !important;">
                        <b>SECRETARIA MUNICIPAL DE EDUCAÇÃO SANTA LUZIA DO ITANHI</b>
                        <br/>
                        <b>PROGRAMA NACIONAL DE ALIMENTAÇÃO ESCOLAR - PNAE</b>
                    </th>
                </tr>
                <tr>
                    <th colspan="6" class="text-align--center" style="background: none !important;">
                        <b>FUNDAMENTAL I (06-10 anos), FUNDAMENTAL II, QUILOMBOLA</b>
                        <br>
                        <b>ZONA RURAL/ PERÍODO PARCIAL</b>
                        <br>
                        <b><?= $foodMenu->description ?></b>
                        <!-- <b>4ª SEMANA - CARDÁPIO 2024</b> -->
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr  style="background-color: lightgray; font-weight: bold;">
                    <td>&nbsp;</td>
                    <td>
                        2ª FEIRA
                    </td>
                    <td>3ª FEIRA</td>
                    <td>4ª FEIRA</td>
                    <td>5ª FEIRA</td>
                    <td>6ª FEIRA</td>
                </tr>
                <?php foreach ($mealTypes as $mealType): ?>
                    <tr>
                            <td style="font-weight: bold;">
                            <?= $mealType["description"] ?><br/>
                            <?= $mealType["turn"] ?><br/>
                            <?= $mealType["meal_time"] ?>
                            </td>
                            <?php foreach ($days as $day): ?>
                                <td>
                                <?php foreach ($foodMenu->$day as $meal): ?>
                                    <?php if($meal["food_meal_type"] == $mealType["food_meal_type"]): ?>
                                        <div>
                                            <?php
                                                $descriptions =  array_reduce($meal["meals_component"], function($carry, $item) {
                                                    // Adiciona a descrição do item ao resultado acumulado
                                                    $carry[] = $item['description'];
                                                    return $carry;
                                                });
                                                echo implode(', ', $descriptions);
                                                //CVarDumper::dump($meal["meals_component"], 12, true)
                                            ?>
                                            </div>
                                            <div class="t-margin-medium--top" style="font-weight: bold;">Princpais Ingredientes:</div>
                                            <?php ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php  //CVarDumper::dump($mealTypes, 12, true)?>
    <?php  //CVarDumper::dump($foodMenu->monday[0]["meals_component"], 12, true)?>
</div>

<script>
    function imprimirPagina() {
        // printButton = document.getElementsByClassName('span12');
        // printButton.style.visibility = 'hidden';
        window.print();
        // printButton.style.visibility = 'visible';
    }
</script>
