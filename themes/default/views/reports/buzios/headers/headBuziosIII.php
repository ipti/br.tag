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
            echo '<img id="logo" style="margin-inline: 45%;" src="data:'.
            $school->logo_file_type . ';base64,'.
            base64_encode($school->logo_file_content).'">';
        };
        ?>
        <h5 style="text-align: center;">___________________________________________________</h5>
        <h4 style="margin-bottom: 10px; margin-top: 20px">SECRETARIA MUNICIPAL DE EDUCAÇÃO,<br> CIÊNCIA E TECNOLOGIA</h4>
        <h5 style="text-align: center;">___________________________________________________</h5>
        <h4 style="margin-top: 10px">COORDENAÇÃO DA UNIDADE EDUCACIONAL</h4>
    </ul>
    <span class="clear"></span>
</div>