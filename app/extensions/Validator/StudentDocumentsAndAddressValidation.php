<?php

$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) . $DS . "register.php");

//registro 70
class StudentDocumentsAndAddressValidation extends Register
{

    //campo 5
    function isRgNumberValid($rg, $Reg60Field12)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            if (strlen($rg) > 20) {
                return array("status" => false, "erro" => "Numero da Identidade não está com tamanho correto");
            } else if (!preg_match('/^[0-9]{7}([- ]?[0-9]{1})?$/', $rg)) {
                return array("status" => false, "erro" => "Numero da Identidade está com padrão incorreto");
            } else {
                return array("status" => true, "erro" => "Campo 12 do registro 60 deve ser igual a 1 ou 2");
            }
        } else if (preg_match('/^[0-9]{7}([- ]?[0-9]{1})?$/', $rg)) {
            return array("status" => true, "erro" => "");
        } else {
            return array("status" => false, "erro" => "Numero da Identidade está com padrão incorreto");
        }
    }

    //campo 6
    function isRgEmissorOrganValid($EmissorOrgan, $Reg60Field12, $Reg70Field5)
    {
        if (strlen($Reg70Field5) != 0) {
            if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
                if (strlen($EmissorOrgan) == 0) {
                    return array("status" => false, "erro" =>
                        " Orgao emissor deve ser  preenchido");
                }
                if (strlen($EmissorOrgan) != 2) {
                    return array("status" => false, "erro" =>
                        " Orgao emissor preenchido com tamanho inválido");
                } else {
                    return array("status" => true, "erro" => "");
                }
            } else {
                return array("status" => false, "erro" => " Campo 12 do registro 60 deve ser igual a 1 ou 2");
            }
        } //deve ser nulo quando campo 5 for nulo
        else {
            if (strlen($EmissorOrgan) != 0) {
                return array("status" => false, "erro" => " Orgao emissor deve ser nulo");
            }
        }
    }

    //campo 7
    function isRgUfValid($rgUF, $Reg60Field12, $Reg70Field5)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            //os campos 5 e 6 devem ser preenchidos
            if (strlen($Reg70Field5) != 0) {
                if (strlen($rgUF) == 0) {
                    return array("status" => false, "erro" => "UF da identidade deve ser  preenchido");
                } else {
                    return array("status" => true, "erro" => "");
                }

                if (strlen($rgUF) != 2) {
                    return array("status" => false, "erro" => "UF da identidade preenchido com tamanho inválido");
                } else {
                    return array("status" => true, "erro" => "");
                }
            } else {
                if (strlen($rgUF) != 0) {
                    return array("status" => false, "erro" => "UF da identidade deve ser nulo");
                } else {
                    return array("status" => true, "erro" => "");
                }
            }
        } else {
            return array("status" => false, "erro" => "Campo 12 do registro 60 deve ser igual a 1 ou 2");
        }
    }

    //campos 8 e 14
    function isDateValid($Reg60Field12, $expeDate, $birthDate, $currentDate, $Reg70Field9, $currentField)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            //SE FOR PARA O CAMPO 8 COM CAMPO 9 SERÁ 0 | PARA O CAMPO 14 COM CAMPO 9 SENDO 1
            if (($currentField == 8) || ($currentField == 14 && $Reg70Field9 == 1)) {
                if ($currentField == 14 && $Reg70Field9 == 0) {
                    return array("status" => false, "erro" => "Campo 9 deve ser 1");
                } else if (self::dateValid($expeDate) == true) {
                    $dataExpedicao = explode('/', $expeDate);
                    $diaExpedicao = $dataExpedicao[0];
                    $mesExpedicao = $dataExpedicao[1];
                    $anoExpedicao = $dataExpedicao[2];

                    $dataNasceu = explode('/', $birthDate);
                    $diaNasceu = $dataNasceu[0];
                    $mesNasceu = $dataNasceu[1];
                    $anoNasceu = $dataNasceu[2];


                    $dataAtual = explode('/', $currentDate);
                    $diaAtual = $dataAtual[0];
                    $mesAtual = $dataAtual[1];
                    $anoAtual = $dataAtual[2];

                    //$DataNasceu < $DataExpedicao < $DataAtual
                    if ($anoExpedicao > $anoNasceu) {
                        if ($anoExpedicao < $anoAtual) {
                            return array("status" => true, "erro" => "");
                        }
                        if ($anoExpedicao > $anoAtual) {
                            return array("status" => false, "erro" => "Data de expedicao superior a data atual");
                        }
                        if ($anoExpedicao == $anoAtual) {
                            //comparar os meses
                            if ($mesExpedicao < $mesAtual) {
                                return array("status" => true, "erro" => "");
                            }
                            if ($mesExpedicao > $mesAtual) {
                                return array("status" => false, "erro" => "Data de expedicao superior a data atual");
                            }

                            if ($mesExpedicao == $mesAtual) {
                                //comparar dias
                                if ($diaExpedicao < $diaAtual) {
                                    return array("status" => true, "erro" => "");
                                }
                                if ($diaExpedicao >= $diaAtual)
                                    return array("status" => false, "erro" => "Data de expedicao superior a data atual");
                            }
                        }
                    }
                    if ($anoExpedicao < $anoNasceu) {
                        return array("status" => false, "erro" => "Data de expedicao inferior a data de nascimento");
                    }
                    if ($anoExpedicao == $anoNasceu) {
                        //comparar os meses
                        if ($mesExpedicao > $mesNasceu) {
                            return array("status" => true, "erro" => "");
                        }
                        if ($mesExpedicao < $mesNasceu) {
                            return array("status" => false, "erro" => "Data de expedicao inferior a data de nascimento");
                        }
                        if ($mesExpedicao == $mesNasceu) {
                            //comparar os dias
                            if ($diaExpedicao > $diaNasceu) {
                                return array("status" => true, "erro" => "");
                            } else {
                                return array("status" => false, "erro" => "Data de expedicao inferior a data de nascimento");
                            }
                        }
                    }
                } else {
                    return array("status" => false, "erro" => "Data de expedicao no formato incorreto");
                }
            }
        } else {
            return array("status" => false, "erro" => "Campo 12 do registro 60 deve ser igual a 1 ou 2");
        }
    }

    //auxiliar do campo 8
    function dateValid($date)
    {
        $data = explode('/', $date);
        $dia = (int)$data[0];
        $mes = (int)$data[1];
        $ano = (int)$data[2];

        // verifica se a data é valida
        if (!checkdate($mes, $dia, $ano)) {
            return array("status" => false, "erro" => "Data no formato incorreto");
        } else {
            return array("status" => true, "erro" => "");
        }
    }

    //campo 9
    function isCivilCertificationValid($Reg70Field5, $Reg60Field12)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            if ($Reg70Field5 == 1 || $Reg70Field5 == 2) {
                return array("status" => true, "erro" => "");
            } else {
                return array("status" => false, "erro" => "Certificacao Civil deve ser igual a 1 ou 2");
            }
        } else {
            return array("status" => false, "erro" => "Campo 12 do registro 60 deve ser igual a 1 ou 2");
        }
    }

    //campo 10
    function isCivilCertificationTypeValid($type, $Reg70Field5, $Reg60Field12, $birthday, $currentDate)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            if ($type == 1) {
                return array("status" => true, "erro" => "");
            } else if ($type == 2) {
                //data
                $data = explode('/', $currentDate);
                $diaAtual = $data[0];
                $mesAtual = $data[1];
                $anoAtual = $data[2];

                $dataNiver = explode('/', $birthday);
                $diaNiver = $dataNiver[0];
                $mesNiver = $dataNiver[1];
                $anoNiver = $dataNiver[2];

                $idade = $anoAtual - $anoNiver;

                if ($mesAtual < $mesNiver) {
                    $idade--;
                } else if ($mesAtual == $mesNiver and $diaAtual < $diaNiver) {
                    $idade--;
                }

                if ($idade <= 10) {
                    return array("status" => false, "erro" => "Aluno com menos de 10 anos não pode ter certidão de casamento.");
                }

                return array("status" => true, "erro" => "");
            } else {
                return array("status" => false, "erro" => "O campo certidão civil deve ser preenchido");
            }

        } else {
            return array("status" => false, "erro" => "Campo 12 do registro 60 deve ser igual a 1 ou 2");
        }
    }

    //campos 11,12,13,15,16,17
    function isFieldValid($allowedSize, $value, $Reg60Field12, $Reg70Field5)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            if ($Reg70Field5 == 1) {
                if (strlen($value) <= $allowedSize) {
                    return array("status" => true, "erro" => "");
                } else {
                    return array("status" => false, "erro" => "Campo com tamanho incorreto");
                }
            } else {
                return array("status" => false, "erro" => "Campo deve ser nulo");
            }
        } else {
            return array("status" => false, "erro" => "Campo 12 do registro 60 deve ser igual a 1 ou 2");
        }
    }

    //campo 18
    function isCivilRegisterNumberValid($value, $birthday)
    {
        if (strlen($value) != 32) {
            return array("status" => false, "erro" => "com número de caracteres inválido.");
        } else {
            for ($i = 0; $i <= strlen($value) - 1; $i++) {
                $char = $value[$i];
                if (($i < 30 && !is_numeric($char)) || ($i >= 30 && (!is_numeric($char) && strtoupper($char) != "X"))) {
                    return array("status" => false, "erro" => "apenas números devem ser informados, podendo também inserir XX nos dois últimos caracteres.");
                }
            }
        }
        if (substr($value, 10, 4) > date("Y")) {
            return array("status" => false, "erro" => "com o ano de registro (dígitos de 11 a 14) posterior ao ano corrente.");
        }
        if (substr($value, 10, 4) < substr($birthday, 6, 4)) {
            return array("status" => false, "erro" => "com o ano de registro (dígitos de 11 a 14) anterior ao ano de nascimento.");
        }
        return array("status" => true, "erro" => "");
    }

    //campo 19
    function isCPFValid($cpfStr)
    {
        if ($cpfStr !== "") {
            $cpf = "$cpfStr";
            if (strpos($cpf, "-") !== false) {
                $cpf = str_replace("-", "", $cpf);
            }
            if (strpos($cpf, ".") !== false) {
                $cpf = str_replace(".", "", $cpf);
            }
            $sum = 0;
            $cpf = str_split($cpf);
            $cpftrueverifier = array();
            $cpfnumbers = array_splice($cpf, 0, 9);
            $cpfdefault = array(10, 9, 8, 7, 6, 5, 4, 3, 2);
            for ($i = 0; $i <= 8; $i++) {
                $sum += $cpfnumbers[$i] * $cpfdefault[$i];
            }
            $sumresult = $sum % 11;
            if ($sumresult < 2) {
                $cpftrueverifier[0] = 0;
            } else {
                $cpftrueverifier[0] = 11 - $sumresult;
            }
            $sum = 0;
            $cpfdefault = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
            $cpfnumbers[9] = $cpftrueverifier[0];
            for ($i = 0; $i <= 9; $i++) {
                $sum += $cpfnumbers[$i] * $cpfdefault[$i];
            }
            $sumresult = $sum % 11;
            if ($sumresult < 2) {
                $cpftrueverifier[1] = 0;
            } else {
                $cpftrueverifier[1] = 11 - $sumresult;
            }
            $returner = false;
            if ($cpf == $cpftrueverifier) {
                $returner = true;
            }


            $cpfver = array_merge($cpfnumbers, $cpf);

            if (count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0)) {

                $returner = false;

            }
            if (!$returner) {
                return array("status" => false, "erro" => "'$cpfStr' inválido.");
            }
        }
        return array("status" => true, "erro" => "");
    }

    //campo 20
    function isPassportValid($passport, $Reg60Field12)
    {
        if ($Reg60Field12 == 3) {
            if (strlen($passport) > 20) {
                return array("status" => false, "erro" => "Passaporte com tamanho incorreto");
            }
        }

        return array("status" => true, "erro" => "");
    }

    //campo 21
    function isNISValid($nis)
    {
        if (strlen($nis) != 11) {
            return array("status" => false, "erro" => "NIS tem tamanho inválido");
        } else if ($nis == 00000000000) {
            return array("status" => false, "erro" => "O NIS foi preenchido com valor inválido.");
        } else if (!preg_match('/^[0-9]{11}$/', $nis)) {
            return array("status" => false, "erro" => "O NIS foi preenchido com valor inválido.");
        }

        return array("status" => true, "erro" => "");
    }

    //campo 22
    function isAreaOfResidenceValid($area_of_residence)
    {
        if ($area_of_residence != 1 && $area_of_residence != 2) {
            return array("status" => false, "erro" => "O campo Localizacao/Area de Residencia  foi preenchido com valor inválido.");
        }

        return array("status" => true, "erro" => "");

    }

    //campo 23
    function isCEPValid($cep)
    {
        if ($cep == null) {
            return array("status" => false, "erro" => "O campo CEP é uma informação obrigatória.");
        } else if (strlen($cep) != 8) {
            return array("status" => false, "erro" => "O campo CEP está com tamanho diferente do especificado.");
        } else if (!is_numeric($cep)) {
            return array("status" => false, "erro" => "O campo CEP foi preenchido com valor inválido.");
        } else if (!preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $cep)) {
            return array("status" => false, "erro" => "O campo CEP foi preenchido com valor inválido.");
        }

        return array("status" => true, "erro" => "");
    }

    //campo 24,25,26,27,28,29
    function isAdressValid($field, $cep, $allowed_lenght)
    {
        $regex = "/^[0-9 a-z.,-ºª ]+$/";
        if ($cep == null) {
            if ($field == null) {
                return array("status" => false, "erro" => "O campo não pode ser nulo.");
            }
        } else if (strlen($field) > $allowed_lenght || strlen($field) <= 0) {
            return array("status" => false, "erro" => "O campo está com tamanho incorreto.");
        } else if (!preg_match($regex, $field)) {
            return array("status" => false, "erro" => "O campo foi preenchido com valor inválido.");
        } else if ($field == null) {
            return array("status" => false, "erro" => "O campo não pode ser nulo.");
        }
        return array("status" => true, "erro" => "");
    }
}

?>
