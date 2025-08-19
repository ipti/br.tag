<div class="ui-accordion-header">
    <?= $plan_name ?>
</div>
<div class="ui-accordion-content">
        <div class="t-field-tarea">
            <?= CHtml::label('Objetivo', 'objective', array('class' => 't-field-tarea__label')) ?>
            <?= CHtml::textArea('objective', '', array('id' => 'objective', 'class' => 't-field-tarea__input')) ?>
        </div>
        <div class="t-multiselect">
            <?= CHtml::label('Tipo', 'type', array('class' => 't-field-select__label')) ?>
            <?= CHtml::dropDownList('type', '', [], array('multiple' => 'multiple', 'class' => 'select-search-on t-multiselect multiselect', 'id' => 'type',)) ?>
        </div>
        <div class="t-field-select">
            <?= CHtml::label('Recursos', 'resources', array('class' => 't-field-select__label clear-margin--all')) ?>
            <div class="mobile-row">
                <div class="column clearfix is-four-fifths--mobile">
                    <?php echo CHtml::dropDownList('resources', '',  [], array('class' => 'select-search-on t-field-select__input full--width clear-margin--all', 'id' => 'resources')); ?>
                </div>
                <div class="column full--height is-one-tenth--mobile">
                    <input type="number" class="full clear-margin--bottom" name="amount" value="1" max="999">
                </div>
                <div class="column clear-margin--right is-one-tenth--mobile">
                    <button class="t-button-primary full clear-margin--all align-items--center clear-margin"><span class="fa fa-plus-square"></span></button>
                </div>
            </div>
        </div>
</div>