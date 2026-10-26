<?php
/* @var $this ClassFaultsController */
/* @var $model ClassFaults */

$this->setPageTitle('TAG - Atualizar Falta');
?>

<div id="mainPage" class="main">
    <h1>Atualizar Falta #<?php echo CHtml::encode($model->id); ?></h1>
    <?php echo $this->renderPartial('_form', ['model' => $model]); ?>
</div>
