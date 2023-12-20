<?php

// $baseUrl = Yii::app()->baseUrl;
// $cs = Yii::app()->getClientScript();
// $cs->registerScriptFile($baseUrl . "/");

$this->setPageTitle('TAG - ' . Yii::t('default', 'Merenda'));

$this->menu = array(
    array('label' => 'List FoodMenu', 'url' => array('index')),
    array('label' => 'Inventory', 'url' => array('inventory')),
);

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/mealsOfWeek/_initialization.js', CClientScript::POS_END);

?>

<div id="viewMealsPage" class="main container-instructor">
    <div class="row-fluid">
        <div class="span12">
            <h1>
                <?php echo Yii::t('default', 'Lunch') ?>
            </h1>
        </div>
    </div>
</div>

<div id="mainPage" class="main container-instructor">
    <div class="row">
        <a class="t-button-primary" href="<?php echo yii::app()->createUrl('foods/foodmenu/') ?>"> 
            Preparar Cardápio
        </a>
        <a class="t-button-secondary">
            Estoque
        </a>
    </div>
    <div class="row">
        <div class="t-field-select column t-multiselect">
            <label class="t-field-select__label--required">Mostrar turnos</label>
            <select class="select-search-on t-field-select__input js-filter-turns multiselect" multiple="multiple" name='Turno' required='required'>
                <option value="M">Manhã</option>
                <option value="T">Tarde</option>
                <option value="N">Noite</option>
            </select>
		</div>
        <div class="t-field-select column t-multiselect">
            <label class="t-field-select__label--required">Filtrar tipo de aluno</label>
            <?= CHtml::dropDownList("stages", [], CHtml::listData(FoodPublicTarget::model()->findAll(), "id", "name"), [
                "multiple" => "multiple", "class" => "select-search-on control-input multiselect js-filter-public-target select3-choices"
            ]) ?>
		</div>
    </div>
    <div class="row days-of-week row">
        <div class="t-tabs-secondary">
            <ul class="t-tabs__list column">
                <li class="t-tabs__item js-day-tab js-change-pagination active" data-day-of-week="1">
                    Segunda-feira
                </li>
                <li class="t-tabs__item js-day-tab js-change-pagination" data-day-of-week="2">
                    Terça-feira
                </li>
                <li class="t-tabs__item js-day-tab js-change-pagination" data-day-of-week="3">
                    Quarta-feira
                </li>
                <li class="t-tabs__item js-day-tab js-change-pagination" data-day-of-week="4">
                    Quinta-feira
                </li>
                <li class="t-tabs__item js-day-tab js-change-pagination" data-day-of-week="5">
                    Sexta-feira
                </li>
            </ul>
        </div>
    </div>
    <div class="js-cards-meals">
        
    </div>
</div>