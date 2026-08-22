<?php

$this->setPageTitle('TAG - ' . Yii::t('default', 'Merenda'));

$this->menu = [
    ['label' => 'List FoodMenu', 'url' => ['index']],
    ['label' => 'Inventory', 'url' => ['inventory']],
];

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/mealsOfWeek/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);

$isNutritionist = Yii::app()->getAuthManager()->checkAccess('nutritionist', Yii::app()->user->loginInfos->id);

?>
<div id="mainPage" class="main">
    <div class="row">
        <div class="column clearfix">
            <h1>
                <?php echo Yii::t('default', 'Lunch') ?>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="t-badge-info t-margin-none--left">
            <span class="t-info_positive"></span>
            Alunos Manhã: <?php echo $studentsByTurn['Manhã'] ?>
        </div>
        <div class="t-badge-info t-margin-none--left">
            <span class="t-info_positive"></span>
            Alunos Tarde: <?php echo $studentsByTurn['Tarde'] ?>
        </div>
        <div class="t-badge-info t-margin-none--left">
            <span class="t-info_positive"></span>
            Alunos Noite: <?php echo $studentsByTurn['Noite'] ?>
        </div>
        <div class="t-badge-info t-margin-none--left">
            <span class="t-info_positive"></span>
            Alunos Integral: <?php echo $studentsByTurn['Integral'] ?>
        </div>
    </div>
    <?php $studentsByAllergyPresent = array_filter($studentsByAllergy ?? []); ?>
    <div class="t-buttons-container">
        <a class="t-button-primary"  href="<?php echo yii::app()->createUrl('foods/foodmenu/index') ?>">
            Preparar Cardápio
        </a>
        <div class="mobile-row">
            <a class="t-button-secondary" style="margin-right:10px;" href="<?php echo yii::app()->createUrl('foods/foodinventory') ?>">
                Estoque
            </a>
            <a class="t-button-secondary" style="margin-right:10px;" href="<?php echo yii::app()->createUrl('foods/foodRequest') ?>">
                Solicitações
            </a>
            <a class="t-button-secondary" style="margin-right:10px;" href="<?php echo yii::app()->createUrl('foods/farmerregister') ?>">
                Agricultor
            </a>
            <a class="t-button-secondary" style="margin-right:10px;" href="<?php echo yii::app()->createUrl('foods/foodnotice') ?>">
                Editais
            </a>
            <a class="t-button-secondary js-expansive-panel show--mobile">
                Filtros
            </a>
        </div>
    </div>
    <div class="row t-expansive-panel expanded">
        <div class="t-field-select column t-margin-none--left t-multiselect">
            <label class="t-field-select__label">Mostrar turnos</label>
            <select class="select-search-on t-field-select__input js-filter-turns multiselect" multiple="multiple" name='Turno' required='required'>
                <option value="M">Manhã</option>
                <option value="T">Tarde</option>
                <option value="N">Noite</option>
            </select>
		</div>
        <div class="t-field-select column clearleft--on-mobile t-multiselect">
            <label class="t-field-select__label">Filtrar etapa de ensino</label>
            <?= CHtml::dropDownList('stages', [], CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), [
                'multiple' => 'multiple', 'class' => 'select-search-on control-input multiselect js-filter-public-target select3-choices'
            ]) ?>
		</div>
        <div Class="column show--desktop"></div>
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
                <li class="t-tabs__item js-day-tab js-change-pagination" data-day-of-week="6">
                    Sábado
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="js-cards-meals column t-margin-none--left is-half">

        </div>
        <?php if (!empty($studentsByAllergyPresent)): ?>
            <div class="column is-half">
                <div class="t-cards t-cards--warning">
                    <div class="t-cards-content">
                        <h2 class="t-cards-title">Alunos com Alergia Alimentar</h2>
                        <p class="t-cards-text clear-margin--left">
                            <?php echo $totalAllergicStudents; ?>
                            <?php echo $totalAllergicStudents === 1 ? 'aluno matriculado possui' : 'alunos matriculados possuem'; ?>
                            alergia alimentar registrada.
                        </p>
                        <?php foreach ($studentsByAllergyPresent as $allergyLabel => $studentsCount): ?>
                            <p class="t-cards-text clear-margin--left">
                                <b><?php echo CHtml::encode($allergyLabel); ?>:</b> <?php echo $studentsCount; ?>
                            </p>
                        <?php endforeach; ?>
                        <div class="t-cards-link">
                            <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('foods/foodmenu/studentAllergies'); ?>">
                                Ver alunos e alergias
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
