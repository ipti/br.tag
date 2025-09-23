<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>


<?php

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/manage-modules.js?v=' . TAG_VERSION, CClientScript::POS_END);

$cs->registerCssFile($baseUrl . 'sass/css/main.css');

$this->setPageTitle('TAG - Gerenciar Módulos');

?>

<div class="main">
    <div class="row-fluid">
        <div class="row">
            <h1>Gerenciar Módulos</h1>
            <div class="buttons">
                <a class='btn btn-icon btn-primary last save'>Salvar</a>
                <div class="save-config-loading-gif no-show">
                    <i class="fa fa-spin fa-spinner fa-3x" style="margin-right: 23px; margin-top: 5px;"></i>
                </div>
            </div>

        </div>
    </div>

    <div class="tag-inner">
        <div class="row">
            <h3>Configurações Gerais</h3>
        </div>
        <div class="success-configs alert alert-success no-show"></div>
        <div class="parameter-structure-container ">
            <div class="row wrap">
                <?php foreach ($configs as $config): ?>
                    <div class="t-cards parameter-row" style="max-width: 300px; height: 240px;">
                        <input type="hidden" class="parameter-id" value="<?= $config->feature_name ?>">
                        <div class="t-cards-content" style="gap: 0px;">
                            <div class="parameter-name">
                                <h2 class="t-cards-title"><?= $config->featureName->description ?></h2>
                                <p class="t-cards-text clear-margin--left"><?= $config->featureName->name ?></p>
                            </div>

                            <div class="t-field-radio">
                                <input type="radio"
                                    id="parameter_value_<?= $config->feature_name ?>_enabled"
                                    name="parameter_value_<?= $config->feature_name ?>"
                                    class="t-field-radio__input parameter-value"
                                    value="1"
                                    <?= $config->active == 1 ? 'checked' : '' ?>>
                                <label class="t-field-radio__label" for="parameter_value_<?= $config->feature_name ?>_enabled">
                                    Ativo
                                </label>
                            </div>

                            <div class="t-field-radio">
                                <input type="radio"
                                    id="parameter_value_<?= $config->feature_name ?>_disabled"
                                    name="parameter_value_<?= $config->feature_name ?>"
                                    class="t-field-radio__input parameter-value"
                                    value="0"
                                    <?= $config->active == 0 ? 'checked' : '' ?>>
                                <label class="t-field-radio__label" for="parameter_value_<?= $config->feature_name ?>_disabled">
                                    Inativo
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
