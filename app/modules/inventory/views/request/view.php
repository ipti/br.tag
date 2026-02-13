<?php
/* @var $this RequestController */
/* @var $model InventoryRequest */

$this->setPageTitle('TAG - Detalhes da Solicitação');
$this->breadcrumbs=array(
	'Almoxarifado' => array('movement/index'),
	'Solicitações' => array('index'),
	'Detalhes',
);

$isAdmin = TagUtils::isAdmin();
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Detalhes da Solicitação #<?php echo $model->id; ?></h1>
            <p>Informações detalhadas sobre o pedido de item.</p>
        </div>
    </div>

    <div class="row t-buttons-container">
        <?php if ($isAdmin): ?>
            <?php echo CHtml::link('Voltar ao Gerenciamento', array('admin'), array('class'=>'t-button-secondary')); ?>
            <?php echo CHtml::link('Editar Solicitação', array('update', 'id'=>$model->id), array('class'=>'t-button-primary')); ?>
        <?php else: ?>
            <?php echo CHtml::link('Solicitações da Escola', array('index'), array('class'=>'t-button-secondary')); ?>
            <?php if ($model->status == InventoryRequest::STATUS_PENDING): ?>
                <?php echo CHtml::link('Editar Solicitação', array('update', 'id'=>$model->id), array('class'=>'t-button-primary')); ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="column is-one-half">
            <h2 class="t-title-small">Informações</h2>
            <?php $this->widget('zii.widgets.CDetailView', array(
                'data'=>$model,
                'attributes'=>array(
                    array(
                        'label' => 'Escola',
                        'value' => $model->school->name,
                    ),
                    array(
                        'label' => 'Item',
                        'value' => $model->item->name,
                    ),
                    array(
                        'label' => 'Quantidade',
                        'value' => $model->quantity . " " . $model->item->unit,
                    ),
                    array(
                        'label' => 'Solicitante',
                        'value' => $model->user->name,
                    ),
                    array(
                        'label' => 'Status',
                        'value' => $model->getStatusText(),
                    ),
                    'justification',
                    'observation',
                    array(
                        'label' => 'Data da Solicitação',
                        'value' => date("d/m/Y H:i", strtotime($model->requested_at)),
                    ),
                ),
            )); ?>
        </div>

        <?php if ($isAdmin && $model->status == InventoryRequest::STATUS_PENDING): ?>
        <div class="column is-one-half">
            <h2 class="t-title-small">Ações da Secretaria</h2>
            <div class="form">
                <?php echo CHtml::beginForm(); ?>
                <div class="row">
                    <div class="column is-full t-field-text clearfix">
                        <?php echo CHtml::label('Observação / Motivo', 'observation', ['class' => 't-field-text__label']); ?>
                        <?php echo CHtml::textArea('observation', '', array('rows'=>4, 'class' => 't-field-text__input')); ?>
                    </div>
                </div>
                <div class="row t-buttons-container" style="margin-top: 20px;">
                    <?php echo CHtml::submitButton('Aprovar Solicitação', array('submit' => array('approve', 'id'=>$model->id), 'class' => 't-button-primary')); ?>
                    <?php echo CHtml::submitButton('Rejeitar Solicitação', array('submit' => array('reject', 'id'=>$model->id), 'class' => 't-button-tertiary', 'style' => 'background-color: #D21C1C;')); ?>
                </div>
                <?php echo CHtml::endForm(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
