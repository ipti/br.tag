<?php
/* @var $this ClassFaultsController */
/* @var $model ClassFaults */

$this->setPageTitle('TAG - Administrar Faltas');
?>

<div id="mainPage" class="main">
    <h1>Administrar Faltas</h1>

    <div class="row t-buttons-container">
        <?php echo CHtml::link('Nova Falta', ['create'], ['class' => 't-button-primary']); ?>
        <?php echo CHtml::link('Voltar', ['index'], ['class' => 't-button-secondary']); ?>
    </div>

    <div class="row">
        <div class="column is-full">
            <?php $this->widget('zii.widgets.grid.CGridView', [
                'id' => 'class-faults-grid',
                'dataProvider' => $model->search(),
                'filter' => $model,
                'columns' => [
                    'id',
                    'schedule_fk',
                    'student_fk',
                    'justification',
                    [
                        'class' => 'CButtonColumn',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
