<?php
/* @var $menuModel Menu
 * @var $meals Meals
 * @var $form CActiveForm
 * @var $isUpdate Boolean
 */

Yii::import('application.modules.foods.models.Food', true);
Yii::import('application.modules.foods.models.FoodMeasurement', true);
Yii::import('application.modules.lunch.controllers.LunchController', true);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.0');
$cs->registerScriptFile($baseScriptUrl . '/common/js/lunch.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerCssFile($baseUrl . '/css/lunch.css');

$form = $this->beginWidget('CActiveForm', [
    'id' => 'Menu',
    'enableClientValidation' => true,
    'clientOptions' => [
        'validateOnSubmit' => true,
    ],
    'htmlOptions' => [
        'class' => 'form-horizontal',
    ],
]);
?>

<?php if (Yii::app()->user->hasFlash('success')) : ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        </div>
    </div>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')) : ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="alert alert-error">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
        </div>
    </div>
<?php endif ?>

<div class="row">
    <div class="column is-two-fifths">
        <div class="t-field-text">
            <?= CHTML::activeLabel($menuModel, 'date', ['class' => 'control-label t-field-text__label']) ?>

            <?php $date = 'date';
?>
            <?= CHTML::textField(
    chtml::resolveName($menuModel, $date),
    date('d/m/Y', strtotime($menuModel->date)),
    ['class' => 'form-control t-field-text____input']
) ?>

        </div>
        <div class="t-field-text">
            <?= CHTML::activeLabel($menuModel, 'name', ['class' => 't-field-text__label']) ?>
            <?php $name = 'name'; ?>
            <?= CHTML::textField(
                chtml::resolveName($menuModel, $name),
                $menuModel->name,
                ['class' => 't-field-text__input', 'placeholder' => 'Digite o Nome do Cardápio']
            ) ?>

        </div>
        <div class="t-field-select">
            <?= CHTML::activeLabel($menuModel, 'turn', ['class' => 't-field-select__label'])?>
            <?php $turn = 'turn'; ?>
            <?= CHtml::dropDownList(
                chtml::resolveName($menuModel, $turn),
                $menuModel->turn,
                ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite'],
                ['class' => 'select-search-on t-field-select__input']
            )?>
        </div>

        <div class="form-group-container">
            <div class="t-buttons-container">
                <button type="submit" class="t-button-primary">
                    <?= Yii::t('lunchModule.lunch', 'Save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget() ?>
<br>
<?php if ($isUpdate) : ?>
    <div class="row">
        <div class="column">
            <div class="t-buttons-container">
                <button class="t-button-danger button-remove-lunch" data-lunch-id="<?= $menuModel->id ?>">
                    <?= Yii::t('lunchModule.lunch', 'Remove'); ?>
                </button>
            </div>
            <div class="form-group-container" style="margin-bottom: 20px;">
                <h4 class="heading glyphicons notes">
                    <i></i><?= yii::t('lunchModule.lunch', 'Meals') ?>
                </h4>
            </div>
            <div class="form-group-container" style="width: 98%;">
            <div class="column">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr role="row">
                                <th class="span1">Código</th>
                                <th class="span4">Restrições</th>
                                <th>Porções</th>
                                <th class="span1">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $odd = false;
    foreach ($meals as $meal) :
        $portions = $meal['portions'];
        $meal = $meal['meal'];
        $odd = !$odd;
        ?>
                                <tr class="<?= $odd ? 'odd' : '' ?>">
                                    <td id="id">REF<?= $meal->id ?></td>
                                    <td id="restrictions" style="word-wrap: break-word; max-width: 600px;}"><?= $meal->restrictions ?></td>
                                    <td id="portions">
                                        <table class="table table-clear">
                                            <tbody>
                                                <?php
                            $lunchController = new LunchController(null);

        foreach ($portions as $portion) :
            $foodModel = Food::model()->findByPk($portion->food_fk);
            $foodAlias = $lunchController->actionGetFoodAlias();
            $food = array_filter(
                $foodAlias,
                function ($f) use ($foodModel) {return $f->id == $foodModel->id; }
            );
            $foodUnity = FoodMeasurement::model()->findByPk($portion->unity_fk);
            $food = array_pop($food); ?>
                                                    <tr class="<?= $odd ? 'odd' : '' ?>">
                                                        <td><?= $food->description ?></td>
                                                        <td class="span2"><?= (floatval($portion->amount) . ' ' . $foodUnity->unit . ' x ' .
                floatval($foodUnity->value)) . ' ' .
                $foodUnity->measure ?></td>
                                                        <td class="span1 text-right">
                                                            <a
                                                                href="#removePortion"
                                                                data-meal-portion-id="<?= $portion->id ?>"
                                                                data-menu-id="<?= $menuModel->id ?>"
                                                                class="button-remove-portion btn btn-danger btn-mini hidden-print"
                                                            >
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                                <tr>
                                                    <td colspan="3" class="<?= $odd ? 'odd' : '' ?>">
                                                        <a data-toggle="modal" href="#addPortion" data-meal-id="<?= $meal->id ?>" class="pull-left button-add-portion btn btn-success btn-small hidden-print">
                                                            <i class="fa fa-plus-circle"></i>
                                                            <?= Yii::t('lunchModule.stock', 'Add Portion'); ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td id="actions" class="text-center">
                                        <a data-toggle="modal" href="#changeMeal" data-meal-id="<?= $meal->id ?>" class="button-change-meal btn btn-primary btn-small hidden-print">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
                <button data-toggle="modal" href="#addMeal" class="button-add-meal pull-right btn btn-success ">
                    <i class="fa fa-plus-circle"></i>
                    <?= Yii::t('lunchModule.lunch', 'New Lunch'); ?>
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade modal-content" id="addPortion">
        <div class="modal-dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title"><?= Yii::t('lunchModule.stock', 'Add Portion'); ?></h4>
            </div>
            <form id="portion" method="post" action="<?= yii::app()->createUrl('lunch/lunch/addPortion') ?>">
                <?= CHtml::hiddenField('Menu[id]', $menuModel->id, ['id' => 'menu-id']) ?>
                <?= CHtml::hiddenField('MealPortion[meal_fk]', '', ['id' => 'meal-id']) ?>
                <div class="modal-body">
                    <div id="is-add-amount" class="widget widget-scroll margin-bottom-none">
                        <div class="widget-head" style="background: none;">
                            <h4 class="heading">
                                <i></i><?= yii::t('lunchModule.stock', 'Add Amount') ?>
                            </h4>
                        </div>
                        <div class="widget-body">
                            <div class="row-fluid row">
                                <div class=" span8">
                                    <?= CHtml::label(Yii::t('lunchModule.labels', 'Portion'), 'MealPortion[food_fk]', ['class' => 'control-label']); ?>
                                    <div class="controls">
                                        <?php
                                            $lunchController = new LunchController(null);
    $lunchController->init();
    echo CHtml::dropDownList(
        'MealPortion[food_fk]',
        '',
        CHtml::listData($lunchController->actionGetFoodAlias(), 'id', 'description'),
    );
    ?>
                                    </div>

                                    <?= CHtml::label(Yii::t('lunchModule.labels', 'Unity'), 'MealPortion[unity_fk]', ['class' => 'control-label']); ?>
                                    <div class="controls">
                                        <?php
        echo CHtml::dropDownList(
        'MealPortion[unity_fk]',
        '',
        CHtml::listData($lunchController->actionGetFoodMeasurement(), 'id', 'unit'),
        ['id' => 'unityDropdown']
    );
    ?>
                                    </div>
                                </div>
                                <div class="row" style="flex-direction: column;">
                                    <div class="column" style="width: 23%;">
                                        <?= CHtml::label(Yii::t('lunchModule.labels', 'Amount'), 'MealPortion[amount]', ['class' => 'control-label', 'style' => 'width: 100%']); ?>
                                        <div class="controls span12">
                                            <?= CHtml::numberField(
        'MealPortion[amount]',
        '1',
        ['min' => '0', 'step' => '1', 'class' => 'span10',
            'style' => 'height:44px;width:100%;',
            'id' => 'foodAmount']
    );
    ?>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <?= CHtml::label(Yii::t('lunchModule.labels', 'Measure'), 'MealPortion[measure]', ['class' => 'control-label', 'style' => 'width: 100%']); ?>
                                        <?= CHtml::hiddenField('MealPortion[measure]', '1', [
                                            'class' => 'hide',
                                            'id' => 'measureInput'
                                        ]); ?>
                                        <div id="lunchUnityMeasure"><span></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('lunchModule.stock', 'Close'); ?></button>
                        <button type="submit" class="btn btn-primary"><?= Yii::t('lunchModule.stock', 'Add'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade modal-content" id="removePortion">
        <div class="modal-dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title"><?= Yii::t('lunchModule.stock', 'Remove Portion'); ?></h4>
            </div>
            <form id="portion" method="post" action="<?= yii::app()->createUrl('lunch/lunch/removePortion') ?>">
                <?= CHtml::hiddenField('Menu[id]', $menuModel->id, ['id' => 'menu-id']) ?>
                <?= CHtml::hiddenField('MealPortion[id]', '', ['id' => 'meal-portion-id']) ?>
                <div class="modal-body">
                    <div id="is-add-amount" class="widget widget-scroll margin-bottom-none">
                        <div class="widget-head" style="background: none;">
                            <h4 class="heading">
                                <i></i><?= yii::t('lunchModule.stock', 'Remove Amount') ?>
                            </h4>
                        </div>
                        <div class="widget-body" style="overflow: hidden;">
                            <div class="row-fluid">
                                <div class=" span6" style="width: 75%;margin-right:22px;">
                                    <?= CHtml::label(Yii::t('lunchModule.labels', 'Portion'), 'MealPortion[portion_fk]', ['class' => 'control-label']); ?>
                                    <div class="controls span12">
                                        <?= CHtml::dropDownList(
                                            'MealPortion[portion_fk]',
                                            '',
                                            CHtml::listData(Portion::model()->findAll(), 'id', 'concatName'),
                                            ['disabled' => 'true', 'class' => 'span10', 'style' => 'width: 100%']
                                        ); ?>
                                    </div>
                                </div>
                                <div class=" span6" style="width: 20%;">
                                    <?= CHtml::label(Yii::t('lunchModule.labels', 'Amount'), 'MealPortion[amount]', ['class' => 'control-label']); ?>
                                    <div class="controls span12">
                                        <?= CHtml::numberField('MealPortion[amount]', '1', ['min' => '1', 'step' => '1', 'class' => 'span10', 'style' => 'height:44px; width:99%;']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('lunchModule.stock', 'Close'); ?></button>
                        <button type="submit" class="btn btn-danger"><?= Yii::t('lunchModule.stock', 'Remove'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade modal-content" id="addMeal">
        <div class="modal-dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title"><?= Yii::t('lunchModule.lunch', 'Add Lunch'); ?></h4>
            </div>
            <form id="portion" method="post" action="<?= yii::app()->createUrl('lunch/lunch/addMeal') ?>">
                <?= CHtml::hiddenField('MenuMeal[menu_fk]', $menuModel->id, ['id' => 'menu-id']) ?>
                <div class="modal-body">
                    <div id="is-add-amount" class="widget widget-scroll margin-bottom-none">
                        <h4 class="heading">
                            <i></i><?= yii::t('lunchModule.lunch', 'New Lunch') ?>
                        </h4>
                        <div class="widget-body" style="overflow: hidden;">
                            <div class="row-fluid">
                                <div class=" span6" style="width: 75%;margin-right:22px;">
                                    <?= CHtml::label(Yii::t('lunchModule.labels', 'Restrictions'), 'Meal[restrictions]', ['class' => 'control-label']); ?>
                                    <div class="controls span12" style="margin-left: 0;">
                                        <?= CHtml::textArea('Meal[restrictions]', '', ['style' => 'resize: vertical;', 'class' => 'span10', 'style' => 'height:44px;width: 100%']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <?= Yii::t('lunchModule.stock', 'Close'); ?>
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <?= Yii::t('lunchModule.stock', 'Add'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade modal-content" id="changeMeal">
        <div class="modal-dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title"><?= Yii::t('lunchModule.lunch', 'Update Lunch'); ?></h4>
            </div>
            <form id="portion" method="post" action="<?= yii::app()->createUrl('lunch/lunch/changeMeal') ?>">
                <?= CHtml::hiddenField('MenuMeal[menu_fk]', $menuModel->id, ['id' => 'menu-id']) ?>
                <?= CHtml::hiddenField('MenuMeal[meal_fk]', '', ['id' => 'meal-id']) ?>
                <div class="modal-body">
                    <div id="is-add-amount" class="widget widget-scroll margin-bottom-none">
                        <div class="widget-head" style="background: none;">
                            <h4 class="heading">
                                <i></i><?= yii::t('lunchModule.lunch', 'Lunch Info') ?>
                            </h4>
                        </div>
                        <div class="widget-body" style="overflow: hidden;">
                            <div class="row-fluid">
                                <div class=" span6" style="width: 75%;margin-right:22px;">
                                    <?= CHtml::label(Yii::t('lunchModule.labels', 'Restrictions'), 'Meal[restrictions]', ['class' => 'control-label']); ?>
                                    <div class="controls span12" style="margin-left: 0;">
                                        <?= CHtml::textArea('Meal[restrictions]', '', ['style' => 'resize: vertical;', 'class' => 'span10', 'style' => 'height:44px;width: 100%']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <?= Yii::t('lunchModule.lunch', 'Close'); ?>
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <?= Yii::t('lunchModule.lunch', 'Update'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif ?>
