<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/sass/css/main.css');

$days = ["monday", "tuesday", "wednesday", "thursday", "friday"]
    ?>
<style>
    th,
    td {
        text-align: center !important;
        vertical-align: middle !important;
        font-size: 14px;
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
            <thead class="background-dark font-bold" >
                <tr>
                    <th colspan="6" class="text-align--center" style="background: none !important;">
                        <div>SECRETARIA MUNICIPAL DE EDUCAÇÃO SANTA LUZIA DO ITANHI</div>
                        <br />
                        <div>PROGRAMA NACIONAL DE ALIMENTAÇÃO ESCOLAR - PNAE</div>
                    </th>
                </tr>
                <tr>
                    <th colspan="6" class="text-align--center" style="background: none !important;">
                        <div>
                        <?= $foodMenu->description ." - ". $publicTarget["name"] ?>
                        </div>
                        <br>
                        <div>
                            <?php switch ($school) {
                                case 1:
                                    $localizacao = 'Urbano';
                                    break;
                                case 2:
                                    $localizacao = 'Rural';
                                    break;
                                default:
                                    $localizacao = 'Desconhecido';
                                    break;
                            }
                            echo $localizacao;
                            ?>
                        </div>
                        <br>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="font-bold">
                    <td class="background-dark" >&nbsp;</td>
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
                        <td class="background-dark font-bold">
                            <?= $mealType["description"] ?><br />
                            <?= $mealType["turn"] ?><br />
                            <?= $mealType["meal_time"] ?>
                        </td>
                        <?php foreach ($days as $day): ?>
                            <td>
                                <?php foreach ($foodMenu->$day as $meal): ?>
                                    <?php if ($meal["food_meal_type"] == $mealType["food_meal_type"]): ?>
                                        <div>
                                            <?php
                                            $descriptions = array_reduce($meal["meals_component"], function ($carry, $item) {
                                                // Adiciona a descrição do item ao resultado acumulado
                                                $carry[] =  $item['description'];
                                                return $carry;
                                            });
                                            echo implode(', ', $descriptions);
                                            //CVarDumper::dump($meal["meals_component"], 12, true)
                                            ?>
                                        </div>
                                        <div class="t-margin-medium--top">
                                            <span class="font-bold">Princpais Ingredientes:</span>
                                            <br>
                                            <?php
                                            $igredientsList = array();
                                            foreach ($meal["meals_component"] as $mealComponent) {
                                                foreach ($mealComponent["ingredients"] as $igredients) {
                                                    array_push($igredientsList, str_replace(',', '', $igredients['food_name']));
                                                }
                                            }
                                                echo implode(', ', $igredientsList);
                                            ?>
                                        </div>
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
    <?php CVarDumper::dump($foodMenu->monday[0]["meals_component"], 12, true) ?>
</div>

<script>
    function imprimirPagina() {
        // printButton = document.getElementsByClassName('span12');
        // printButton.style.visibility = 'hidden';
        window.print();
        // printButton.style.visibility = 'visible';
    }
</script>