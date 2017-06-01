<?php
$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) . $DS . "register.php");

class ClassroomValidation extends Register{
    function __construct() {
    }

    //campos 4, 5
    function checkLength($value, $max){
        if (trim($value) === '' || !isset($value)) {
            return array('status' => false, 'erro' => 'Valor eh nulo');
        }
        if (strlen($value) > $max) {
            return array('status' => false, 'erro' => 'Tamanho eh maior que o maximo permitido');
        }
        return array('status' => true, 'erro' => '');
    }

    //campo 5
    function isValidClassroomName($name){
        $regex = "/^[ 0-9A-Zªº-]+$/";
        $length = $this->checkLength($name, 80);
        if (!$length['status']){
            return array('status' => false, 'erro' => $length['erro']);
        }
        if (!preg_match($regex, $name)) {
            return array('status' => false, 'erro' => 'O campo "Nome da Turma" foi preenchido com valor invalido.');
        }
        return array('status' => true, 'erro' => '');
    }

    //campo 6
    function isValidMediation($mediation){
        if (strlen($mediation) != 1){
            return array('status' => false, 'erro' => 'O campo deve possuir 1 caractere');
        }
        if (!in_array($mediation, array('1', '2', '3'))) {
            return array('status' => false, 'erro' => 'O campo "Tipo de mediacao didatico-pedagogica" foi preenchido com valor invalido.');
        }
        return array('status' => true, 'erro' => '');
    }

    //campos 7 a 10
    function isValidClassroomTime($initialHour, $initialMinute, $finalHour, $finalMinute, $mediation){
        $allowedHours = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
        $allowedMinutes = array('00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55');

        if ($mediation != '1' ){
            if (!$this->isEmpty($initialHour) || !$this->isEmpty($initialMinute) || !$this->isEmpty($finalHour) || !$this->isEmpty($finalMinute)) {
                return array('status' => false, 'erro' => 'Horario da turma deve ser vazio se nao for presencial');
            }
            return array('status' => true, 'erro' => '');
        }
        if ($this->isEmpty($initialHour) || $this->isEmpty($initialMinute) || $this->isEmpty($finalHour) || $this->isEmpty($finalMinute)) {
            return array('status' => false, 'erro' => 'Horario da turma tem que ser preenchido se for presencial');
        }
        if (strlen($initialHour) != 2 || strlen($initialMinute) != 2 || strlen($finalHour) != 2 || strlen($finalMinute) != 2){
            return array('status' => false, 'erro' => 'Os campos devem possuir 2 caracteres');
        }
        if (!in_array($initialHour, $allowedHours) || !in_array($initialMinute, $allowedMinutes) || !in_array($finalHour, $allowedHours) || !in_array($finalMinute, $allowedMinutes)){
            return array('status' => false, 'erro' => 'Horario contem valores invalidos');
        }
        if ($initialHour > $finalHour) {
            return array('status' => false, 'erro' => 'Horario inicial nao pode ser maior que o final');
        } else if (($initialHour == $finalHour) && ($initialMinute >= $finalMinute)) {
            return array('status' => false, 'erro' => 'Horario inicial nao pode ser maior que o final');
        }
        return array('status' => true, 'erro' => '');
    }


    //campos 11 a 17, 20 a 25
    function atLeastOne($array){
        $number_of_ones = 0;
        for($i = 0; $i < sizeof($array); $i++){
            if((strlen($array[$i]) == 1 && $array[$i] == '1') || strlen($array[$i]) == 5) {
                $number_of_ones++;
            }
        }
        if($number_of_ones == 0){
            return array('status' => false, 'erro' => 'Nenhum valor foi marcado');
        }
        return array('status' => true, 'erro' => '');
    }

    //campos 11 a 17
    function areValidClassroomDays($days, $mediation){
        $allowedValues = array('0', '1');

        if ($mediation != '1'){
            foreach ($days as $day) {
                $emptyDay = $this->isEmpty($day);
                if (!$emptyDay['status']) {
                    return array('status' => false, 'erro' => 'Dias de aula da turma devem ser vazios se nao for presencial');
                }
            }
            return array('status' => true, 'erro' => '');
        }
        foreach ($days as $day){
            if (strlen($day) != 1){
                return array('status' => false, 'erro' => 'Os campos devem possuir 1 caractere');
            }
            if (!in_array($day, $allowedValues)) {
                return array('status' => false, 'erro' => 'Dias de aula contem valores invalidos');
            }
        }
        $atLeastOne = $this->atLeastOne($days);
        if (!$atLeastOne['status']){
            return array('status' => false, 'erro' => $atLeastOne['erro']);
        }
        return array('status' => true, 'erro' => '');
    }

