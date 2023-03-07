<div class="span12" style="height: 63px; margin-left: 3px">
    <h3 class="heading-mosaic">Cadastrar Unidade</h3>
    <span class="subtitle">Campos com * são obrigatórios.
    </span>
</div>

<div class="tag-inner">
    <div class="row-fluid">
        <div class=" span6">
            <div class="separator"></div>
            <div class="separator"></div>
            <div class="separator"></div>
            <div class="form">
                <?php $form = $this->beginWidget(
                    'CActiveForm',
                    array(
                        'id' => 'provision-accounts-form',
                        'enableAjaxValidation' => false,
                    )
                ); ?>

                <?php echo $form->errorSummary($model); ?>

                <div class="control-group">
                    <div class="controls">
                        <?php echo $form->labelEx($model, 'codigoUnidGestora'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $form->textField($model, 'codigoUnidGestora', array('size' => 30, 'maxlength' => 30)); ?>
                        <?php echo $form->error($model, 'codigoUnidGestora'); ?>
                    </div>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'nomeUnidGestora'); ?>
                    <?php echo $form->textField($model, 'nomeUnidGestora', array('size' => 150, 'maxlength' => 150)); ?>
                    <?php echo $form->error($model, 'nomeUnidGestora'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'cpfResponsavel'); ?>
                    <?php echo $form->textField($model, 'cpfResponsavel', array('size' => 16, 'maxlength' => 16)); ?>
                    <?php echo $form->error($model, 'cpfResponsavel'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'cpfGestor'); ?>
                    <?php echo $form->textField($model, 'cpfGestor', array('size' => 16, 'maxlength' => 16)); ?>
                    <?php echo $form->error($model, 'cpfGestor'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'anoReferencia'); ?>
                    <?php echo $form->textField($model, 'anoReferencia'); ?>
                    <?php echo $form->error($model, 'anoReferencia'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'mesReferencia'); ?>
                    <?php echo $form->textField($model, 'mesReferencia'); ?>
                    <?php echo $form->error($model, 'mesReferencia'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'versaoxml'); ?>
                    <?php echo $form->textField($model, 'versaoxml'); ?>
                    <?php echo $form->error($model, 'versaoxml'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'diaInicPresContas'); ?>
                    <?php echo $form->textField($model, 'diaInicPresContas'); ?>
                    <?php echo $form->error($model, 'diaInicPresContas'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'diaFinaPresContas'); ?>
                    <?php echo $form->textField($model, 'diaFinaPresContas'); ?>
                    <?php echo $form->error($model, 'diaFinaPresContas'); ?>
                </div>

                <div class="row buttons">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Cadastrar' : 'Salvar'); ?>
                </div>
                <?php $this->endWidget(); ?>
                
            </div><!-- form -->
        </div>
    </div>
</div>