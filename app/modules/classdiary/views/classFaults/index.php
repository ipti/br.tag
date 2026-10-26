<?php
/* @var $this ClassFaultsController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - Faltas');
?>

<div id="mainPage" class="main">
    <h1>Faltas</h1>

    <div class="row t-buttons-container">
        <?php echo CHtml::link('Nova Falta', ['create'], ['class' => 't-button-primary']); ?>
        <?php echo CHtml::link('Administrar', ['admin'], ['class' => 't-button-secondary']); ?>
    </div>

    <div class="row">
        <div class="column is-full">
            <?php $this->widget('zii.widgets.CListView', [
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
            ]); ?>
        </div>
    </div>
</div>
