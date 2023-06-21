<?php

$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) . $DS . "register.php");

//Registro 00
class SchoolIdentificationValidation extends Register
{

    //campo 1
    function isRegisterType00($register_type)
    {
        if (strlen($register_type) > 2) {
            return array("status" => false, "erro" => "As linhas devem ser iniciadas com o número do registro.");
        }

        if ($register_type != 00) {
            return array("status" => false, "erro" =>
                "O registro declarado <tipo de registro> não faz parte do escopo do educacenso.");
        }

        return array("status" => true, "erro" => "");
    }

    //campo 2
    /*@todo
    4. Deve está de acordo com a UF informada no campo 18 (UF).
    5. O código deve ser de uma entidade existente no cadastro do Inep.
    6. A escola informada deve estar entre as escolas abrangidas pelo perfil do informante.
    7. A escola informada não pode ter sido extinta em anos anteriores.
   */

    function isInepIdValid($inep_id)
    {
        if (empty($inep_id)) {
            return array("status" => false, "erro" =>
                "O campo Código de escola - está vazio");
        }

        if (strlen($inep_id) != 8) {
            return array("status" => false, "erro" =>
                "O campo Código de escola - Inep está com tamanho diferente de 8 caracteres");
        }

        if (!is_numeric($inep_id)) {
            return array("status" => false, "erro" =>
                "O campo Código de escola - Inep foi preenchido com um valor não númerico");
        }

        return array("status" => true, "erro" => "");
    }

    //campo 3
    function isManagerCPFValid($manager_cpf)
    {
        if (empty($manager_cpf)) {
            return array("status" => false, "erro" =>
                "O campo Número do CPF do Gestor Escolar não está preenchido");
        }

        if (strlen($manager_cpf) > 11) {
            return array("status" => false, "erro" =>
                "O campo Número do CPF do Gestor Escolar está com tamanho diferente de 11 caracteres");
        }

        // se nao for numerico
        if (!is_numeric($manager_cpf)) {
            return array("status" => false, "erro" =>
                "O campo Número do CPF do Gestor Escolar tem que ser um valor númerico");
        }

        // se for 0000000000, 1111111
        if (preg_match('/^(.)\1*$/', $manager_cpf)) {
            return array("status" => false, "erro" =>
                "O campo Número do CPF do Gestor Escolar não pode ser 0000000000 ou 1111111");
        }

        if ($manager_cpf == "00000000191") {
            return array("status" => false, "erro" =>
                "O campo Número do CPF do Gestor Escolar não pode ser 00000000191");
        }

        return array("status" => true, "erro" => "");
    }

    //campo 4
    function isManagerNameValid($manager_name)
    {
        if (empty($manager_name)) {
            return array("status" => false, "erro" =>
                "O campo Nome do Gestor Escolar não pode ser vazio");
        }

        if (strlen($manager_name) > 100) {
            return array("status" => false, "erro" =>
                "O campo Nome do Gestor Escolar foi preenchido com mais de 100 caracteres");
        }

        $regex = "/^[A-Z0-9°ºª\- ]/";
        if (!preg_match($regex, $manager_name)) {
            return array("status" => false, "erro" =>
                "O campo Nome do Gestor Escolar foi preenchido com caracteres inválidos");
        }

        return array("status" => true, "erro" => "");
    }

    //cargo do gestor campo 5
    function isManagerRoleValid($manager_role)
    {
        if (empty($manager_role)) {
            return array("status" => false, "erro" =>
                "O campo Cargo do Gestor Escolar não pode ser vazio.");
        }

        if ($manager_role != 1 && $manager_role != 2) {
            return array("status" => false, "erro" =>
                "O campo Cargo do Gestor Escolar foi preenchido com um valor diferente de 1 ou 2");
        }

        return array("status" => true, "erro" => "");
    }

