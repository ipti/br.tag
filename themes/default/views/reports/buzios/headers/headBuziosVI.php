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
            echo '<img style="" id="logo" src="data:'.
            $school->logo_file_type . ';base64,'.
            base64_encode($school->logo_file_content).'">';
        };
        ?>
        <h4>PREFEITURA MUNICIPAL DE ARMAÇÃO DOS BÚZIOS</h4>
        <h4>Secretaria Municipal de Educação, Ciência e Tecnologia</h4>
        <h4>Coordenação da Unidade Educacional</h4>
        <h4><?php echo "UNIDADE ESCOLAR: ".$school->name?></h4>
    </ul>
    <span class="clear"></span>
</div>