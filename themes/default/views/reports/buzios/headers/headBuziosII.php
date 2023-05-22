<?php
/* @var $this ReportsController
@var $school SchoolIdentification
 */
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>
<style>
    #info li {text-align:center;}
    #addinfo li{text-align: center}
</style>
<h3 class="heading visible-print"><?php echo @$title ?></h3>
<div id="header-report">
    <?php
    if(isset($school->logo_file_name)){
        echo '<img id="logo" src="data:'.
        $school->logo_file_type.';base64,'.
        base64_encode($school->logo_file_content).'">';
    };
    ?>
    <ul id="info" style="padding: 25px 0;">
        <h4 style="color:#628dc3 !important;font-weight: 500;">SECRETARIA MUNICIPAL DE EDUCAÇÃO, CIÊNCIA E TECNOLOGIA</h4>
        <h4 style="color:#628dc3 !important;font-weight: 500;">COORDENAÇÃO DA UNIDADE EDUCACIONAL</h4>
    </ul>
    <span class="clear"></span>
</div>
