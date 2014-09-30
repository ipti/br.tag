<?php
/* @var $this ReportsController */
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
//$school = new SchoolIdentification;
?>
<div id="report-logo" class="visible-print">
    <img src="../../../images/sntaluzia.png">
</div>
<h3 class="heading visible-print"><?php echo Yii::t('default', 'Result Board'); ?></h3> 
<table class="table table-bordered table-striped">
    <tr>
        <th>Escola:</th><td colspan="7"><?php echo $school->inep_id . " - " . $school->name ?></td>
    <tr>
    <tr>
        <th>Estado:</th><td colspan="2"><?php echo $school->edcensoUfFk->name . " - " . $school->edcensoUfFk->acronym ?></td>
        <th>Municipio:</th><td colspan="2"><?php echo $school->edcensoCityFk->name ?></td>
        <th>Endereço:</th><td colspan="2"><?php echo $school->address ?></td>
    <tr>
    <tr>
        <th>Localização:</th><td colspan="2"><?php echo ($school->location == 1 ? "URBANA" : "RURAL") ?></td>
        <th>Dependência Administrativa:</th><td colspan="4"><?php
            $ad = $school->administrative_dependence;
            echo ($ad == 1 ? "FEDERAL" :
                    ($ad == 2 ? "ESTADUAL" :
                            ($ad == 3 ? "MUNICIPAL" :
                                    "PRIVADA" )));
            ?></td>
    <tr>
</table>
<br>