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

    public function validatorMenu($school){
        $inconsistencies = [];
        $menus = $school->getCardapio();
        foreach($menus as $menu){
            if(!in_array($menu->getTurno(), [1,2,3,4])){
                $inconsistencies[] = [
                    "enrollment" => 'CARDÁPIO', 
                    "school" => $school->getIdEscola(), 
                    "description" => 'TURNO INVÁLIDO', 
                    "action" => 'INFORMAR UM TURNO VÁLIDO PARA O TURNO'
                ];
            }
        }

        return  array_unique($inconsistencies);     
    }

    function validaCPF($cpf) {
 
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
         
        if (strlen($cpf) != 11) {return false;}

        if (preg_match('/(\d)\1{10}/', $cpf)) {return false;}

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
}