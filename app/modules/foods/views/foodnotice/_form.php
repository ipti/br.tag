<?php
/* @var $this FoodNoticeController */
/* @var $model FoodNotice */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '\notice\functions.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '\notice\validations.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '\notice\_initialization.js', CClientScript::POS_END);

$form = $this->beginWidget(
    'CActiveForm',
    array(
        'id' => 'food-notice-form',
        'enableAjaxValidation' => false,
    )
);
?>

<div class="form">

    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom"><?php echo $title; ?></h1>
            <p></p>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <a title="Save Notice Button" class="t-button-primary column js-submit">
                <?= $model->isNewRecord ? 'Criar Edital' : 'Salvar Edital' ?>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="column clearfix">
            <div id="info-alert" class="alert hide"></div>
        </div>
    </div>
    <div class="row">
        <h3 class="column clearleft">
            Informações do Edital
        </h3>
    </div>
    <div id="loading-popup" class="hide">
        <img class="js-grades-loading" height="60px" width="60px" src="/themes/default/img/loadingTag.gif" alt="TAG Loading">
    </div>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="t-field-text column is-two-fifths clearleft">
            <label for="menu_description" class="t-field-text__label--required">Nome</label>
            <input type="text" name="Nome" class="t-field-text__input js-notice-name" required="required">
        </div>
        <div class="t-field-text column is-two-fifths clearleft--on-mobile">
            <label for="menu_start_date" class="t-field-text__label--required">Data Inicial</label>
            <input type="text" name="Data Inicial" class="t-field-text__input js-date date" readonly
            required="required">
        </div>
    </div>
    <div class="row">
        <div class="column clearleft">
            <div class="row t-buttons-container">
                <label for="notice_pdf" class="t-field-file__label t-button-secondary">Anexar PDF</label>
                <a title="" id="js-view-pdf" class="t-button-secondary <?= $model->isNewRecord ? 'hide' : '' ?>">Visualizar PDF</a>
            </div>
            <input type="file" id="notice_pdf" name="notice_pdf" accept=".pdf" class="t-field-file__input js-notice_pdf">
            <span class="uploaded-notice-name"><?php echo $model->file_name !== null ? $model->file_name : '' ?></span>
        </div>
    </div>
    <div class="row">
        <h3 class="column clearleft">
            Itens do Edital
        </h3>
    </div>
    <div id="shopping-list">
        <div class="row">
            <div class="t-field-select column clearleft">
                <p class="t-field-select__label">Deseja preencher os itens do edital com os itens da lista de compras ?</p>
            </div>
        </div>
        <div class="row">
            <div class="column clearfix is-one-tenth">
                <a title="Import Shopping List" class="js-add-shopping-list t-button-primary">Sim</a>
            </div>
            <div class="column clearleft is-one-tenth">
                <a title="Not Import Shopping List" class="js-not-add-shopping-list t-button-secondary">Não</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="t-field-select column is-two-fifths clearleft">
            <label for="item_food" class="t-field-select__label--required">Alimento</label>
            <select id="item_food" class="t-field-select__input js-initialize-select2 js-taco-foods">
                <option value="">Selecione um alimento</option>
            </select>
        </div>
        <div class="t-field-text column is-two-fifths clearleft--on-mobile">
            <label for="notice_year_amount" class="t-field-text__label--required">Quantidade Anual</label>
            <input type="text" id="notice_year_amount" name="Nome" class="t-field-text__input js-notice-year-amount" placeholder="Informe a quantidade anual">
        </div>
    </div>
    <div class="row">
        <div class="t-field-tarea column is-two-fifths clearleft">
            <label for="item_description" class="t-field-tarea__label">Descrição</label>
            <textarea id="item_description" class="t-field-tarea__input js-item-description" placeholder="Informe a descrição"></textarea>
        </div>
        <div class="t-field-text column is-two-fifths clearleft--on-mobile">
            <label for="item_measurement" class="t-field-select__label--required">Unidade</label>
            <select id="item_measurement" class="t-field-select__input js-initialize-select2 js-item-measurement">
                <option value="">Selecione uma opção</option>
                <option value="Kg">Quilograma</option>
                <option value="Und">Unidade</option>
                <option value="Ml">Mililitros</option>
                <option value="L">Litros</option>
            </select>
        </div>
    </div>

    <div class="row">
        <a class="t-button-primary column js-add-notice-item">
            Adicionar Item
        </a>
    </div>
    <div class="row">
        <div class="column clearfix">
            <table aria-label="Tabela de items" class="tag-table-primary table js-datatable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Quantidade</th>
                        <th>Unidade</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row show--tablet">
        <div class="column clearfix">
            <div class="t-buttons-container">
                <a title="Save Notice Button" class="t-button-primary column js-submit">
                    <?= $model->isNewRecord ? 'Criar Edital' : 'Salvar Edital' ?>
                </a>
            </div>
        </div>
    </div>

    <div class="modal fade t-modal-container larger" id="js-edit-food-items" tabindex="-1">
        <div class="modal-dialog ">
            <div class="t-modal__header">
                <h4 class="t-title" id="myModalLabel">Editar item do edital</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="">
                </button>
            </div>
            <form method="post">
                <div class="t-modal__body">
                    <div class="row">
                        <div class="column clearfix">
                            <div id="modal-food-alert" class="alert hide"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="t-field-select column is-half clearleft">
                            <label for="item_measurement" class="t-field-select__label--required">Alimento</label>
                            <select id="edit_item_food" class="t-field-select__input js-initialize-select2 js-taco-foods">
                                <option value="">Selecione um alimento</option>
                            </select>
                        </div>
                        <div class="t-field-text column is-half clearleft--on-mobile">
                            <label for="notice_year_amount" class="t-field-text__label">Quantidade Anual</label>
                            <input type="text" id="edit_notice_year_amount" name="Nome" class="t-field-text__input js-notice-year-amount" placeholder="Informe a quantidade">
                        </div>
                    </div>
                    <div class="row">
                        <div class="t-field-tarea column is-half clearleft">
                            <label for="item_description" class="t-field-tarea__label">Descrição</label>
                            <textarea id="edit_item_description" class="t-field-tarea__input js-item-description" placeholder="Informe a descrição"></textarea>
                        </div>
                        <div class="t-field-text column is-half clearleft--on-mobile">
                            <label for="item_measurement" class="t-field-select__label">Unidade</label>
                            <select id="edit_item_measurement" class="t-field-select__input js-initialize-select2 js-item-measurement">
                                <option value="">Selecione uma opção</option>
                                <option value="Kg">Quilograma</option>
                                <option value="Und">Unidade</option>
                                <option value="Ml">Mililitros</option>
                                <option value="L">Litros</option>
                            </select>
                        </div>
                    </div>

                    <div class="t-modal__footer row reverse">
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-primary clear-margin--right" id="edit-foods">Editar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
