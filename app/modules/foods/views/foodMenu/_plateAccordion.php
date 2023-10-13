<div class="ui-accordion-header js-plate-accordion">
    <?= CHtml::telField('name', '', array(
    'class'=>'t-accordion-input-header js-plate-name',
    'autofocus' => true,
    'placeholder' => 'Digite o nome do prato',
    'data-idAccordion' => $idAccordion))?>
   <label for="<?= $idInputPlate ?>">
    <span class="fa fa-pencil"  id="js-stopPropagation"></span>
    </label>
    <div data-idAccordion='<?= $idAccordion?>' class="row js-ingredients-names">
    </div>
</div>
<div class="ui-accordion-content">
    <div class="row">
        <div class="t-field-select column clearfix">
        <?= CHtml::dropDownList('TACO', '',
        CHtml::listData(Food::model()->findAll(), 'id', 'description'), array(
            'data-idAccordion' => $idAccordion,
            'class' => ' t-field-select__input js-inicializate-select2 js-taco-foods',
            'prompt' => 'Busque pelo Alimento (TACO)',
        ),
        ); ?>
        </div>
    </div>
            <table class="tag-table-secondary centralize js-add-line" data-idAccordion='<?= $idAccordion?>'>
                <tr>
                    <th>Nome</th>
                    <th>unidade</th>
                    <th>Medida</th>
                    <th>Quantidade</th>
                    <th>PT</th>
                    <th>LIP</th>
                    <th>CHO</th>
                    <th>KCAL</th>
                    <th></th>
                </tr>
            </table>
</div>