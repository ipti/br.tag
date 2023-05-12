<?php
$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) . $DS . "register.php");

class ClassroomValidation extends Register
{

    public $modalities = [
        73 => ' Curso FIC integrado na modalidade EJA – Nível Fundamental (EJA integrada à Educação Profissional)',
        39 => ' Curso Técnico  - Concomitante',
        40 => ' Curso Técnico  - Subsequente',
        30 => ' Curso Técnico Integrado (Ensino Médio Integrado) 1ª Série',
        31 => ' Curso Técnico Integrado (Ensino Médio Integrado) 2ª Série',
        32 => ' Curso Técnico Integrado (Ensino Médio Integrado) 3ª Série',
        33 => ' Curso Técnico Integrado (Ensino Médio Integrado) 4ª Série',
        34 => ' Curso Técnico Integrado (Ensino Médio Integrado) Não Seriada',
        74 => ' Curso Técnico Integrado na Modalidade EJA (EJA integrada à Educação Profissional de Nível Médio)',
        1 => ' Educação Infantil - Creche (0 a 3 anos)',
        2 => ' Educação Infantil - Pré-escola (4 e 5 anos)',
        3 => ' Educação Infantil - Unificada (0 a 5 anos)',
        56 => ' Educação Infantil e Ensino Fundamental (8 e 9 anos) Multietapa',
        64 => ' Educação Profissional Mista - Concomitante e Subsequente ',
        72 => ' EJA - Ensino Fundamental  - Anos iniciais e Anos finais',
        70 => ' EJA - Ensino Fundamental -  Anos finais',
        69 => ' EJA - Ensino Fundamental -  Anos iniciais',
        71 => ' EJA - Ensino Médio',
        44 => ' EJA Presencial - Anos finais',
        43 => ' EJA Presencial - Anos iniciais',
        51 => ' EJA Presencial - Anos iniciais e Anos finais',
        65 => ' EJA Presencial - Ensino Fundamental - Projovem Urbano',
        45 => ' EJA Presencial - Ensino Médio',
        62 => ' EJA Presencial - integrada à Educação Profissional de Nível Médio',
        60 => ' EJA Presencial - integrado à Educação Profissional de Nível Fundamental - FIC',
        47 => ' EJA Semi Presencial - Anos finais',
        46 => ' EJA Semi Presencial - Anos iniciais',
        58 => ' EJA Semi Presencial - Anos iniciais e Anos finais',
        48 => ' EJA Semi Presencial - Ensino Médio',
        63 => ' EJA Semi Presencial - integrada à Educação Profissional de Nível Médio',
        61 => ' EJA Semi Presencial - integrado à Educação Profissional de Nível Fundamental - FIC',
        4 => ' Ensino Fundamental de 8 anos - 1ª Série',
        5 => ' Ensino Fundamental de 8 anos - 2ª Série',
        6 => ' Ensino Fundamental de 8 anos - 3ª Série',
        7 => ' Ensino Fundamental de 8 anos - 4ª Série',
        8 => ' Ensino Fundamental de 8 anos - 5ª Série',
        9 => ' Ensino Fundamental de 8 anos - 6ª Série',
        10 => ' Ensino Fundamental de 8 anos - 7ª Série',
        11 => ' Ensino Fundamental de 8 anos - 8ª Série',
        13 => ' Ensino Fundamental de 8 anos - Correção de Fluxo',
        12 => ' Ensino Fundamental de 8 anos - Multi',
        24 => ' Ensino Fundamental de 8 e 9 anos - Multi 8 e 9 anos',
        14 => ' Ensino Fundamental de 9 anos - 1º Ano',
        15 => ' Ensino Fundamental de 9 anos - 2º Ano',
        16 => ' Ensino Fundamental de 9 anos - 3º Ano',
        17 => ' Ensino Fundamental de 9 anos - 4º Ano',
        18 => ' Ensino Fundamental de 9 anos - 5º Ano',
        19 => ' Ensino Fundamental de 9 anos - 6º Ano',
        20 => ' Ensino Fundamental de 9 anos - 7º Ano',
        21 => ' Ensino Fundamental de 9 anos - 8º Ano',
        41 => ' Ensino Fundamental de 9 anos - 9º Ano',
        23 => ' Ensino Fundamental de 9 anos - Correção de Fluxo',
        22 => ' Ensino Fundamental de 9 anos - Multi',
        25 => ' Ensino Médio - 1ª Série',
        26 => ' Ensino Médio - 2ª Série',
        27 => ' Ensino Médio - 3ª Série',
        28 => ' Ensino Médio - 4ª Série',
        29 => ' Ensino Médio - Não Seriada',
        35 => ' Ensino Médio - Normal/Magistério 1ª Série',
        36 => ' Ensino Médio - Normal/Magistério 2ª Série',
        37 => ' Ensino Médio - Normal/Magistério 3ª Série',
        38 => ' Ensino Médio - Normal/Magistério 4ª Série',
        66 => ' Segmento Técnico da EJA integrada'
    ];