    //campo 18
    function isValidAssistanceType($school_structure, $assistance_type, $mediation){
        $allowedValues = array('0', '1', '2', '3', '4', '5');
        $complementary_activities = $school_structure['complementary_activities'];
        $operation_locations = array($school_structure['operation_location_building'],
                                     $school_structure['operation_location_temple'],
                                     $school_structure['operation_location_businness_room'],
                                     $school_structure['operation_location_instructor_house'],
                                     $school_structure['operation_location_other_school_room'],
                                     $school_structure['operation_location_barracks'],
                                     $school_structure['operation_location_socioeducative_unity'],
                                     $school_structure['operation_location_prison_unity'],
                                     $school_structure['operation_location_other']);
        $aee = $school_structure['aee'];
        $emptyAssistanceType = $this->isEmpty($assistance_type);

        if ($emptyAssistanceType['status']){
            return array('status' => false, 'erro' => 'Deve ser preenchido');
        }
        if (strlen($assistance_type) != '1'){
            return array('status' => false, 'erro' => 'O campo deve possuir 1 caractere');
        }
        if (!in_array($assistance_type, $allowedValues)){
            return array('status' => false, 'erro' => 'Tipo de atendimento contem valor invalido');
        }
        for ($i = 0; $i < sizeof($operation_locations); $i++){
            if ($operation_locations[$i] == 1) {
                switch ($i) {
                    case 0:
                        if (!in_array($assistance_type, array(0, 1, 4, 5))){
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - predio escolar');
                        }
                        break;
                    case 1:
                        if (!in_array($assistance_type, array(0, 4, 5))){
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - templo/igreja');
                        }
                        break;
                    case 2:
                        if (!in_array($assistance_type, array(0, 4, 5))){
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - salas de empresa');
                        }
                        break;
                    case 3:
                        if (!in_array($assistance_type, array(0, 4, 5))){
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - casa do professor');
                        }
                        break;
                    case 4:
                        if (!in_array($assistance_type, array(0, 1, 4, 5))){
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - salas em outra escola');
                        }
                        break;
                    case 5:
                        if (!in_array($assistance_type, array(0, 1, 4, 5))){
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - galpao/rancho/paiol/barracao');
                        }
                        break;
                    case 6:
                        if (!in_array($assistance_type, array(2, 4, 5))){
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - atendimento socioeducativo');
                        }
                        break;
                    case 7:
                        if (!in_array($assistance_type, array(3, 4, 5))){
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - unidade prisional');
                        }
                        break;
                    case 8:
                        if (!in_array($assistance_type, array(0, 1, 4, 5))){
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - outros');
                        }
                        break;
                }
            }
        }
        if ($complementary_activities == 0 && $assistance_type == 4){
            return array('status' => false, 'erro' => 'Tipo de atendimento nao pode ser "Atividade complementar" se a escola nao oferece essa atividade');
        }
        if ($aee == 0 && $assistance_type == 5) {
            return array('status' => false, 'erro' => 'Tipo de atendimento nao pode ser "AEE" se a escola nao oferece essa atividade');
        }
        if ($mediation != 1 && ($assistance_type == 4 || $assistance_type == 5)) {
            return array('status' => false, 'erro' => 'Tipo de atendimento nao pode ser "Atividade complementar" nem "AEE" se nao for presencial');
        }
        //fazer regras 7 e 8, que envolvem docentes do registro 51
        return array('status' => true, 'erro' => '');
    }

    //campo 19
    function isValidMaisEducacaoParticipator($participator, $mediation, $administrative_dependence, $assistance_type, $modality, $stage){
        $allowedValues = array('0', '1');
        $checkStages = array('4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19',
                             '20', '21', '22', '23', '24', '41', '25', '26', '27', '28', '29', '30', '31', '32', '33',
                             '34', '35', '36', '37', '38');
        $emptyParticipator = $this->isEmpty($participator);

        if (($mediation == 1 && ($administrative_dependence == 2 || $administrative_dependence == 3)
                && $assistance_type != 1 && $assistance_type != 5
                && ($assistance_type != 4 && $modality != 3 && in_array($stage, $checkStages)))
                && $emptyParticipator['status']) {
            return array('status' => false, 'erro' => 'O campo nao foi preenchido');
        }

        if (!$emptyParticipator['status'] && strlen($participator) != '1'){
            return array('status' => false, 'erro' => 'O campo deve possuir 1 caractere');
        }
        if (!$emptyParticipator['status'] && !in_array($participator, $allowedValues)) {
            return array('status' => false, 'erro' => 'Campo possui valores invalidos');
        }

        if ($mediation != 1 && !$emptyParticipator['status']) {
            return array('status' => false, 'erro' => 'Deve ser nulo se for presencial');
        }

        if (($administrative_dependence != 2 && $administrative_dependence != 3) && !$emptyParticipator['status']) {
            return array('status' => false, 'erro' => 'Deve ser nulo se dependencia administrativa nao for 2 nem 3');
        }

        if (($assistance_type == 1 || $assistance_type == 5) && !$emptyParticipator['status']) {
            return array('status' => false, 'erro' => 'Deve ser nulo se for hospitalar ou AEE');
        }
        if (($modality == 3 || in_array($stage, $checkStages)) && !$emptyParticipator['status']) {
            return array('status' => false, 'erro' => 'Deve ser nulo se nao for fundamental ou medio da modalidade regular, especial ou profissional');
        }
        return array('status' => true, 'erro' => '');
    }


