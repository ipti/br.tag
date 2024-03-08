<?php
/* @var $this FoodNoticeController */
/* @var $model FoodNotice */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '\notice\functions.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '\notice\_initialization.js', CClientScript::POS_END);
?>

<div class="main form-content form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'food-notice-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    )); ?>
    <div class="row">
        <h1 class="column clearleft">
            <?php echo $title; ?>
        </h1>
    </div>
    <div class="t-tabs row">
        <div class="column clearleft">
            <ul class="tab-instructor t-tabs__list ">
                <li class="active t-tabs__item"><a data-toggle="tab" class="t-tabs__link" style="padding: 0;">
                        <span class="t-tabs__numeration">1</span>
                        <?= $model->isNewRecord ? 'Criar Cardápio' : 'Salvar Cardápio' ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <h3 class="column clearleft">
            Informações do Edital
        </h3>
    </div>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="t-field-text column clearleft">
            <label for="menu_description" class="t-field-text__label--required">Nome</label>
            <input type="text" name="Nome" class="t-field-text__input" required="required">
        </div>
        <div class="t-field-text column">
            <label for="menu_start_date" class="t-field-text__label--required">Data Inicial</label>
            <input type="text" name="Data Inicial" class="t-field-text__input js-date date" readonly required="required">
        </div>
    </div>
    <div class="row">
        <h3 class="column clearleft">
            Itens do Edital
        </h3>
    </div>
    <div class="row">
        <div class="t-field-text column clearleft">
            <label for="notice_item_name" class="t-field-text__label">Nome do Item</label>
            <input type="text" id="notice_item_name" name="Nome" class="t-field-text__input js-notice-item-name">
        </div>
        <div class="t-field-text column clearleft">
            <label for="notice_year_amount" class="t-field-text__label">Quantidade Anual</label>
            <input type="text" id="notice_year_amount" name="Nome" class="t-field-text__input js-notice-year-amount">
        </div>
    </div>
    <div class="row">
        <div class="t-field-tarea column clearleft">
            <label for="item_description" class="t-field-tarea__label">Descrição</label>
            <textarea id="item_description" class="t-field-tarea__input js-item-description" placeholder="Digite">
            </textarea>
        </div>
        <div class="t-field-text column">
            <label for="item_measurement" class="t-field-select__label">Unidade</label>
            <select id="item_measurement" class="t-field-select__input js-initialize-select2 js-item-measurement">
                <option value="">Selecione uma opção</option>
                <option value="KG">Kilograma</option>
                <option value="UND">Unidade</option>
                <option value="Maço">Maço</option>
            </select>
        </div>
    </div>
    <div class="row">
        <a class="t-button-secondary column js-add-notice-item">
            Adicionar Item
        </a>
    </div>
        <table class="tag-table-primary table js-datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Item 1</td>
                    <td>2024-03-07</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Item 2</td>
                    <td>2024-03-08</td>
                </tr>
                <!-- Adicione mais linhas conforme necessário -->
            </tbody>
        </table>

    <div class="row buttons">
        <a class="t-button-primary column">
            <?= $model->isNewRecord ? 'Criar Edital' : 'Salvar Edital' ?>
        </a>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<style>
    .date[readonly] {
        cursor: pointer;
        background-color: white;
    }

    textarea {
        height: 100px;
    }
</style>
