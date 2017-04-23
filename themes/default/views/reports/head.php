<?php
/* @var $this ReportsController
    @var $school SchoolIdentification
 */
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
//$school = new SchoolIdentification;
?>
<h3 class="heading visible-print"><?php echo @$title ?></h3>
<div id="header-report">
    <img src="<?php echo Yii::app()->controller->createUrl('school/displayLogo', array('id' => $school->inep_id));?>" id="logo"/>
    <ul id="info">
        <li>ESTADO DE <?php echo strtoupper($school->edcensoUfFk->name) ?></li>
        <li>PREFEITURA MUNICIPAL DE <?php echo $school->edcensoCityFk->name ?></li>
        <li><?php echo $school->name ?></li>
    </ul>
    <ul id="addinfo">
        <li><?php echo $school->address.', '.(!empty($school->address_number) ? $school->address_number.', ':'' ).$school->address_neighborhood; ?></li>
        <li><?php echo $school->cep.' - '.$school->edcensoCityFk->name . " - " . $school->edcensoUfFk->acronym ?></li>
        <?php echo 'Email: '.(!empty($school->email) ? $school->email.' - ': (!empty($school->manager_email) ? $school->manager_email.' - ':'' ) ).'Tel: '.(!empty($school->phone_number) ? $school->phone_number:'' )?>
    </ul>
    <span class="clear"></span>

</div>
