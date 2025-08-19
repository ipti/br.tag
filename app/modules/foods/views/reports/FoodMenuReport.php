<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/sass/css/main.css');

$days = ["monday", "tuesday", "wednesday", "thursday", "friday"];

if($include_saturday){
    array_push($days, "saturday");
}
?>
<style>
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
    <h2 id="title-page">
        SECRETARIA MUNICIPAL DE EDUCAÇÃO <br>
        DEPARTAMENTO DA ALIMENTAÇÃO ESCOLAR - DAE
    </h2>
    <div class="row t-margin-medium--top">
        <table aria-describedby="title-page" class="column table table-bordered">
            <thead class="background-dark font-bold">
                <tr>
                    <th id="title" colspan="<?= $include_saturday ? "7" : "6" ?>" class="text-align--center">
                        <div>SECRETARIA MUNICIPAL DE
                            <?= $schoolCity ?>
                        </div>
                        <br />
                        <div>PROGRAMA NACIONAL DE ALIMENTAÇÃO ESCOLAR - PNAE</div>
                    </th>
                </tr>
                <tr>
                    <th id="food-menu-infos" colspan="<?= $include_saturday ? "7" : "6" ?>" class="text-align--center">
                        <div>
                            <?= $foodMenu->description . " - " . $publicTarget["name"] ?>
                        </div>
                        <div class="t-margin-medium--top">
                            <?php
                                foreach ($stagesNames as $name) {
                                   echo $name. "<br>";
                                }
                            ?>
                        </div>
                        <br>
                        <div>
                            <?php switch ($schoolLocation) {
                                case 1:
                                    $localizacao = 'URBANO';
                                    break;
                                case 2:
                                    $localizacao = 'RURAL';
                                    break;
                                default:
                                    $localizacao = 'DESCONHECIDO';
                                    break;
                            }
                            echo $localizacao;
                            ?>
                        </div>
                        <?php
                        switch ($foodMenu->week) {
                            case 1:
                                $week = '1° SEMANA';
                                break;
                            case 2:
                                $week = '2° SEMANA';
                                break;
                            case 3:
                                $week = '3° SEMANA';
                                break;
                            case 4:
                                $week = '4° SEMANA';
                                break;
                            default:
                                $week = '';
                                break;
                        }
                        echo $week;
                        ?>
                        <br>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="font-bold">
                    <td class="background-dark">&nbsp;</td>
                    <td>
                        2ª FEIRA
                    </td>
                    <td>3ª FEIRA</td>
                    <td>4ª FEIRA</td>
                    <td>5ª FEIRA</td>
                    <td>6ª FEIRA</td>
                    <?php if($include_saturday): ?>
                        <td>SÁBADO</td>
                    <?php endif ?>
                </tr>
                <?php foreach ($mealTypes as $mealType) : ?>
                    <tr>
                        <td class="background-dark font-bold nowrap">
                            <?= $mealType["description"] ?><br />
                            <?= $mealType["turn"] ?><br />
                            <?= $mealType["meal_time"] ?>
                        </td>
                        <?php foreach ($days as $day) : ?>
                            <td>
                                <?php foreach ($foodMenu->$day as $meal) : ?>
                                    <?php if ($meal["foodMealType"] == $mealType["food_meal_type"]) : ?>
                                        <div>
                                            <?php
                                            $descriptions = array_reduce($meal["mealsComponent"], function ($carry, $item) {
                                                // Adiciona a descrição do item ao resultado acumulado
                                                $carry[] = $item['description'];
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
                                            foreach ($meal["mealsComponent"] as $mealComponent) {
                                                foreach ($mealComponent["ingredients"] as $igredients) {
                                                    array_push($igredientsList, str_replace(',', '', $igredients['foodName']));
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
                <tr class="font-bold">
                    <td colspan="2" rowspan="4">
                        Composição Nutricional <br>
                        (Média semanal)
                    </td>
                    <td rowspan="2">
                        Energia <br>
                        (Kcal)
                    </td>
                    <td>
                        CHO(g)
                    </td>
                    <td>
                        PTN(g)
                    </td>
                    <td colspan="<?= $include_saturday ? "2" : "1" ?>">
                        LPD(g)
                    </td>
                </tr>
                <tr class="font-bold">
                    <td class="nowrap">55% a %65 do VET</td>
                    <td class="nowrap">10% a 15% do VET</td>
                    <?php if ($publicTarget["id"] != 7) : ?>
                        <td class="nowrap" colspan="<?= $include_saturday ? "2" : "1" ?>">25% a 35% do VET</td>
                    <?php else : ?>
                        <td class="nowrap" colspan="<?= $include_saturday ? "2" : "1" ?>">15% a 30% do VET</td>
                    <?php endif; ?>

                </tr>
                <tr>
                    <td rowspan="2">
                        <?= $nutritionalValue["kcalAverage"] . 'kcal' ?>
                    </td>
                    <td>
                        <?= $nutritionalValue["calpct"] . "%" ?>
                    </td>
                    <td>
                        <?= $nutritionalValue["ptnpct"] . "%" ?>
                    </td>
                    <td colspan="<?= $include_saturday ? "2" : "1" ?>">
                        <?= $nutritionalValue["lpdpct"] . "%" ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $nutritionalValue["calAverage"] . "g" ?>
                    </td>
                    <td>
                        <?= $nutritionalValue["ptnAvarage"] . "g" ?>
                    </td>
                    <td colspan="<?= $include_saturday ? "2" : "1" ?>">
                        <?= $nutritionalValue["lpdAvarage"] . "g" ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    function imprimirPagina() {
        window.print();
    }
</script>
