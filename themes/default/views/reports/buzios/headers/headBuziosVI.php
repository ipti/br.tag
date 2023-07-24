<?php
/* @var $this ReportsController
@var $school SchoolIdentification
 */
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>
<style>
    #info li {
        text-align: center;
    }

    #addinfo li {
        text-align: center
    }
</style>
<h3 class="heading visible-print"><?php echo @$title ?></h3>
<div id="header-report">
    <ul id="info" style="margin: 30px 0;">
        <?php
        if (isset($school->logo_file_name)) {
            echo '<img style="margin-left: 5%;position: absolute;" id="logo" src="data:'.
            $school->logo_file_type . ';base64,'.
            base64_encode($school->logo_file_content).'">';
        };
        ?>
        <h3 style="text-align:center;">PREFEITURA MUNICIPAL DE ARMAÇÃO DOS BÚZIOS</h3>
        <h4 style="text-align:center;">Secretaria Municipal de Educação, Ciência e Tecnologia</h4>
        <h4 style="text-align:center;">Coordenação da Unidade Educacional</h4>
        <h4 style="text-align:center;"><?php echo "UNIDADE ESCOLAR: ".$school->name?></h4>
    </ul>
    <span class="clear"></span>
</div>