    //campos 20 a 25
    function isValidComplementaryActivityType($activities, $assistance_type){
        $atLeastOne = $this->atLeastOne($activities);

        if ($assistance_type == 4){
            if (!$atLeastOne['status']){
                return array('status' => false, 'erro' => 'Atividade nao foi informada');
            }
        }

        if (count(array_unique(array_diff($activities, array('')))) < count(array_diff($activities, array('')))){
            return array('status' => false, 'erro' => 'Duplicatas');
        }

        if ($assistance_type != 4) {
            foreach ($activities as $activity){
                $emptyActivity = $this->isEmpty($activity);
                if (!$emptyActivity['status']) {
                    return array('status' => false, 'erro' => 'Nao pode ser preenchido se o tipo de atendimento for diferente de Atividade Complementar');
                }
            }
        }

        //falta fazer regra 2

        return array('status' => true, 'erro' => '');
    }

    //campos 26 a 36
    function isValidAEE($aeeArray, $assistance_type){
        $allowedValues = array('0', '1');

        foreach ($aeeArray as $aee) {
            $emptyAee = $this->isEmpty($aee);
            if ($emptyAee['status'] && $assistance_type == 5){
                return array('status' => false, 'erro' => 'Deve ser preenchido quando tipo de atendimento for AEE');
            }
            if (!$emptyAee['status'] && $assistance_type != 5){
                return array('status' => false, 'erro' => 'Nao pode ser preenchido se o tipo de atendimento for diferente de AEE');
            }
            if (!$emptyAee['status'] && !in_array($aee, $allowedValues)){
                return array('status' => false, 'erro' => 'O campo foi preenchido com valor invalido');
            }
        }

        $atLeastOne = $this->atLeastOne($aeeArray);
        if (!$atLeastOne['status'] && $assistance_type == 5){
            return array('status' => false, 'erro' => 'Nao podem ser todos 0');
        }

        return array('status' => true, 'erro' => '');
    }

    //campo 37
    function isValidModality($modality, $assistance_type, $schoolStructureModalities, $mediation){
        $allowedValues = array('1', '2', '3', '4');
        $emptyModality = $this->isEmpty($modality);

        if ($emptyModality['status'] && $assistance_type != 4 && $assistance_type != 5) {
            return array('status' => false, 'erro' => 'Deve ser preenchido quando for Atividade Complementar ou AEE');
        }
        if (!$emptyModality['status'] && $assistance_type == 4) {
            return array('status' => false, 'erro' => 'Nao pode ser preenchido quando for Atividade Complementar');
        }
        if (!$emptyModality['status'] && $assistance_type == 5) {
            return array('status' => false, 'erro' => 'Nao pode ser preenchido quando for AEE');
        }
        if (!in_array($modality, $allowedValues)){
            return array('status' => false, 'erro' => 'O campo foi preenchido com valor invalido');
        }
        foreach ($schoolStructureModalities as $ssm) {
            if ($modality == 1 && $ssm[0] != 1) {
                return array('status' => false, 'erro' => 'O campo nao pode ser preenchido com 1 quando nao for educacao regular');
            }
            if ($modality == 2 && $ssm[1] != 1) {
                return array('status' => false, 'erro' => 'O campo nao pode ser preenchido com 2 quando nao for educacao especial');
            }
            if ($modality == 3 && $ssm[2] != 1) {
                return array('status' => false, 'erro' => 'O campo nao pode ser preenchido com 3 quando nao for EJA');
            }
            if ($modality == 4 && $ssm[3] != 1) {
                return array('status' => false, 'erro' => 'O campo nao pode ser preenchido com 4 quando nao for educacao profissional');
            }
        }
        if (!($modality == 2 || $modality == 3) && $mediation == 2) {
            return array('status' => false, 'erro' => 'O campo modalidade deve ser 2 ou 3 quando a mediacao for semipresencial');
        }
        if (!($modality == 1 || $modality == 3 || $modality == 4) && $mediation == 3) {
            return array('status' => false, 'erro' => 'O campo modalidade deve ser 1, 3 ou 4 quando a mediacao for educacao a distancia');
        }
        return array('status' => true, 'erro' => '');
    }

