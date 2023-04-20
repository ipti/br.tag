<?php
/* @var $this ReportsController
@var $school SchoolIdentification
 */
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
//$school = new SchoolIdentification;
?>
<style>
    #info li {text-align:center;}
    #addinfo li{text-align: center}
</style>
<h3 class="heading visible-print"><?php echo @$title ?></h3>
<div id="header-report">
    <?php
    if(isset($school->logo_file_name)){
        echo '<img id="logo" src="data:'.$school->logo_file_type.';base64,'.base64_encode($school->logo_file_content).'">';
    };
    ?>
    <ul id="info" style="margin: 30px 0;">
        <h4>PREFEITURA MUNICIPAL DE ARMAÇÃO DOS BÚZIOS</h4>
        <h5>Secretaria Municipal de Educação, Ciência e Tecnologia</h5>
        <h5>Coordenação da Unidade Escolar</h5>
        <h5><?php echo $school->name ?></h5>
    </ul>
    <span class="clear"></span>
</div>
