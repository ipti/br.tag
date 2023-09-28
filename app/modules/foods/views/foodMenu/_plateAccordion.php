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
    <table>
        
    </table>
</div>