    //campo 38
    function isValidStage($stage, $assistance_type, $mediation) {
        $emptyStage = $this->isEmpty($stage);
        if ($emptyStage['status'] && $assistance_type != 4 && $assistance_type != 5) {
            return array('status' => false, 'erro' => 'O campo deve ser preenchido quando o tipo de atendimento nao for atividade complementar nem AEE');
        }
        if (!$emptyStage['status'] && ($assistance_type == 4 || $assistance_type == 5)) {
            return array('status' => false, 'erro' => 'O campo nao pode ser preenchido quando o tipo de atendimento for atividade complementar ou AEE');
        }

        //falta fazer regras 4 e 5

        if (($assistance_type == 2 || $assistance_type == 3) && in_array($stage, array(1, 2, 3, 56))) {
            return array('status' => false, 'erro' => 'Nao pode ser educacao infantil quando o tipo de atendimento for unidade de internacao socioeducativa ou unidade prisional');
        }
        if ($mediation == 2 && !in_array($stage, array(69, 70, 71, 72))) {
            return array('status' => false, 'erro' => 'Deve ser preenchido com 69, 70, 71, 72 quando a mediacacao for semipresencial');
        }
        if ($mediation == 3 && !in_array($stage, array(30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 70, 71, 73, 74, 64, 67, 68))) {
            return array('status' => false, 'erro' => 'Deve ser preenchido com 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 70, 71, 73, 74, 64, 67 ou 68 quando a mediacao for educacao a distancia');
        }

        //fazer regras 9 e 10, que envolvem docentes do registro 51

        return array('status' => true, 'erro' => '');
    }

    //campo 39
    function isValidProfessionalEducation($professionalEducation, $stage){
        $emptyProfessionalEducation = $this->isEmpty($professionalEducation);

        if (strlen($professionalEducation) > 8) {
            return array('status' => false, 'erro' => 'O campo deve ter no maximo 8 caracteres');
        }

        if ($emptyProfessionalEducation['status'] && in_array($stage, array(30, 31, 32, 33, 34, 39, 40, 64, 74))) {
            return array('status' => false, 'erro' => 'O campo deve ser preenchido quando a etapa for 30, 31, 32, 33, 34, 39, 40, 64 ou 74');
        }
        if (!$emptyProfessionalEducation['status'] && !in_array($stage, array(30, 31, 32, 33, 34, 39, 40, 64, 74))) {
            return array('status' => false, 'erro' => 'O campo nao pode ser preenchido quando a etapa for 30, 31, 32, 33, 34, 39, 40, 64 ou 74');
        }

        //falta fazer regra 3

        return array('status' => true, 'erro' => '');
    }

    function isValidDiscipline($disciplineArray, $mediation, $assistance_type, $stage){
        $allowedValues = array(0, 1, 2);
        $mustFillOne = false;

        if (!in_array($assistance_type, array(4, 5)) && !in_array($stage, array(1, 2, 3, 65))) {
            $mustFillOne = true;
        }

        foreach ($disciplineArray as $key => $discipline){
            $emptyDiscipline = $this->isEmpty($discipline);
            if (strlen($discipline) > 1){
                return array('status' => false, 'erro' => 'O campo deve ter apenas 1 caractere');
            }
            if ($mustFillOne && $emptyDiscipline['status']){
                return array('status' => false, 'erro' => 'O campo deve ser preenchido quando o tipo de atendimento for diferente de 4 ou 5 e a etapa for diferente de 1, 2, 3 ou 65');
            }
            if ((in_array($assistance_type, array(4, 5)) || in_array($stage, array(1, 2, 3, 65))) && !$emptyDiscipline['status']){
                return array('status' => false, 'erro' => 'O campo deve ser nulo quando o tipo de atendimento for 4 ou 5 e a etapa for 1, 2, 3 ou 65');
            }
            if (!in_array($discipline, $allowedValues)) {
                return array('status' => false, 'erro' => 'O campo foi preenchido com valor invalido');
            }

            //falta fazer regra 5

            if ($discipline == 2 && $mediation == 3) {
                return array('status' => false, 'erro' => 'O campo nao pode ser preenchido com 2 quando a mediacao for Educacao a Distancia');
            }
        }

        $atLeastOne = $this->atLeastOne($disciplineArray);
        if ($mustFillOne && !$atLeastOne['status']){
            return array('status' => false, 'erro' => 'Pelo menos um dos campos deve ser preenchido com 1');
        }
        return array('status' => true, 'erro' => '');
    }
}
?>