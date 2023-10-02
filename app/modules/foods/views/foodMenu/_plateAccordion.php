<?php
    $idInputPlate = 'name-plate-'. $idAccordion;
?>
<div class="ui-accordion-header js-plate-accordion">
    <?= CHtml::telField('name', '', array(
    'class'=>'t-accordion-input-header',
    'autofocus' => true,
    'placeholder' => 'Digite o nome do prato',
    'id' => $idInputPlate))?>
   <label for="<?= $idInputPlate ?>">
    <span class="fa fa-pencil"  id="js-stopPropagation"></span>
    </label>
   
</div>
<div class="ui-accordion-content">
    <div class="row">
        <div class="t-search column clearfix">
            <?= CHtml::textField('TACO','',array(
        'class' => 't-search-input js-inicializate-select2',
        'placeholder' => 'Busque pelo Alimento (TACO)'));
    ?>
		  <!--  <input type="text" class="t-search-input" placeholder="Busque pelo Alimento (TACO)"> -->
           <span class="t-icon-search_icon t-search-icon"></span>
        </div>
    </div>
    <table>
        <tr>
            <th>Nome</th>
            <th>Medida</th>
            <th>Quantidade</th>
            <th>PT</th>
            <th>LIP</th>
            <th>CHO</th>
            <th>KCAL</th>
        </tr>
        <tr>
            <td> ARROZ </td>
            <td>
                <div class="row">
                <?= CHtml::telField('name', '',
                    array('class'=>'column is-one-quarter t-field-text__input clear-margin--right')) ?>
                <?= CHtml::telField('name', '', array('class'=>'column t-field-text__input')) ?>
                </div>
            </td>
            <td>
                24g
            </td>
            <td>
                1g
            </td>
            <td>
                1g
            </td>
            <td>
                1g
            </td>
            <td>
                1g
            </td>
        </tr>
    </table>
</div>