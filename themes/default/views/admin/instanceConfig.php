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
                <a class='btn btn-icon btn-primary last'>Salvar</a>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div class="error-configs alert alert-error no-show"></div>

        <div class="parameter-structure-container">
            <div class="row-fluid">
                <?php foreach ($configs as $config): ?>
                <div class="parameter-row">
                    <input type="hidden" class="parameter-id" value="<?= $config->id ?>">
                    <div class="span12">
                        <label class="control-label parameter-name"><h2><?= $config->parameter_name ?></h2></label>
                        <input type="text" class="control-input parameter-value" value="<?= $config->value ?>">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="save-unity-loading-gif">
            <i class="fa fa-spin fa-spinner fa-3x"></i>
        </div>

    </div>
</div>