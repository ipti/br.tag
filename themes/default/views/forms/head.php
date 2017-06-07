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
    <ul id="info">
        <?php if(isset($school->act_of_acknowledgement)&&(!empty($school->act_of_acknowledgement))){?>
            <li><?php echo $school->name ?></li>
        <?php }else{?>
            <li>PREFEITURA MUNICIPAL DE <?php echo $school->edcensoCityFk->name ?></li>
            <li><?php echo $school->name ?></li>
        <?php }?>
    </ul>
    <ul id="addinfo">
        <li><?php echo $school->address.', '.(!empty($school->address_number) ? $school->address_number.', ':'' ).$school->address_neighborhood; ?>, <?php echo $school->edcensoCityFk->name . " - " . $school->edcensoUfFk->acronym ?> </li>
        <li><?php echo $school->act_of_acknowledgement ?></li>
        <!--<?php echo 'Email: '.(!empty($school->email) ? $school->email.' - ': (!empty($school->manager_email) ? $school->manager_email.' - ':'' ) ).'Tel: '.(!empty($school->phone_number) ? $school->phone_number:'' )?>-->
    </ul>
    <span class="clear"></span>

</div>
