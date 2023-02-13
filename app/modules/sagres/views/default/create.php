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
                        <?php echo $form->labelEx($model, 'codigounidgestora'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $form->textField($model, 'codigounidgestora', array('size' => 30, 'maxlength' => 30)); ?>
                        <?php echo $form->error($model, 'codigounidgestora'); ?>
                    </div>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'nomeunidgestora'); ?>
                    <?php echo $form->textField($model, 'nomeunidgestora', array('size' => 150, 'maxlength' => 150)); ?>
                    <?php echo $form->error($model, 'nomeunidgestora'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'cpfcontador'); ?>
                    <?php echo $form->textField($model, 'cpfcontador', array('size' => 16, 'maxlength' => 16)); ?>
                    <?php echo $form->error($model, 'cpfcontador'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'cpfgestor'); ?>
                    <?php echo $form->textField($model, 'cpfgestor', array('size' => 16, 'maxlength' => 16)); ?>
                    <?php echo $form->error($model, 'cpfgestor'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'anoreferencia'); ?>
                    <?php echo $form->textField($model, 'anoreferencia'); ?>
                    <?php echo $form->error($model, 'anoreferencia'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'mesreferencia'); ?>
                    <?php echo $form->textField($model, 'mesreferencia'); ?>
                    <?php echo $form->error($model, 'mesreferencia'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'versaoxml'); ?>
                    <?php echo $form->textField($model, 'versaoxml'); ?>
                    <?php echo $form->error($model, 'versaoxml'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'diainicprescontas'); ?>
                    <?php echo $form->textField($model, 'diainicprescontas'); ?>
                    <?php echo $form->error($model, 'diainicprescontas'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'diafinaprescontas'); ?>
                    <?php echo $form->textField($model, 'diafinaprescontas'); ?>
                    <?php echo $form->error($model, 'diafinaprescontas'); ?>
                </div>

                <div class="row buttons">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Cadastrar' : 'Salvar'); ?>
                </div>
                <?php $this->endWidget(); ?>
                
            </div><!-- form -->
        </div>
    </div>
</div>