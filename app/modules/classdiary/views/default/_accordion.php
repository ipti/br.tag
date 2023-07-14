
    <div class="ui-accordion-header">
    <?= $plan_name ?>
    </div>
    <div class="ui-accordion-content">
        <div>
            <div class="t-field-tarea">
                <?= CHtml::label('Objetivo', 'objective', array('class'=> 't-field-tarea__label'))?>
                <?= CHtml::textArea('objective', '', array('id'=>'objective', 'class'=>'t-field-tarea__input'))?>
            </div>
            <div class="t-multiselect">
                <?= CHtml::label('Tipo', 'type', array('class'=>'t-field-select__label')) ?>
                <?= CHtml::dropDownList('type','', [], array('multiple' => 'multiple', 'class'=>'select-search-on t-multiselect multiselect', 'id'=>'type', ))?>
            </div>
            <div class="t-field-select">
            <?= CHtml::label('Recursos', 'resources', array('class'=>'t-field-select__label')) ?>
            <?php echo CHtml::dropDownList('resources', '',  [], array('class' => 'select-search-on t-field-select__input', 'id' => 'resources')); ?> 
            <input type="number" name="amount" step="1" min="1" value="1" max="999">
            </div>
        </div>
    </div>