    //address eletronico do gestor campo 6
    function isManagerEmailValid($manager_email)
    {
        if (empty($manager_email)) {
            return array("status" => false, "erro" =>
                "O campo Endereço eletrônico (e-mail) do Gestor Escolar não pode ser vazio");
        }

        if (strlen($manager_email) > 50) {
            return array("status" => false, "erro" =>
                "O campo Endereço eletrônico (e-mail) do Gestor Escolar não pode ser maior que 50 caracteres");
        }

        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $manager_email)) {
            return array("status" => false, "erro" =>
                "O campo Endereço eletrônico (e-mail) do Gestor Escolar  foi preenchido com um valor inválido.");
        }

        return array("status" => true, "erro" => "");

    }

    function isManagerBirthdayValid($date, $currentYear)
    {

        $result = $this->validateDateformart($date);
        if (!$result['status']) {
            return array("status" => false, "erro" => $result['erro']);
        }

        $mdy = explode('/', $date);

        $result = $this->isGreaterThan($mdy[2], 1910);
        if (!$result['status']) {
            return array("status" => false, "erro" => $date . ": O ano de nascimento do gestor escolar foi preenchido incorretamente.");
        }

        $result = $this->isNotGreaterThan($mdy[2], $currentYear);
        if (!$result['status']) {
            return array("status" => false, "erro" => $result['erro']);
        }

        if ($mdy[2] == date("Y")) {
            return array("status" => false, "erro" => $date . ": o ano de nascimento do gestor escolar não pode ser o ano atual.");
        }

        $currentDate = new DateTime("now");
        $birthdayDate = DateTime::createFromFormat('d/m/Y', $date);
        $interval = $birthdayDate->diff($currentDate);
        if ($interval->y < 18 || $interval->y > 95) {
            return array("status" => false, "erro" => "O gestor escolar não pode ter menos de 18 anos ou mais de 95 anos.");
        }
        return array("status" => true, "erro" => "");
    }

    //situacao de funcionamento campo 7
    function isSituationValid($situation)
    {
        if (empty($situation)) {
            return array("status" => false, "erro" =>
                "O campo Situação de funcionamento não pode ser vazio");
        }
        if ($situation != 1 && $situation != 2 && $situation != 3) {
            return array("status" => false, "erro" =>
                "O campo Situação de funcionamento não pode ser preenchido com valores diferentes de 1,2 ou 3");
        }
        return array("status" => true, "erro" => "");
    }

    //auxiliar dos campos 8 e 9
    function isDateValid($date)
    {

        if ($date == '' || $date == null) {
            return array("status" => false, "erro" => "Data no formato incorreto");
        }

        $data = explode('/', $date);
        $dia = $data[0];
        $mes = $data[1];
        $ano = $data[2];

        // verifica se a data é valida
        if (!checkdate($mes, $dia, $ano)) {
            return array("status" => false, "erro" => "Data no formato incorreto");
        }

        return array("status" => true, "erro" => "");
    }


    //campo 8 e 9
    function isSchoolYearValid($initial_date, $final_date)
    {
        if (empty($initial_date) || empty($final_date)) {
            return array("status" => false, "erro" => "As datas de início e final do ano letivo não podem ser vazias.");
        }
        $first_result = $this->isDateValid($initial_date);
        $second_result = $this->isDateValid($final_date);
        if (!($first_result['status'] && $second_result['status'])) {
            return array("status" => false, "erro" => "As datas de inicio e final do ano letivo não estão em um formato válido.");
        } else {
            $dataInicial = explode('/', $initial_date);
            $diaInicial = $dataInicial[0];
            $mesInicial = $dataInicial[1];
            $anoInicial = $dataInicial[2];

            $dataFinal = explode('/', $final_date);
            $diaFinal = $dataFinal[0];
            $mesFinal = $dataFinal[1];
            $anoFinal = $dataFinal[2];

            // se a data inicial do periodo letivo é menor que a data final
            if ($anoInicial < $anoFinal) {
                return array("status" => true, "erro" => "");
            } else if ($anoInicial == $anoFinal) {
                if ($mesInicial < $mesFinal) {
                    return array("status" => true, "erro" => "");
                }
                if ($mesInicial == $mesFinal) {
                    if ($diaInicial < $diaFinal) {
                        return array("status" => true, "erro" => "");
                    }
                    if ($diaInicial >= $diaFinal)
                        return array("status" => false, "erro" =>
                            "Dia inicial é maior ou igual a Dia Final");
                }
                if ($mesInicial > $mesFinal) {
                    return array("status" => false, "erro" =>
                        "Mes Inicial está maior que Mes Final");
                }
            } else {
                return array("status" => false, "erro" =>
                    "Ano letivo inicial está maior que o ano final");
            }
        }
    }


    //campo 10
    function isSchoolNameValid($name)
    {
        //deve ser no minimo 4
        if (strlen($name) == 0) {
            return array("status" => false, "erro" =>
                "O campo Nome da escola não pode ser vazio");
        }
        if (strlen($name) < 4) {
            return array("status" => false, "erro" =>
                "O campo Nome da escola não pode ter menos de 4 caracteres");
        }
        if (strlen($name) > 100) {
            return array("status" => false, "erro" =>
                "O campo Nome da escola tem mais de 100 caracteres");
        }
        if (!(preg_match('/^[a-z\d°ºª\-. ]{4,20}/i', $name))) {
            return array("status" => false, "erro" =>
                "O campo Nome da escola possui caracteres inválidos");
        }

        return array("status" => true, "erro" => "");
    }

    //campo 11
    function isLatitudeValid($latitude)
    {
        if (strlen($latitude) > 20) {
            return array("status" => false, "erro" =>
                "O campo Latitude não pode ter mais de 20 caracteres");
        }
        $regex = "/^[0-9.-]+$/";
        if (!preg_match($regex, $latitude)) {
            return array("status" => false, "erro" =>
                "O campo Latitude contém caractere(s) inválido(s).");
        }
        if (!($latitude >= -33.750833 && $latitude <= 5.272222)) {
            return array("status" => false, "erro" =>
                "O campo Latitude foi preenchido com um valor que não está entre -33.750833 e 5.272222");
        }

        return array("status" => true, "erro" => "");
    }

    //campo 12
    function isLongitudeValid($longitude)
    {
        if (strlen($longitude) > 20) {
            return array("status" => false, "erro" =>
                "O campo Longitude não pode ter mais de 20 caracteres");
        }
        $regex = "/^[0-9.-]+$/";
        if (!preg_match($regex, $longitude)) {
            return array("status" => false, "erro" =>
                "O campo Longitude contém caractere(s) inválido(s).");
        }
        if (!($longitude >= -73.992222 && $longitude <= -32.411280))
            return array("status" => false, "erro" =>
                "O campo Longitude foi preenchido com um valor que não está entre -73.99222 e -32.411280");

        return array("status" => true, "erro" => "");
    }


    //campo 13
    function isCEPValid($cep)
    {
        if (empty($cep)) {
            return array("status" => false, "erro" => "O campo CEP não pode ser vazio");
        }
        if (strlen($cep) != 8) {
            return array("status" => false, "erro" =>
                "O campo CEP deve ter 8 caracteres");
        }
        if (!is_numeric($cep)) {
            return array("status" => false, "erro" =>
                "O campo CEP deve ser preenchido com um valor numerico");
        }
        if (preg_match('/^(.)\1*$/', $cep)) {
            return array("status" => false, "erro" =>
                "O campo CEP não pode ser a repetição de um único algarismo");
        }

        return array("status" => true, "erro" => "");
    }

    //campo 14,campo 15,campo 16,campo 17,campo 18,campo 19,campo 20
    function isAddressValid($address, $allowed_lenght, $optional)
    {
        $regex = "/^[0-9 a-z.,-ºª ]/";

        if (!$optional && $address === "") {
            return array("status" => false, "erro" =>
                "O campo endereço é obrigatório.");
        }

        if (strlen($address) > $allowed_lenght) {
            return array("status" => false, "erro" =>
                "O campo de endereço - $address - não pode ter mais de $allowed_lenght caracteres.");
        }
        if (!preg_match($regex, $address)) {
            return array("status" => false, "erro" =>
                "O campo de endereço - $address - foi preenchido com caracteres inválidos");
        }

        return array("status" => true, "erro" => "");
    }

    function checkPhoneNumbers($ddd, $phoneNumber, $otherPhoneNumber)
    {
        $dddEmpty = $ddd === "" || $ddd === null;
        $phoneNumberEmpty = $phoneNumber === "" || $phoneNumber === null;
        $otherPhoneNumberEmpty = $otherPhoneNumber === "" || $otherPhoneNumber === null;
        if (!$dddEmpty && $phoneNumberEmpty && $otherPhoneNumberEmpty) {
            return array("status" => false, "erro" => "Quando o DDD é prenchido, pelo menos um dos telefones também deve ser preenchido.");
        } else if ($dddEmpty && (!$phoneNumberEmpty || !$otherPhoneNumberEmpty)) {
            return array("status" => false, "erro" => "Quando um dos telefones é preenchido, o DDD também deve ser preenchido.");
        } else if (!$dddEmpty && strlen($ddd) != 2) {
            return array("status" => false, "erro" => "DDD deve conter 2 dígitos.");
        } else if (!$phoneNumberEmpty && !$otherPhoneNumberEmpty && ($phoneNumber === $otherPhoneNumber)) {
            return array("status" => false, "erro" => "Os dois campos de números de telefone não podem ser iguais.");
        } else if ((!$phoneNumberEmpty && (strlen($phoneNumber) < 8 || strlen($phoneNumber) > 9))
            || (!$otherPhoneNumberEmpty && (strlen($otherPhoneNumber) < 8 || strlen($otherPhoneNumber) > 9))) {
            return array("status" => false, "erro" => "Os campos de telefone, quando preenchidos, devem conter 8 ou 9 dígitos.");
        } else if ((!$phoneNumberEmpty && !is_numeric($phoneNumber)) || (!$otherPhoneNumberEmpty && !is_numeric($otherPhoneNumber))) {
            return array("status" => false, "erro" => "Apenas dígitos devem ser informados, sem hífens.");
        } else if ((!$phoneNumberEmpty && strlen($phoneNumber) === 9 && substr($phoneNumber, 0, 1) !== "9")
            || (!$otherPhoneNumberEmpty && strlen($otherPhoneNumber) === 9 && substr($otherPhoneNumber, 0, 1) !== "9")) {
            return array("status" => false, "erro" => "Quando o telefone tiver 9 dígitos, o primeiro caractere deve ser o dígito 9.");
        } else if ((!$phoneNumberEmpty && count(array_unique(str_split($phoneNumber))) === 1) || (!$otherPhoneNumberEmpty && count(array_unique(str_split($otherPhoneNumber))) === 1)) {
            return array("status" => false, "erro" => "Os campos de telefone não podem ser a repetição de um mesmo algarismo.");
        }
        return array("status" => true, "erro" => "");
    }

    //campo 26
    function isEmailValid($email)
    {
        if (strlen($email) > 50) {
            return array("status" => false, "erro" => "Email com tamanho invalido");
        }

        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i",
            $email)) {
            return array("status" => false, "erro" => "Email com formato invalido");
        }

        return array("status" => true, "erro" => "");
    }


    //campo 28
    function isAdministrativeDependenceValid($value, $uf)
    {

        $result = $this->isAllowed($value, array('1', '2', '3', '4'));
        if (!$result['status']) {
            return array("status" => false, "erro" => $result['erro']);
        }

        if ($uf == '53') {
            if ($value == '3') {
                return array("status" => false, "erro" => "Dependencia Administrativa inválida");
            }
        }
        return array("status" => true, "erro" => "");
    }

    //campo 29
    function isLocationValid($value)
    {
        if ($value != 1 && $value != 2) {
            return array("status" => false, "erro" => "Lozalização inválida");
        }

        return array("status" => true, "erro" => "");
    }

    //auxiliar nos campos 30,31,32
    function isField7And28Valid($inep_id, $schoolSituation, $dependency)
    {
        //campo 7 deve ser igual a 1.. Campo 28 deve ser igual a 4
        if ($schoolSituation == 1 && isSituationValid($schoolSituation) == true
            && isAdministrativeDependenceValid($inep_id, $dependency) == true && $dependency == 4) {
            return true;
        } else {
            return false;
        }
    }

    //campo 30
    function checkPrivateSchoolCategory($value, $situation, $administrative_dependence)
    {
        if ($situation == '1' && $administrative_dependence == '4') {
            $result = $this->isAllowed($value, array('1', '2', '3', '4'));
            if (!$result['status']) {
                return array("status" => false, "erro" => $result['erro']);
            }
        } else {
            if ($value != null) {
                return array("status" => false, "erro" => "Valor $value deveria ser nulo");
            }
        }

        return array("status" => true, "erro" => "");
    }

    //campo 31

    function isPublicContractValid($value, $situation, $administrative_dependence)
    {
        if (!($situation == '1' && $administrative_dependence == '4')) {
            if ($value != null) {
                return array("status" => false, "erro" => "Valor $value deveria ser nulo");
            } else {
                return array("status" => true, "erro" => "");
            }
        } else {
            $result = $this->isAllowed($value, array('1', '2', '3'));
            if (!$result['status']) {
                return array("status" => false, "erro" => $result['erro']);
            }
        }
        return array("status" => true, "erro" => "");

    }

    //campos 32 a 36

    function isPrivateSchoolMaintainerValid($keepers, $situation, $administrative_dependence)
    {

        if ($situation == '1' && $administrative_dependence == '4') {
            $result = $this->atLeastOne($keepers);
            if (!$result['status']) {
                return array("status" => false, "erro" => $result['erro']);
            }
        } else {
            foreach ($keepers as $key => $value) {
                if ($value != null) {
                    return array("status" => false, "erro" => "Valor deveria ser nulo");
                }
            }
        }

        return array("status" => true, "erro" => "");
    }

    //para os campos 37 e 38
    function isCNPJValid($cnpj, $situation, $administrative_dependence)
    {

        if (!($situation == '1' && $administrative_dependence == '4')) {
            if ($cnpj != null) {
                return array("status" => false, "erro" => "Valor $cnpj deveria ser nulo");
            } else {
                return array("status" => true, "erro" => "");
            }
        }

        if (!is_numeric($cnpj)) {
            return array("status" => false, "erro" => "CNPJ está com padrão inválido");
        }
        if (strlen($cnpj) != 14) {
            return array("status" => false, "erro" => "O CNPJ está com tamanho errado");
        }

        return array("status" => true, "erro" => "");
    }

    //campo 39
    function isRegulationValid($value, $schoolSituation)
    {
        //campo 7 deve ser igual a 1
        if ($schoolSituation == 1) {
            if ($value != 0 && $value != 1 && $value != 2) {
                return array("status" => false, "erro" => "Campo Obrigatório");
            }
        } else {
            if ($value != null) {
                return array("status" => false, "erro" => "Valor $value deveria ser nulo");
            }
        }

        return array("status" => true, "erro" => "");
    }

    //campo 40,41 e 42
    function isOfferOrLinkedUnity($value, $InepCode, $HeadSchool, $schoolSituation,
                                  $hostedcenso_city_fk, $atualedcenso_city_fk, $hostDependencyAdm, $atualDependencyAdm, $IESCode)
    {
        if ($value == 1) {
            return isInepHeadSchoolValid($InepCode, $HeadSchool, $schoolSituation, $hostedcenso_city_fk, $atualedcenso_city_fk, $hostDependencyAdm, $atualDependencyAdm);
        }
        if ($value == 2) {
            return isIESCodeValid($IESCode, $schoolSituation);
        }
    }

    //41
    function inepHeadSchool($value, $offer_or_linked_unity, $current_inep_id,
                            $head_school_situation, $head_of_head_school)
    {
        if ($offer_or_linked_unity == '1') {
            if ($value == "" && $value == null) {
                return array("status" => false, "erro" => "O campo não foi preenchido quando deveria ser preenchido.");
            }
            if (!is_numeric($value)) {
                return array("status" => false, "erro" => "Apenas números são permitidos");
            }
            if (strlen($value) != 8) {
                return array("status" => false, "erro" => "Deve conter 8 caracteres");
            }
            if ($value == $current_inep_id) {
                return array("status" => false, "erro" => "Não pode ser preenchido com o mesmo código INEP da escola.");
            }
            if ($head_school_situation != '1') {
                return array("status" => false, "erro" => "Escola sede não está em atividade");
            }
            if (substr($current_inep_id, 0, 2) != substr($value, 0, 2)) {
                return array("status" => false, "erro" => "Escolas não são do mesmo estado");
            }
            if ($current_inep_id == $head_of_head_school) {
                return array("status" => false, "erro" => "Escola sede não pode ter atual como sede");
            }

        } else {
            if ($value != null) {
                return array("status" => false, "erro" => "Valor $value deveria ser nulo");
            }
        }

        return array("status" => true, "erro" => "");

    }

    //42
    function iesCode($value, $administrativeDependence, $offer_or_linked_unity)
    {
        if ($offer_or_linked_unity == '2') {
            if ($value == "" && $value == null) {
                return array("status" => false, "erro" => "O campo não foi preenchido quando deveria ser preenchido.");
            }
            if (!is_numeric($value)) {
                return array("status" => false, "erro" => "Apenas números são permitidos");
            }
            $institutionType = "PRIVADA";
            if ($administrativeDependence == "1" || $administrativeDependence == "2" || $administrativeDependence == "3") {
                $institutionType = "PUBLICA";
            }
            $sql = "SELECT 	COUNT(id) AS status
			FROM 	edcenso_ies
			WHERE 	id = ' . $value . ' AND working_status = 'ATIVA'
					AND institution_type = '$institutionType';";
            $check = Yii::app()->db->createCommand($sql)->queryAll();
            if ($check[0]["status"] != '1') {
                return array("status" => false, "erro" => "O código IES deve ser preenchido com um valor permitido, estar ativo e ser da mesma dependência administrativa.");
            }
        } else {
            if ($value != null || $value != "") {
                return array("status" => false, "erro" => "Valor $value deveria ser nulo");
            }
        }

        return array("status" => true, "erro" => "");
    }

    public function isNotNullValid($value)
    {
        if ($value == null) {
            return array("status" => false, "erro" => "O campo não foi preenchido quando deveria ser preenchido");
        }
        return array("status" => true, "erro" => "");
    }

    public function isValidLinkedOrgan($administrativeDependence, $linkedMec, $linkedArmy, $linkedHealth, $linkedOther)
    {
        if ($administrativeDependence == "1" || $administrativeDependence == "2" || $administrativeDependence == "3") {
            if (($linkedMec == null || $linkedMec == "0") && ($linkedArmy == null || $linkedArmy == "0")
                && ($linkedHealth == null || $linkedHealth == "0") && ($linkedOther == null || $linkedOther == "0")) {
                return array("status" => false, "erro" => "Quando a dependência administrativa for federal, estadual ou municipal, o campo se torna obrigatório.");
            }
        } else {
            if ($linkedMec != null || $linkedArmy != null || $linkedHealth != null || $linkedOther != null) {
                return array("status" => false, "erro" => "Quando a dependência administrativa for privada, as opções do campo deve ser todas vazias.");
            }
        }
        return array("status" => true, "erro" => "");
    }

    public function regulationOrganSphere($administrativeDependence, $federal, $state, $municipal) {
        if (($federal == 0 || $federal == null) && ($state == 0 || $state == null) && ($municipal == 0 || $municipal == null)) {
            return array("status" => false, "erro" => "Selecione pelo menos uma opção.");
        }

        if ($federal == 1 && ($administrativeDependence == 2 || $administrativeDependence == 3)) {
            return array("status" => false, "erro" => "Quando a dependência administrativa for Estadual ou Municipal, não pode selecionar 'Órgão Federal'.");
        }
        if ($municipal == 1 && ($administrativeDependence == 1 || $administrativeDependence == 2)) {
            return array("status" => false, "erro" => "Quando a dependência administrativa for Federal ou Estadual, não pode selecionar 'Órgão Municipal'.");
        }
        
        if ($municipal == 1 && $federal == 1) {
            return array("status" => false, "erro" => "Os campos 'Órgão Municipal' e 'Órgão Federal' não podem ser ambos selecionados.");
        }
        return array("status" => true, "erro" => "");
    }
}

?>