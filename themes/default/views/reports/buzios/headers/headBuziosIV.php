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
    <ul id="info" style="margin: 30px 0px 0px;">
        <h4 style="margin-top: 20px;">ESTADO DO RIO DE JANEIRO</h4>
        <h4>PREFEITURA DE ARMAÇÃO DOS BÚZIOS</h4>
        <h4>SECRETARIA MUNICIPAL DE EDUCAÇÃO, CIÊNCIA E TECNOLOGIA</h4>
    </ul>
    <span class="clear"></span>
</div>
