<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>


<?php

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/instance-config.js', CClientScript::POS_END);

$cs->registerCssFile($baseUrl . 'sass/css/main.css');

$this->setPageTitle('TAG - Configurações Gerais');
?>

<?php //echo $form->errorSummary($model);
?>

<div class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1>Configurações Gerais</h1>
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

        <div class="parameter-structure-container">
            <div class="success-configs alert alert-success no-show"></div>

            <div class="row-fluid">
                <?php foreach ($configs as $config): ?>
                <div class="parameter-row">
                    <input type="hidden" class="parameter-id" value="<?= $config->id ?>">
                    <label class="control-label parameter-name"><h2><?= $config->parameter_name ?></h2></label>
                    <input type="text" class="control-input parameter-value" value="<?= $config->value ?>">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>