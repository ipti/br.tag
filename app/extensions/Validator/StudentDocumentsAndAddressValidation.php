<?php

$DS = DIRECTORY_SEPARATOR;

require_once dirname(__FILE__) . $DS . 'register.php';

//registro 70
class StudentDocumentsAndAddressValidation extends Register
{
    //campo 5
    public function isRgNumberValid($rg, $Reg60Field12)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            if (strlen($rg) > 20) {
                return ['status' => false, 'erro' => 'Numero da Identidade não está com tamanho correto'];
            } elseif (!preg_match('/^[0-9]{7}([- ]?[0-9]{1})?$/', $rg)) {
                return ['status' => false, 'erro' => 'Numero da Identidade está com padrão incorreto'];
            } else {
                return ['status' => true, 'erro' => 'Campo 12 do registro 60 deve ser igual a 1 ou 2'];
            }
        } elseif (preg_match('/^[0-9]{7}([- ]?[0-9]{1})?$/', $rg)) {
            return ['status' => true, 'erro' => ''];
        } else {
            return ['status' => false, 'erro' => 'Numero da Identidade está com padrão incorreto'];
        }
    }

    //campo 6
    public function isRgEmissorOrganValid($EmissorOrgan, $Reg60Field12, $Reg70Field5)
    {
        if (strlen($Reg70Field5) != 0) {
            if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
                if (strlen($EmissorOrgan) == 0) {
                    return [
                        'status' => false,
                        'erro' => ' Orgao emissor deve ser  preenchido'
                    ];
                }
                if (strlen($EmissorOrgan) != 2) {
                    return [
                        'status' => false,
                        'erro' => ' Orgao emissor preenchido com tamanho inválido'
                    ];
                } else {
                    return ['status' => true, 'erro' => ''];
                }
            } else {
                return ['status' => false, 'erro' => ' Campo 12 do registro 60 deve ser igual a 1 ou 2'];
            }
        } //deve ser nulo quando campo 5 for nulo
        else {
            if (strlen($EmissorOrgan) != 0) {
                return ['status' => false, 'erro' => ' Orgao emissor deve ser nulo'];
            }
        }
    }

    //campo 7
    public function isRgUfValid($rgUF, $Reg60Field12, $Reg70Field5)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            //os campos 5 e 6 devem ser preenchidos
            if (strlen($Reg70Field5) != 0) {
                if (strlen($rgUF) == 0) {
                    return ['status' => false, 'erro' => 'UF da identidade deve ser  preenchido'];
                } else {
                    return ['status' => true, 'erro' => ''];
                }

                if (strlen($rgUF) != 2) {
                    return ['status' => false, 'erro' => 'UF da identidade preenchido com tamanho inválido'];
                } else {
                    return ['status' => true, 'erro' => ''];
                }
            } else {
                if (strlen($rgUF) != 0) {
                    return ['status' => false, 'erro' => 'UF da identidade deve ser nulo'];
                } else {
                    return ['status' => true, 'erro' => ''];
                }
            }
        } else {
            return ['status' => false, 'erro' => 'Campo 12 do registro 60 deve ser igual a 1 ou 2'];
        }
    }

    //campos 8 e 14
    public function isDateValid($Reg60Field12, $expeDate, $birthDate, $currentDate, $Reg70Field9, $currentField)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            //SE FOR PARA O CAMPO 8 COM CAMPO 9 SERÁ 0 | PARA O CAMPO 14 COM CAMPO 9 SENDO 1
            if (($currentField == 8) || ($currentField == 14 && $Reg70Field9 == 1)) {
                if ($currentField == 14 && $Reg70Field9 == 0) {
                    return ['status' => false, 'erro' => 'Campo 9 deve ser 1'];
                } elseif (self::dateValid($expeDate) == true) {
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
                            return ['status' => true, 'erro' => ''];
                        }
                        if ($anoExpedicao > $anoAtual) {
                            return ['status' => false, 'erro' => 'Data de expedicao superior a data atual'];
                        }
                        if ($anoExpedicao == $anoAtual) {
                            //comparar os meses
                            if ($mesExpedicao < $mesAtual) {
                                return ['status' => true, 'erro' => ''];
                            }
                            if ($mesExpedicao > $mesAtual) {
                                return ['status' => false, 'erro' => 'Data de expedicao superior a data atual'];
                            }

                            if ($mesExpedicao == $mesAtual) {
                                //comparar dias
                                if ($diaExpedicao < $diaAtual) {
                                    return ['status' => true, 'erro' => ''];
                                }
                                if ($diaExpedicao >= $diaAtual) {
                                    return ['status' => false, 'erro' => 'Data de expedicao superior a data atual'];
                                }
                            }
                        }
                    }
                    if ($anoExpedicao < $anoNasceu) {
                        return ['status' => false, 'erro' => 'Data de expedicao inferior a data de nascimento'];
                    }
                    if ($anoExpedicao == $anoNasceu) {
                        //comparar os meses
                        if ($mesExpedicao > $mesNasceu) {
                            return ['status' => true, 'erro' => ''];
                        }
                        if ($mesExpedicao < $mesNasceu) {
                            return ['status' => false, 'erro' => 'Data de expedicao inferior a data de nascimento'];
                        }
                        if ($mesExpedicao == $mesNasceu) {
                            //comparar os dias
                            if ($diaExpedicao > $diaNasceu) {
                                return ['status' => true, 'erro' => ''];
                            } else {
                                return ['status' => false, 'erro' => 'Data de expedicao inferior a data de nascimento'];
                            }
                        }
                    }
                } else {
                    return ['status' => false, 'erro' => 'Data de expedicao no formato incorreto'];
                }
            }
        } else {
            return ['status' => false, 'erro' => 'Campo 12 do registro 60 deve ser igual a 1 ou 2'];
        }
    }

    //auxiliar do campo 8
    public function dateValid($date)
    {
        $data = explode('/', $date);
        $dia = (int) $data[0];
        $mes = (int) $data[1];
        $ano = (int) $data[2];

        // verifica se a data é valida
        if (!checkdate($mes, $dia, $ano)) {
            return ['status' => false, 'erro' => 'Data no formato incorreto'];
        } else {
            return ['status' => true, 'erro' => ''];
        }
    }

    //campo 9
    public function isCivilCertificationValid($Reg70Field5, $Reg60Field12)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            if ($Reg70Field5 == 1 || $Reg70Field5 == 2) {
                return ['status' => true, 'erro' => ''];
            } else {
                return ['status' => false, 'erro' => 'Certificacao Civil deve ser igual a 1 ou 2'];
            }
        } else {
            return ['status' => false, 'erro' => 'Campo 12 do registro 60 deve ser igual a 1 ou 2'];
        }
    }

    //campo 10
    public function isCivilCertificationTypeValid($type, $Reg70Field5, $Reg60Field12, $birthday, $currentDate)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            if ($type == 1) {
                return ['status' => true, 'erro' => ''];
            } elseif ($type == 2) {
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
                } elseif ($mesAtual == $mesNiver and $diaAtual < $diaNiver) {
                    $idade--;
                }

                if ($idade <= 10) {
                    return ['status' => false, 'erro' => 'Aluno com menos de 10 anos não pode ter certidão de casamento.'];
                }

                return ['status' => true, 'erro' => ''];
            } else {
                return ['status' => false, 'erro' => 'O campo certidão civil deve ser preenchido'];
            }
        } else {
            return ['status' => false, 'erro' => 'Campo 12 do registro 60 deve ser igual a 1 ou 2'];
        }
    }

    //campos 11,12,13,15,16,17
    public function isFieldValid($allowedSize, $value, $Reg60Field12, $Reg70Field5)
    {
        if ($Reg60Field12 == 1 || $Reg60Field12 == 2) {
            if ($Reg70Field5 == 1) {
                if (strlen($value) <= $allowedSize) {
                    return ['status' => true, 'erro' => ''];
                } else {
                    return ['status' => false, 'erro' => 'Campo com tamanho incorreto'];
                }
            } else {
                return ['status' => false, 'erro' => 'Campo deve ser nulo'];
            }
        } else {
            return ['status' => false, 'erro' => 'Campo 12 do registro 60 deve ser igual a 1 ou 2'];
        }
    }

    //campo 18
    public function isCivilRegisterNumberValid($value, $birthday)
    {
        if (strlen($value) != 32) {
            return ['status' => false, 'erro' => 'com número de caracteres inválido.'];
        } else {
            for ($i = 0; $i <= strlen($value) - 1; $i++) {
                $char = $value[$i];
                if (($i < 30 && !is_numeric($char)) || ($i >= 30 && (!is_numeric($char) && strtoupper($char) != 'X'))) {
                    return ['status' => false, 'erro' => 'apenas números devem ser informados, podendo também inserir XX nos dois últimos caracteres.'];
                }
            }
        }
        if (substr($value, 10, 4) > date('Y')) {
            return ['status' => false, 'erro' => 'com o ano de registro (dígitos de 11 a 14) posterior ao ano corrente.'];
        }
        if (substr($value, 10, 4) < substr($birthday, 6, 4)) {
            return ['status' => false, 'erro' => 'com o ano de registro (dígitos de 11 a 14) anterior ao ano de nascimento.'];
        }

        if (!$this->validateCertidao($value)) {
            return ['status' => false, 'erro' => 'O número da matrícula da certidão inserida é inválido.'];
        }

        return ['status' => true, 'erro' => ''];
    }

    /**
     * Valida se a CERTIDÃO é válida (nascimento, casamento, óbito)
     * @param string $value
     * @return bool
     */
    private function validateCertidao($value)
    {
        if (!preg_match('/[0-9]{32}/', $value)) {
            return false;
        }

        $num = substr($value, 0, -2);

        $dv = substr($value, -2);

        $dv1 = $this->weightedSumCertidao($num) % 11;

        $dv1 = $dv1 > 9 ? 1 : $dv1;

        $dv2 = $this->weightedSumCertidao($num . $dv1) % 11;

        $dv2 = $dv2 > 9 ? 1 : $dv2;

        // Compara o dv recebido com os dois numeros calculados
        if ($dv === $dv1 . $dv2) {
            return true;
        }

        return false;
    }

    /**
     * @param $value
     * @return int
     */
    private function weightedSumCertidao($value): int
    {
        $sum = 0;

        $multiplier = 32 - mb_strlen($value);

        for ($i = 0; $i < mb_strlen($value); $i++) {
            $sum += $value[$i] * $multiplier;
            $multiplier += 1;
            $multiplier = $multiplier > 10 ? 0 : $multiplier;
        }

        return $sum;
    }

    //campo 19
    public function isCPFValid($cpfStr)
    {
        if ($cpfStr !== '') {
            $cpf = "$cpfStr";
            if (strpos($cpf, '-') !== false) {
                $cpf = str_replace('-', '', $cpf);
            }
            if (strpos($cpf, '.') !== false) {
                $cpf = str_replace('.', '', $cpf);
            }
            $sum = 0;
            $cpf = str_split($cpf);
            $cpftrueverifier = [];
            $cpfnumbers = array_splice($cpf, 0, 9);
            $cpfdefault = [10, 9, 8, 7, 6, 5, 4, 3, 2];
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
            $cpfdefault = [11, 10, 9, 8, 7, 6, 5, 4, 3, 2];
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

            if (count(array_unique($cpfver)) == 1 || $cpfver == [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0]) {
                $returner = false;
            }
            if (!$returner) {
                return ['status' => false, 'erro' => "'$cpfStr' inválido."];
            }
        }
        return ['status' => true, 'erro' => ''];
    }

    //campo 20
    public function isPassportValid($passport, $Reg60Field12)
    {
        if ($Reg60Field12 == 3) {
            if (strlen($passport) > 20) {
                return ['status' => false, 'erro' => 'Passaporte com tamanho incorreto'];
            }
        }

        return ['status' => true, 'erro' => ''];
    }

    //campo 21
    public function isNISValid($nis)
    {
        if (strlen($nis) != 11) {
            return ['status' => false, 'erro' => 'NIS tem tamanho inválido'];
        } elseif ($nis == 00000000000) {
            return ['status' => false, 'erro' => 'O NIS foi preenchido com valor inválido.'];
        } elseif (!preg_match('/^[0-9]{11}$/', $nis)) {
            return ['status' => false, 'erro' => 'O NIS foi preenchido com valor inválido.'];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function isAreaOfResidenceValid($area_of_residence)
    {
        if ($area_of_residence != 1 && $area_of_residence != 2) {
            return ['status' => false, 'erro' => 'O campo foi preenchido com valor inválido.'];
        }

        return ['status' => true, 'erro' => ''];
    }

    //campo 23
    public function isCEPValid($cep)
    {
        if ($cep == null) {
            return ['status' => false, 'erro' => 'O campo CEP é uma informação obrigatória.'];
        } elseif (strlen($cep) != 8) {
            return ['status' => false, 'erro' => 'O campo CEP está com tamanho diferente do especificado.'];
        } elseif (!is_numeric($cep)) {
            return ['status' => false, 'erro' => 'O campo CEP foi preenchido com valor inválido.'];
        } elseif (!preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $cep)) {
            return ['status' => false, 'erro' => 'O campo CEP foi preenchido com valor inválido.'];
        }

        return ['status' => true, 'erro' => ''];
    }

    public function cepVerify($cityFk, $studentZipCode)
    {
        if (!isset($cityFk)) {
            return ['status' => false, 'erro' => 'O município do aluno não foi adicionado.'];
        }
        $city = EdcensoCity::model()->findByPk($cityFk);
        $result = $city->validateContainsCep($studentZipCode);
        if (!$result) {
            return ['status' => false, 'erro' => 'O CEP do aluno não corresponde à faixa do município selecionado.'];
        }
    }

    //campo 24,25,26,27,28,29
    public function isAdressValid($field, $cep, $allowed_lenght)
    {
        $regex = '/^[0-9 a-z.,-ºª ]+$/';
        if ($cep == null) {
            if ($field == null) {
                return ['status' => false, 'erro' => 'O campo não pode ser nulo.'];
            }
        } elseif (strlen($field) > $allowed_lenght || strlen($field) <= 0) {
            return ['status' => false, 'erro' => 'O campo está com tamanho incorreto.'];
        } elseif (!preg_match($regex, $field)) {
            return ['status' => false, 'erro' => 'O campo foi preenchido com valor inválido.'];
        } elseif ($field == null) {
            return ['status' => false, 'erro' => 'O campo não pode ser nulo.'];
        }
        return ['status' => true, 'erro' => ''];
    }
}
