<?php
    /** @var DefaultController $this DefaultController */
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Diário de Classe'));
    $this->breadcrumbs=array(
        $this->module->id,
    );
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'classDiary',
        'enableAjaxValidation' => false,
    ));
?>
<div class="main">
    <h1>Diário de Classe</h1>
    <div class="row">
        <div class="column">
            <div></div>
            <button></button>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>