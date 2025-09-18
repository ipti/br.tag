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

<?php //echo $form->errorSummary($model);
?>

<div class="main">
    <div class="row-fluid">
        <div class="span12">
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
        <div class="parameter-structure-container">
            <div class="row-fluid">
                <?php foreach ($configs as $config): ?>
                    <div class="parameter-row">
                        <input type="hidden" class="parameter-id" value="<?= $config->id ?>">
                        <label class="control-label parameter-name">
                            <h2><?= $config->parameter_name ?></h2>
                        </label>

                        <label>
                            <input type="radio"
                                name="parameter_value_<?= $config->id ?>"
                                class="control-input parameter-value"
                                value="1"
                                <?= $config->value == 1 ? 'checked' : '' ?>>
                            Ativo
                        </label>

                        <label>
                            <input type="radio"
                                name="parameter_value_<?= $config->id ?>"
                                class="control-input parameter-value"
                                value="0"
                                <?= $config->value == 0 ? 'checked' : '' ?>>
                            Inativo
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