    function __construct()
    {
    }

    //campos 4, 5
    function checkLength($value, $max)
    {
        if (trim($value) === '' || !isset($value)) {
            return array('status' => false, 'erro' => 'Valor eh nulo');
        }
        if (strlen($value) > $max) {
            return array('status' => false, 'erro' => 'Tamanho eh maior que o maximo permitido');
        }
        return array('status' => true, 'erro' => '');
    }

    //campo 5
    function isValidClassroomName($name)
    {

        $regex = "/^([0-9A-ZÀ-Ú ª°º-])+$/";
        $length = $this->checkLength($name, 80);
        if (!$length['status']) {
            return array('status' => false, 'erro' => $length['erro']);
        }
        if (!preg_match($regex, $name)) {
            return array('status' => false, 'erro' => 'O campo "Nome da Turma" foi preenchido com valor invalido.');
        }
        return array('status' => true, 'erro' => '');
    }

    //campo 6
    function isValidMediation($mediation)
    {
        if (empty($mediation)) {
            return array('status' => false, 'erro' => 'O campo deve ser preenchido');
        }
        if (!in_array($mediation, array('1', '2', '3'))) {
            return array('status' => false, 'erro' => 'O campo "Tipo de mediacao didatico-pedagogica" foi preenchido com valor invalido.');
        }
        return array('status' => true, 'erro' => '');
    }

