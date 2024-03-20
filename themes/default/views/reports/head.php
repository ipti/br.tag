<?php
/* @var $this ReportsController
@var $school SchoolIdentification
 */

if(!isset($school)){
    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
}

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
        <?php if (isset($school->act_of_acknowledgement) && !empty($school->act_of_acknowledgement)) { ?>
            <li><?php echo $school->name ?></li>
        <?php } else { ?>
            <li>
                <?php if (TagUtils::isInstance("POCODANTAS")) { ?>
                    SECRETÁRIA MUNICIPAL DE EDUCAÇÃO DE POÇO DANTAS
                <?php } else { ?>
                    PREFEITURA MUNICIPAL DE <?php echo $school->edcensoCityFk->name ?>
                <?php } ?>
            </li>
            <li><?php echo $school->name ?></li>
        <?php } ?>
    </ul>

    <ul id="addinfo">

        <?php
            $cep = $school->cep;

            if (empty($cep)){
                $fieldCep = '';
            } else if (ctype_digit($cep) && strlen($cep) === 8) {
                $formatted_cep = substr($cep, 0, 2) . '.' . substr($cep, 2, 3) . '-' . substr($cep, 5, 3);
                $fieldCep = ', CEP: '. $formatted_cep;
            } else {
                $fieldCep = '';
            }
        ?>

        <li>
            <?php
                echo $school->address.', '.(!empty($school->address_number) ? $school->address_number.', ':'' ).$school->address_neighborhood;
            ?>,
            <?php
            echo $school->edcensoCityFk->name . " - " . $school->edcensoUfFk->acronym . $fieldCep ?> </li>
        <li><?php echo $school->act_of_acknowledgement ?></li>
        <!--<?php echo 'Email: '.(!empty($school->email) ? $school->email.' - ': (!empty($school->manager_email) ? $school->manager_email.' - ':'' ) ).'Tel: '.(!empty($school->phone_number) ? $school->phone_number:'' )?>-->
    </ul>
    <span class="clear"></span>

</div>
