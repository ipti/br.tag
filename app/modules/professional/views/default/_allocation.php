<div class="allocation-container">
    <?php if (TagUtils::isAdmin()): ?>
        <div class="modal fade" id="allocation-modal" tabindex="-1" role="dialog" aria-labelledby="allocationModalLabel">
            <div class="modal-dialog" role="document" style="width: 80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="allocationModalLabel">Nova Lotação</h4>
                    </div>
                    <div class="modal-body">
                        <div id="professional-allocation-form">
                            <?php echo CHtml::hiddenField('ProfessionalAllocation[id]', $allocationModel->id, ['id' => 'ProfessionalAllocation_id']); ?>
                            <?php echo CHtml::hiddenField('ProfessionalAllocation[professional_fk]', $allocationModel->professional_fk, ['id' => 'ProfessionalAllocation_professional_fk']); ?>
                            <?php echo CHtml::hiddenField('ProfessionalAllocation[school_year]', Yii::app()->user->year); ?>

                            <div class="row">
                                <div class="column is-full clearleft">
                                    <div class="t-field-select">
                                        <?php echo CHtml::activeLabelEx($allocationModel, 'location_type', ['class' => 't-field-select__label']); ?>
                                        <?php echo CHtml::activeDropDownList(
                                            $allocationModel, 
                                            'location_type', 
                                            ProfessionalAllocation::getLocationTypeOptions(), 
                                            ['class' => 't-field-select__input', 'id' => 'location-type-select']
                                        ); ?>
                                        <?php echo CHtml::error($allocationModel, 'location_type'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="school-selection-row">
                                <div class="column is-full clearleft">
                                    <div class="t-field-select">
                                        <?php echo CHtml::activeLabelEx($allocationModel, 'school_inep_fk', ['class' => 't-field-select__label']); ?>
                                        <?php echo CHtml::activeDropDownList($allocationModel, 'school_inep_fk', $schools, ['prompt' => 'Selecione a Escola', 'class' => 't-field-select__input']); ?>
                                        <?php echo CHtml::error($allocationModel, 'school_inep_fk'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="location-name-row" style="display: none;">
                                <div class="column is-full clearleft">
                                    <div class="t-field-text">
                                        <?php echo CHtml::activeLabelEx($allocationModel, 'location_name', ['class' => 't-field-text__label']); ?>
                                        <?php echo CHtml::activeTextField(
                                            $allocationModel, 
                                            'location_name', 
                                            ['class' => 't-field-text__input', 'placeholder' => 'Ex: Secretaria Municipal de Educação']
                                        ); ?>
                                        <?php echo CHtml::error($allocationModel, 'location_name'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="column is-full clearleft">
                                    <div class="t-field-select">
                                        <?php echo CHtml::activeLabelEx($allocationModel, 'role', ['class' => 't-field-select__label']); ?>
                                        <?php echo CHtml::activeDropDownList($allocationModel, 'role', ProfessionalAllocation::getRoleOptions(), ['prompt' => 'Selecione o Cargo', 'class' => 't-field-select__input']); ?>
                                        <?php echo CHtml::error($allocationModel, 'role'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column is-full clearleft">
                                    <div class="t-field-select">
                                        <?php echo CHtml::activeLabelEx($allocationModel, 'contract_type', ['class' => 't-field-select__label']); ?>
                                        <?php echo CHtml::activeDropDownList($allocationModel, 'contract_type', ProfessionalAllocation::getContractTypeOptions(), ['prompt' => 'Selecione o Contrato', 'class' => 't-field-select__input']); ?>
                                        <?php echo CHtml::error($allocationModel, 'contract_type'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column is-one-quarter clearleft">
                                    <div class="t-field-text">
                                        <?php echo CHtml::activeLabelEx($allocationModel, 'workload', ['class' => 't-field-text__label']); ?>
                                        <?php echo CHtml::activeTextField($allocationModel, 'workload', ['class' => 't-field-text__input', 'placeholder' => 'Horas/semana']); ?>
                                        <?php echo CHtml::error($allocationModel, 'workload'); ?>
                                    </div>
                                </div>
                                <div class="column is-one-quarter">
                                    <div class="t-field-select">
                                        <?php echo CHtml::activeLabelEx($allocationModel, 'status', ['class' => 't-field-select__label']); ?>
                                        <?php echo CHtml::activeDropDownList($allocationModel, 'status', ProfessionalAllocation::getStatusOptions(), ['class' => 't-field-select__input']); ?>
                                        <?php echo CHtml::error($allocationModel, 'status'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-secondary" id="btn-cancel-allocation" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="t-button-primary" id="btn-save-allocation">Salvar Lotação</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="widget-header align-items--center justify-content--space-between">
        <h3>Lotações Registradas</h3>
        <?php if (TagUtils::isAdmin()): ?>
            <button type="button" id="btn-nova-lotacao" class="t-button-primary">
                + Nova Lotação
            </button>
        <?php endif; ?>
    </div>
    <script>
        var ProfessionalAllocationConfig = {
            saveUrl: '<?php echo Yii::app()->createUrl("professional/default/saveAllocation"); ?>',
            deleteUrl: '<?php echo Yii::app()->createUrl("professional/default/deleteAllocation"); ?>',
            viewUrl: '<?php echo Yii::app()->createUrl("professional/default/viewAllocation"); ?>'
        };
    </script>
    <div class="table-responsive" style="width: 100%;">
        <?php
        DataTableGridView::show($this, [
            'id' => 'professional-allocation-grid',
            'dataProvider' => $allocationProvider,
            'htmlOptions' => ['style' => 'width: 100%;'],
            'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
            'columns' => [
                [
                    'header' => 'Cargo/Função',
                    'value' => 'ProfessionalAllocation::getRoleOptions()[$data->role] ?? "Não definido"',
                ],
                [
                    'header' => 'Tipo de Contrato',
                    'value' => 'ProfessionalAllocation::getContractTypeOptions()[$data->contract_type] ?? "Não definido"',
                ],
                [
                    'header' => 'Carga Horária',
                    'value' => '$data->workload . "h"',
                ],
                [
                    'header' => 'Situação',
                    'value' => 'ProfessionalAllocation::getStatusOptions()[$data->status] ?? "Indefinido"',
                ],
                [
                    'header' => 'Local',
                    'value' => '$data->getLocationDisplay()',
                ],
                [
                    'header' => 'Ano',
                    'value' => '$data->school_year',
                ],
                [
                    'header' => 'Ações',
                    'class' => 'CButtonColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => [
                            'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                            'options' => ['class' => 'btn-edit-allocation', 'title' => 'Editar'],
                            'url' => '$data->id', // Passamos apenas o ID, o JS previne o link
                        ],
                        'delete' => [
                            'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                            'options' => ['class' => 'btn-delete-allocation', 'title' => 'Excluir'],
                            'url' => '$data->id',
                        ]
                    ],
                    'updateButtonOptions' => ['class' => 'btn-edit-allocation', 'style' => 'margin-right: 10px;'],
                    'deleteButtonOptions' => ['class' => 'btn-delete-allocation'],
                    'headerHtmlOptions' => ['style' => 'width: 100px; text-align: center;'],
                    'htmlOptions' => ['style' => 'text-align: center;'],
                    'visible' => TagUtils::isAdmin(),
                ],
            ],
        ]);
        ?>
    </div>
</div>
