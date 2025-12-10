<div class="tab-pane active" id="professional-identify">
    <div><h3><?= CHtml::encode('Dados Básicos') ?></h3></div>

    <div class="row">
        <div class="column clearleft">

            <?= CHtml::activeHiddenField($modelProfessional, 'id'); ?>

            <div class="row">
                <div class="column t-field-text">
                    <?= $form->label($modelProfessional, 'name', ['class' => 't-field-text__label--required']); ?>
                    <?= $form->textField($modelProfessional, 'name', [
                        'size' => 100,
                        'maxlength' => 200, // compatível com VARCHAR(200)
                        'class' => 't-field-text__input',
                        'placeholder' => 'Nome completo'
                    ]); ?>
                    <?= $form->error($modelProfessional, 'name'); ?>
                </div>

                <div class="column t-field-text">
                    <?= $form->label($modelProfessional, 'cpf', ['class' => 't-field-text__label--required']); ?>
                    <?= $form->textField($modelProfessional, 'cpf', [
                        'size' => 14,
                        'maxlength' => 14, // compatível com VARCHAR(14)
                        'class' => 't-field-text__input cpf-input',
                        'placeholder' => '000.000.000-00'
                    ]); ?>
                    <?= $form->error($modelProfessional, 'cpf'); ?>
                </div>
            </div>

            <div class="row">
                <div class="column t-multiselect t-field-select">
                    <?php
                    // echo CHtml::label('Especialidades', 'Professional_specialtyIds', ['class' => 'control-label']);
                    // echo $form->listBox(
                    //     $modelProfessional,
                    //     'specialtyIds',
                    //     CHtml::listData($specialities, 'id', 'name'),
                    //     [
                    //         'multiple' => 'multiple',
                    //         'id' => 'Professional_specialtyIds',
                    //         'class' => 't-field-select__input select-search-on multiselect select3-choices',
                    //         // 'size' => 6,
                    //     ]
                    // );
                    // echo $form->error($modelProfessional, 'specialtyIds');
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>
