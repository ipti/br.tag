<?php
/* @var $menuModel Menu
 * @var $form CActiveForm
 * @var $isUpdate Boolean
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'Menu',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    ),
));
?>

    <div class="row-fluid">
        <div class="widget widget-scroll margin-bottom-none">
            <div class="widget-head">
                <h4 class="heading glyphicons notes">
                    <i></i><?= yii::t('lunchModule.lunch', 'Menu Info') ?>
                </h4>
            </div>
            <div class="widget-body in" style="height: auto;">
                <div class="row-fluid">
                    <div class="span12">
                        <?= CHTML::activeLabel($menuModel, 'date', ['class' => "control-label"]) ?>
                        <div class="form-group ">
                            <?php $date = 'date'; ?>
                            <?= CHTML::textField("none", date("d/m/Y", strtotime($menuModel->date)), ['class' => "span10 form-control", "readonly" => "readonly"]) ?>
                            <?= CHTML::hiddenField(chtml::resolveName($menuModel, $date), $menuModel->date, ['class' => "span10 form-control", "readonly" => "readonly"]) ?>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row-fluid">
                    <div class="span12">
                        <?= CHTML::activeLabel($menuModel, 'name', ['class' => "control-label"]) ?>
                        <div class="form-group ">
                            <?php $name = 'name'; ?>
                            <?= CHTML::textField(chtml::resolveName($menuModel, $name), $menuModel->name, ['class' => "span10 form-control"]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row-fluid">
        <div class="widget widget-scroll margin-bottom-none">
            <div class="widget-head">
                <h4 class="heading glyphicons notes">
                    <i></i><?= yii::t('lunchModule.lunch', 'Meals') ?>
                </h4>
            </div>
            <div class="widget-body in" style="height: auto;">
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table table-bordered">
                            <thead>
                            <tr role="row">
                                <th class="span1">Código</th>
                                <th class="span3">Restrições</th>
                                <th>Composição</th>
                                <th class="span1">Quantidade</th>
                                <th class="span1">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $odd = false;
                            foreach ($menuModel->menuMeals as $menuMeal):
                                $meal = $menuMeal->meal;
                                $odd = !$odd;
                                ?>
                                <tr class="<?= $odd ? "odd" : "" ?>">
                                    <td>REF<?= $meal->id ?></td>
                                    <td><?= $meal->restrictions ?></td>
                                    <td>
                                        <table class="table table-clear">
                                            <tbody>
                                            <?php foreach($meal->mealPortions as $mealPortion):
                                                $portion = $mealPortion->portion?>
                                                <tr class="<?= $odd ? "odd" : "" ?>">
                                                    <td><?= $portion->item->name ?></td>
                                                    <td class="span1"><?= (floatval($portion->amount)*floatval($portion->measure))." ".$portion->unity->acronym ?></td>
                                                    <td class="span1 text-right">
                                                        <a data-toggle="modal" href="#removePortion" class="btn btn-danger btn-mini">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="text-center"><?= $menuMeal->amount ?></td>
                                    <td class="text-center">
                                        <a data-toggle="modal" href="#addPortion" class="btn btn-success btn-small">
                                            <i class="fa fa-plus-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $this->endWidget() ?>