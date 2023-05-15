<?php
use Respect\Validation\Validator;
use Yii;

class SagresValidations
{

    public function validator($schools)
    {
        $query = "delete from inconsistency_sagres";
        Yii::app()->db->createCommand($query)->execute();

        $inconsistencyList = [];

        foreach ($schools as $school) {
            $inconsistencyList = array_merge($inconsistencyList, $this->validatorSchoolDirector($school));
            $inconsistencyList = array_merge($inconsistencyList, $this->validatorMenu($school));
            $inconsistencyList = array_merge($inconsistencyList, $this->validatorClass($school));
        }

        return $inconsistencyList;
    }

    function validatorSchoolDirector($school)
    {
        $inconsistencies = [];

        if ($school->getDiretor()->getNrAto() == null) {
            $inconsistencies[] = [
                "enrollment" => 'DIRETOR',
                "school" => $school->getIdEscola(),
                "description" => 'NR-ATO NÃO PODE SER VAZIO',
                "action" => 'INFORMAR UM NR-ATO PARA O DIRETOR'
            ];
        }

        if ($school->getDiretor()->getCpfDiretor() == null || !preg_match('/^[0-9]{11}$/', $school->getDiretor()->getCpfDiretor())) {
            $inconsistencies[] = [
                "enrollment" => 'DIRETOR',
                "school" => $school->getIdEscola(),
                "description" => 'CPF NÃO CADASTRADO OU CPF NO FORMATO INVÁLIDO PARA O DIRETOR',
                "action" => 'INFORMAR UM CPF VÁLIDO PARA O DIRETOR'
            ];
        }

        if (!$this->validaCPF($school->getDiretor()->getCpfDiretor())) {
            $inconsistencies[] = [
                "enrollment" => 'DIRETOR',
                "school" => $school->getIdEscola(),
                "description" => 'CPF DO DIRETOR INVÁLIDO',
                "action" => 'INFORMAR UM CPF VÁLIDO PARA O DIRETOR'
            ];
        }
        return $inconsistencies;
    }

    public function validatorMenu($school)
    {
        $strlen = 4;
        $inconsistencies = [];
        $menus = $school->getCardapio();

        foreach ($menus as $menu) {

            if (!in_array($menu->getTurno(), [1, 2, 3, 4])) {
                $inconsistencies[] = [
                    "enrollment" => 'CARDÁPIO',
                    "school" => $school->getIdEscola(),
                    "description" => 'TURNO INVÁLIDO',
                    "action" => 'INFORMAR UM TURNO VÁLIDO PARA O TURNO'
                ];
            }

            if (strlen($menu->getDescricaoMerenda()) <= $strlen) {
                $inconsistencies[] = [
                    "enrollment" => 'CARDÁPIO',
                    "school" => $school->getIdEscola(),
                    "description" => 'DESCRIÇÃO PARA MERENDA MENOR QUE 5 CARACTERES',
                    "action" => 'INFORMAR UMA DESCRIÇÃO PARA MERENDA MAIOR QUE 4 CARACTERES'
                ];
            }

            if (!in_array($menu->getAjustado(), [0, 1])) { # 0: Not, 1: True
                $inconsistencies[] = [
                    "enrollment" => 'CARDÁPIO',
                    "school" => $school->getIdEscola(),
                    "description" => 'VALOR INVÁLIDO PARA O CAMPO AJUSTADO',
                    "action" => 'MARQUE OU DESMARQUE O CHECKBOX PARA O CAMPO AJUSTADO'
                ];
            }

            if (!$this->validateDate($menu->getData()->format("Y-m-d"))) {
                $inconsistencies[] = [
                    "enrollment" => 'CARDÁPIO',
                    "school" => $school->getIdEscola(),
                    "description" => 'DATA NO FORMATO INVÁLIDO',
                    "action" => 'ADICIONE UMA DATA NO FORMATO VÁLIDA'
                ];
            }
        }

        return array_unique($inconsistencies);
    }

    public function validatorClass($school)
    {

        $strlen = 2;
        $inconsistencies = [];
        $classes = $school->getTurma();

        foreach ($classes as $class) {

            if (!in_array($class->getTurno(), [1, 2, 3, 4])) {
                $inconsistencies[] = [
                    "enrollment" => 'TURMA',
                    "school" => $school->getIdEscola(),
                    "description" => 'VALOR INVÁLIDO PARA O TURNO DA TURMA',
                    "action" => 'ADICIONE UM VALOR VÁLIDO PARA O TURNO DA TURMA'
                ];
            }
            if (strlen($class->getDescricao()) <= $strlen) {
                $inconsistencies[] = [
                    "enrollment" => 'TURMA',
                    "school" => $school->getIdEscola(),
                    "description" => 'DESCRIÇÃO PARA A TURMA MENOR QUE 3 CARACTERES ',
                    "action" => 'INFORMAR UMA DESCRIÇÃO MAIOR QUE 2 CARACTERES'
                ];
            }
        }

        return array_unique($inconsistencies);
    }



    function validaCPF($cpf)
    {

        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}