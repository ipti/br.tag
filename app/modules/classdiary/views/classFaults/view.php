<?php
/* @var $this ClassFaultsController */
/* @var $model ClassFaults */

$this->setPageTitle('TAG - Detalhes da Falta');
?>

<div id="mainPage" class="main">
    <h1>Detalhes da Falta #<?php echo CHtml::encode($model->id); ?></h1>

    <div class="row t-buttons-container">
        <?php echo CHtml::link('Editar', ['update', 'id' => $model->id], ['class' => 't-button-secondary']); ?>
        <?php echo CHtml::link('Voltar', ['index'], ['class' => 't-button-secondary']); ?>
    </div>

    <div class="row">
        <div class="column is-full">
            <?php $this->widget('zii.widgets.CDetailView', [
                'data' => $model,
                'attributes' => [
                    'id',
                    'schedule_fk',
                    'student_fk',
                    'justification',
                    'created_at',
                    'updated_at',
                ],
            ]); ?>
        </div>
    </div>
</div>