    //campos 7 a 10
    function isValidClassroomTime($initialHour, $initialMinute, $finalHour, $finalMinute, $mediation)
    {
        $allowedHours = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
        $allowedMinutes = array('00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55');

        if ($mediation != '1') {
            if (!$this->isEmpty($initialHour) || !$this->isEmpty($initialMinute) || !$this->isEmpty($finalHour) || !$this->isEmpty($finalMinute)) {
                return array('status' => false, 'erro' => 'Horario da turma deve ser vazio se nao for presencial');
            }
            return array('status' => true, 'erro' => '');
        }


        if (empty($initialHour) || empty($initialMinute) || empty($finalHour) || empty($finalMinute)) {
            return array('status' => false, 'erro' => 'Horario da turma tem que ser preenchido se for presencial');

        }
        if (strlen($initialHour) != 2 || strlen($initialMinute) != 2 || strlen($finalHour) != 2 || strlen($finalMinute) != 2) {
            return array('status' => false, 'erro' => 'Os campos devem possuir 2 caracteres');
        }
        if (!in_array($initialHour, $allowedHours) || !in_array($initialMinute, $allowedMinutes) || !in_array($finalHour, $allowedHours) || !in_array($finalMinute, $allowedMinutes)) {
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
    function atLeastOne($array)
    {
        $number_of_ones = 0;
        for ($i = 0; $i < sizeof($array); $i++) {
            if ((strlen($array[$i]) == 1 && $array[$i] == '1') || strlen($array[$i]) == 5) {
                $number_of_ones++;
            }
        }
        if ($number_of_ones == 0) {
            return array('status' => false, 'erro' => 'Nenhum valor foi marcado');
        }
        return array('status' => true, 'erro' => '');
    }

    //campos 11 a 17
    function areValidClassroomDays($days, $mediation)
    {
        $allowedValues = array('0', '1');

        foreach ($days as $day) {
            if (strlen($day) != 1) {
                return array('status' => false, 'erro' => 'Os campos devem possuir 1 caractere');
            }
            if (!in_array($day, $allowedValues)) {
                return array('status' => false, 'erro' => 'Dias de aula contem valores invalidos');
            }
        }
        $atLeastOne = $this->atLeastOne($days);
        if (!$atLeastOne['status']) {
            return array('status' => false, 'erro' => $atLeastOne['erro']);
        }
        return array('status' => true, 'erro' => '');
    }

    //campo 18
    function isValidAssistanceType($school_structure, $assistance_type, $mediation)
    {
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

        if ($emptyAssistanceType['status']) {
            return array('status' => false, 'erro' => 'Deve ser preenchido');
        }
        if (strlen($assistance_type) != '1') {
            return array('status' => false, 'erro' => 'O campo deve possuir 1 caractere');
        }
        if (!in_array($assistance_type, $allowedValues)) {
            return array('status' => false, 'erro' => 'Tipo de atendimento contem valor invalido');
        }
        for ($i = 0; $i < sizeof($operation_locations); $i++) {
            if ($operation_locations[$i] == 1) {
                switch ($i) {
                    case 0:
                        if (!in_array($assistance_type, array(0, 1, 4, 5))) {
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - predio escolar');
                        }
                        break;
                    case 1:
                        if (!in_array($assistance_type, array(0, 4, 5))) {
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - templo/igreja');
                        }
                        break;
                    case 2:
                        if (!in_array($assistance_type, array(0, 4, 5))) {
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - salas de empresa');
                        }
                        break;
                    case 3:
                        if (!in_array($assistance_type, array(0, 4, 5))) {
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - casa do professor');
                        }
                        break;
                    case 4:
                        if (!in_array($assistance_type, array(0, 1, 4, 5))) {
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - salas em outra escola');
                        }
                        break;
                    case 5:
                        if (!in_array($assistance_type, array(0, 1, 4, 5))) {
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - galpao/rancho/paiol/barracao');
                        }
                        break;
                    case 6:
                        if (!in_array($assistance_type, array(2, 4, 5))) {
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - atendimento socioeducativo');
                        }
                        break;
                    case 7:
                        if (!in_array($assistance_type, array(3, 4, 5))) {
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - unidade prisional');
                        }
                        break;
                    case 8:
                        if (!in_array($assistance_type, array(0, 1, 4, 5))) {
                            return array('status' => false, 'erro' => 'Viola regra local de funcionamento - outros');
                        }
                        break;
                }
            }
        }
        if ($complementary_activities == 0 && $assistance_type == 4) {
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
    function isValidMaisEducacaoParticipator($participator, $mediation, $administrative_dependence, $assistance_type, $modality, $stage)
    {
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

        if (!$emptyParticipator['status'] && strlen($participator) != '1') {
            return array('status' => false, 'erro' => 'O campo deve possuir 1 caractere');
        }
        if (!$emptyParticipator['status'] && !in_array($participator, $allowedValues)) {
            return array('status' => false, 'erro' => 'Campo possui valores invalidos');
        }

        if ($mediation != 1 && !$emptyParticipator['status']) {
            return array('status' => false, 'erro' => 'Deve ser nulo se for presencial');
        }

        if (($administrative_dependence != 2 && $administrative_dependence != 3) && !$emptyParticipator['status']) {
            return array('status' => false, 'erro' => 'Deve ser nulo se dependencia administrativa nao for Estadual nem Municipal');
        }

        if (($assistance_type == 1 || $assistance_type == 5) && !$emptyParticipator['status']) {
            return array('status' => false, 'erro' => 'Deve ser nulo se for hospitalar ou AEE');
        }

        /*
        *   Ocultando validação pois amesma já está sendo tratada
        *   if (($modality == 3 || !(in_array($stage, $checkStages))) && !$emptyParticipator['status']) {
        *        return array('status' => false, 'erro' => 'Deve ser nulo se nao for fundamental ou medio da modalidade regular, especial ou profissional');
        *   }
        */

        return array('status' => true, 'erro' => '');
    }


    //campos 20 a 25
    function isValidComplementaryActivityType($activities, $complementaryActivity)
    {
        $atLeastOne = $this->atLeastOne($activities);

        if ($complementaryActivity == 1) {
            if (!$atLeastOne['status']) {
                return array('status' => false, 'erro' => 'Pelo menos uma atividade complementar deve ser inserida quando o tipo de atendimento "atividade complementar" for selecionado.');
            }
        } else {
            foreach ($activities as $activity) {
                $emptyActivity = $this->isEmpty($activity);
                if (!$emptyActivity['status']) {
                    return array('status' => false, 'erro' => 'Nao pode ser preenchido se o tipo de atendimento "Atividade Complementar" não for selecionado.');
                }
            }
        }
        return array('status' => true, 'erro' => '');
    }

    //campos 26 a 36
    function isValidAEE($aeeArray, $assistance_type)
    {
        $allowedValues = array('0', '1');

        foreach ($aeeArray as $aee) {
            $emptyAee = $this->isEmpty($aee);
            $result = boolval($aee);
            if ($result && $assistance_type == 5) {
                return array('status' => false, 'erro' => 'Deve ser preenchido quando tipo de atendimento for AEE');
            }
            if (!$result && $assistance_type != 5) {
                return array('status' => false, 'erro' => 'Nao pode ser preenchido se o tipo de atendimento for diferente de AEE');
            }
            if (!$result && !in_array($aee, $allowedValues)) {
                return array('status' => false, 'erro' => 'O campo foi preenchido com valor invalido');
            }
        }

        $atLeastOne = $this->atLeastOne($aeeArray);
        if (!$atLeastOne['status'] && $assistance_type == 5) {
            return array('status' => false, 'erro' => 'Nao podem ser todos 0');
        }

        return array('status' => true, 'erro' => '');
    }

    //campo 37
    function isValidModality($modality, $mediation, $complementaryActivity)
    {
        if ($mediation == 2 && ($modality !== 2 && $modality !== 3)) {
            return array('status' => false, 'erro' => 'Deve ser selecionada a opção "Educação Especial" ou "EJA" quando a mediação didático-pedagógica for Semipresencial.');
        }
        if ($mediation == 3 && ($modality !== 1 && $modality !== 3 && $modality !== 4)) {
            return array('status' => false, 'erro' => 'Deve ser selecionada a opção "Ensino Regular", "EJA" ou "Educação Profissional" quando a mediação didático-pedagógica for Educação à Distância.');
        }
        if ($complementaryActivity == 1 && $modality == 3) {
            return array('status' => false, 'erro' => 'Não deve ser selecionada a opção "EJA" quando o tipo de atendimento for "Atividade Complementar".');
        }
        return array('status' => true, 'erro' => '');
    }

    //campo 38
    function isValidStage($stage, $complementaryActivity, $mediation, $modality, $diffLocation)
    {
        if (in_array($stage, [43, 44, 51, 65, 45, 62, 60, 47, 46, 58, 48, 63, 61])) {
            return array('status' => false, 'erro' => 'A etapa de ensino selecionada não faz mais parte do censo. Modifique-a.');
        }
        if ($complementaryActivity == 1 && in_array($stage, array(1, 2, 3, 39, 40, 56, 64, 67, 68, 69, 70, 71, 72, 73, 74))) {
            return array('status' => false, 'erro' => 'Nao pode ser preenchido com essa etapa quando o tipo de atendimento "Atividade Complementar" for selecionado.');
        }
        if ($modality == 1 && !in_array($stage, array(1, 2, 3, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 25, 26, 27, 28, 29, 35, 36, 37, 38, 41, 56))) {
            return array('status' => false, 'erro' => 'Nao pode ser preenchido com essa etapa quando a modalidade "Ensino Regular" for selecionada.');
        }
        if ($modality == 2 && !in_array($stage, array(1, 2, 3, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 41, 56, 39, 40, 69, 70, 71, 72, 73, 74, 64, 67, 68))) {
            return array('status' => false, 'erro' => 'Nao pode ser preenchido com essa etapa quando a modalidade "Educação Especial - Modalidade Substitutiva" for selecionada.');
        }
        if ($modality == 3 && !in_array($stage, array(69, 70, 71))) {
            return array('status' => false, 'erro' => 'Nao pode ser preenchido com essa etapa quando a modalidade "Educação de Jovens e Adultos (EJA)" for selecionada.');
        }
        if ($mediation == 2 && !in_array($stage, array(69, 70, 71, 72))) {
            return array('status' => false, 'erro' => 'Nao pode ser preenchido com essa etapa quando a mediação didático-pedagócica "Semipresencial" for selecionada.');
        }
        if ($mediation == 3 && !in_array($stage, array(25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 70, 71, 73, 74, 64, 67, 68))) {
            return array('status' => false, 'erro' => 'Nao pode ser preenchido com essa etapa quando a mediação didático-pedagócica "Educação a distância" for selecionada.');
        }
        if (($diffLocation == 2 || $diffLocation == 3) && in_array($stage, array(1, 2, 3, 56))) {
            return array('status' => false, 'erro' => 'Quando se trata de uma etapa de Educação Infantil, o local de funcionamento diferenciado NÃO PODE SER "Unidade de atendimento socioeducativo" ou "Unidade prisional".');
        }
        return array('status' => true, 'erro' => '');
    }

    //campo 39
    function isValidProfessionalEducation($professionalEducation, $stage)
    {
        $emptyProfessionalEducation = $this->isEmpty($professionalEducation);

        if (strlen($professionalEducation) > 8) {
            return array('status' => false, 'erro' => 'O campo deve ter no maximo 8 caracteres');
        }

        if ($emptyProfessionalEducation['status'] && in_array($stage, array(30, 31, 32, 33, 34, 39, 40, 64, 74))) {
            return array('status' => false, 'erro' => $this->replaceCodeModalities('O campo deve ser preenchido quando a etapa for 30, 31, 32, 33, 34, 39, 40, 64 ou 74'));
        }
        if (!$emptyProfessionalEducation['status'] && !in_array($stage, array(30, 31, 32, 33, 34, 39, 40, 64, 74))) {
            return array('status' => false, 'erro' => $this->replaceCodeModalities('O campo nao pode ser preenchido quando a etapa for 30, 31, 32, 33, 34, 39, 40, 64 ou 74'));
        }

        //falta fazer regra 3

        return array('status' => true, 'erro' => '');
    }

    function isValidDiscipline($disciplineArray, $mediation, $assistance_type, $stage)
    {
        foreach ($disciplineArray as $key => $discipline) {
            $emptyDiscipline = $this->isEmpty($discipline);
            if (in_array($stage, array(1, 2, 3)) && !$emptyDiscipline['status']) {
                return array('status' => false, 'erro' => 'Não se pode preencher componentes curriculares/eixos de Educação Fundamental quando a etapa for de Educação Infantil');
            }
        }

//        $allowedValues = array(0, 1, 2);
//        $mustFillOne = false;
//
////        if (!in_array($assistance_type, array(4, 5)) && !in_array($stage, array(1, 2, 3, 65))) {
////            $mustFillOne = true;
////        }
//
//        foreach ($disciplineArray as $key => $discipline) {
//            $emptyDiscipline = $this->isEmpty($discipline);
//            if (strlen($discipline) > 1) {
//                return array('status' => false, 'erro' => 'O campo deve ter apenas 1 caractere');
//            }
//            if ($mustFillOne && $emptyDiscipline['status']) {
//                return array('status' => false, 'erro' => 'O campo deve ser preenchido quando o tipo de atendimento for diferente de 4 ou 5 e a etapa for diferente de 1, 2, 3 ou 65');
//            }
//            if ((in_array($assistance_type, array(4, 5)) || in_array($stage, array(1, 2, 3, 65))) && !$emptyDiscipline['status']) {
//                return array('status' => false, 'erro' => 'O campo deve ser nulo quando o tipo de atendimento for 4 ou 5 e a etapa for 1, 2, 3 ou 65');
//            }
//            if (!in_array($discipline, $allowedValues)) {
//                return array('status' => false, 'erro' => 'O campo foi preenchido com valor invalido');
//            }
//
//            //falta fazer regra 5
//
//            if ($discipline == 2 && $mediation == 3) {
//                return array('status' => false, 'erro' => 'O campo nao pode ser preenchido com 2 quando a mediacao for Educacao a Distancia');
//            }
//        }
//
//        $atLeastOne = $this->atLeastOne($disciplineArray);
//        if ($mustFillOne && !$atLeastOne['status']) {
//            return array('status' => false, 'erro' => 'Pelo menos uma disciplina/professor deve ser inserida na turma');
//        }
//        return array('status' => true, 'erro' => '');
    }

    public function replaceCodeModalities($msg)
    {
        foreach ($this->modalities as $key => $value) {
            $msg = str_replace($key, trim($value), $msg);
        }
        return $msg;
    }

    public function isValidAttendanceType($schooling, $complementaryActivity, $aee)
    {
        if ($schooling == null || $complementaryActivity == null || $aee == null) {
            return array('status' => false, 'erro' => 'Atualize a turma.');
        } else if ($schooling == '0' && $complementaryActivity == "0" && $aee == "0") {
            return array('status' => false, 'erro' => 'Selecione ao menos uma opção em "Tipo de Atendimento".');
        }
        return array('status' => true, 'erro' => '');
    }

    public function isValidDiffLocation($pedagogicalMediationType, $diffLocation)
    {
        if ($pedagogicalMediationType == "1" && ($diffLocation == null || $diffLocation == "")) {
            return array('status' => false, 'erro' => 'Quando o tipo de mediação didático-pedagógica for presencial, o campo se torna obrigatório');
        }
        return array('status' => true, 'erro' => '');
    }

    public function isValidRoleForInstructor($instructorsTeachingData, $complementaryActivity)
    {
        if ($complementaryActivity == null || $complementaryActivity === "0") {
            foreach ($instructorsTeachingData as $instructorTeachingData) {
                if (!empty($instructorTeachingData->teachingMatrixes) && $instructorTeachingData->role === "3") {
                    return array('status' => false, 'erro' => 'O cargo de um professor não pode ser "Monitor" quando o campo da turma "Tipo de Atendimento - Atividade Complementar" não for selecionado.');
                }
            }
        }
        return array('status' => true, 'erro' => '');
    }

    public function isValidDisciplineForStage($stage, $instructorsTeachingData)
    {
        foreach ($instructorsTeachingData as $instructorTeachingData) {
            if ($this->instructorHasDiscipline($instructorTeachingData, 25) && in_array($stage, [14, 15, 16, 17, 18, 69, 19, 20, 21, 41, 23, 22, 56, 70, 72, 73, 25, 26, 27, 28, 29, 71, 30, 31, 32, 33, 34, 74, 67, 39, 40, 64, 68])) {
                return array('status' => false, 'erro' => 'A componente curricular "Componentes curriculares/eixos pedagógicos" não é compatível com a etapa de ensino nesta turma.');
            }
        }
        return array('status' => true, 'erro' => '');
    }

    public function instructorHasDiscipline($instructorTeachingData, $disciplineFk)
    {
        if (
            $instructorTeachingData->discipline_1_fk == $disciplineFk ||
            $instructorTeachingData->discipline_2_fk == $disciplineFk ||
            $instructorTeachingData->discipline_3_fk == $disciplineFk ||
            $instructorTeachingData->discipline_4_fk == $disciplineFk ||
            $instructorTeachingData->discipline_5_fk == $disciplineFk ||
            $instructorTeachingData->discipline_6_fk == $disciplineFk ||
            $instructorTeachingData->discipline_7_fk == $disciplineFk ||
            $instructorTeachingData->discipline_8_fk == $disciplineFk ||
            $instructorTeachingData->discipline_9_fk == $disciplineFk ||
            $instructorTeachingData->discipline_10_fk == $disciplineFk ||
            $instructorTeachingData->discipline_11_fk == $disciplineFk ||
            $instructorTeachingData->discipline_12_fk == $disciplineFk ||
            $instructorTeachingData->discipline_13_fk == $disciplineFk ||
            $instructorTeachingData->discipline_14_fk == $disciplineFk ||
            $instructorTeachingData->discipline_15_fk == $disciplineFk
        ) {
            return true;
        }
        return false;
    }

    public function containsInstructors($instructorsTeachingData) {
        if ($instructorsTeachingData == null) {
            return array('status' => false, 'erro' => 'A turma precisa de professores com componentes curriculares/eixos cadastrados.');
        } else {
            $hasInstructorWithTeachingMatrix = false;
            foreach($instructorsTeachingData as $instructorTeachingData) {
                if (!empty($instructorTeachingData->teachingMatrixes)) {
                    $hasInstructorWithTeachingMatrix = true;
                    break;
                }
            }
            if (!$hasInstructorWithTeachingMatrix) {
                return array('status' => false, 'erro' => 'A turma precisa de professores com componentes curriculares/eixos cadastrados.');
            }
        }
        return array('status' => true, 'erro' => '');
    }

    public function containsStudents($studentsEnrollment) {
        if ($studentsEnrollment == null) {
            return array('status' => false, 'erro' => 'A turma precisa de alunos matriculados.');
        }
        return array('status' => true, 'erro' => '');
    }
}

?>