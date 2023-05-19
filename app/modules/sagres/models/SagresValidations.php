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

        return $inconsistencies;
    }

    public function validatorClass($school)
    {

        $strlen = 2;
        $inconsistencies = [];
        $classes = $school->getTurma();   
        $schoolId = $school->getIdEscola();

        foreach ($classes as $class) {

             /* 
             *  [0 : Anual], [1 : 1°], [2 : 2º] Semestre
             */	
            if (!in_array($class->getPeriodo(), [0, 1, 2])) {
                $inconsistencies[] = [
                    "enrollment" => 'TURMA',
                    "school" => $school->getIdEscola(),
                    "description" => 'VALOR INVÁLIDO PARA O PERÍODO',
                    "action" => 'FORNEÇA UM VALOR VÁLIDO PARA O PERÍODO'
                ];
            }

            if (strlen($class->getDescricao()) <= $strlen) {
                $inconsistencies[] = [
                    "enrollment" => 'TURMA',
                    "school" => $school->getIdEscola(),
                    "description" => 'DESCRIÇÃO PARA A TURMA MENOR QUE 3 CARACTERES',
                    "action" => 'FORNEÇA UMA DESCRIÇÃO MAIS DETALHADA, CONTENDO MAIS DE 5 CARACTERES'
                ];
            }

            if (!in_array($class->getTurno(), [1, 2, 3, 4])) {
                $inconsistencies[] = [
                    "enrollment" => 'TURMA',
                    "school" => $school->getIdEscola(),
                    "description" => 'VALOR INVÁLIDO PARA O TURNO DA TURMA',
                    "action" => 'SELECIONE UM TURNO VÁLIDO PARA O HORÁRIO DE FUNCIONAMENTO'
                ];
            }

            $inconsistencies = array_merge($inconsistencies, $this->validationSeries($class, $schoolId));
            $inconsistencies = array_merge($inconsistencies, $this->validatorEnrollments($class, $schoolId));
        }

        return $inconsistencies;
    }


    public function validationSeries($class, $schoolId)
    {
        $strlen = 2;
        $inconsistencies = [];
        $series = $class->getSerie();

        foreach ($series as $serie) {
            if (strlen($serie->getDescricao()) <= $strlen) {
                $inconsistencies[] = [
                    "enrollment" => 'SÉRIE',
                    "school" => $schoolId,
                    "description" => 'DESCRIÇÃO PARA A SÉRIE MENOR QUE 3 CARACTERES',
                    "action" => 'FORNEÇA UMA DESCRIÇÃO MAIS DETALHADA, CONTENDO MAIS DE 5 CARACTERES'
                ];
            }

            /* [1, 2, 3, 4]
             * 1 - Educação Infantil -->
             * 2 - Ensino Fundamental -->
             * 3 - Ensino Médio -->				
             * 4 - Educação de Jovens e Adultos -->
             */
            if (!in_array($serie->getModalidade(), [1, 2, 3, 4])) {
                $inconsistencies[] = [
                    "enrollment" => 'SÉRIE',
                    "school" => $schoolId,
                    "description" => 'MODALIDADE INVÁLIDA',
                    "action" => 'SELECIONE UMA MODALIDADE VÁLIDA'
                ];
            }
        };

        return $inconsistencies;
    }

    public function validatorEnrollments($class, $schoolId)
    {
       $inconsistencies = [];
       $enrollments = $class->getMatricula();

       foreach ($enrollments as $enrollment) {
            if(!$this->validateDate($enrollment->getDataMatricula()->format("Y-m-d"))){
                $inconsistencies[] = [
                    "enrollment" => 'MATRÍCULA',
                    "school" => $schoolId,
                    "description" => 'DATA NO FORMATO INVÁLIDO',
                    "action" => 'ADICIONE UMA DATA NO FORMATO VÁLIDA'
                ];
            }

            if(!is_int($enrollment->getNumeroFaltas())){
                $inconsistencies[] = [
                    "enrollment" => 'MATRÍCULA',
                    "school" => $schoolId,
                    "description" => 'O VALOR PARA O NÚMERO DE FALTAS É INVÁLIDO',
                    "action" => 'COLOQUE UM VALOR VÁLIDO PARA O NÚMERO DE FALTAS'
                ];
            }

            if(!is_bool($enrollment->getAprovado())){
                $inconsistencies[] = [
                    "enrollment" => 'MATRÍCULA',
                    "school" => $schoolId,
                    "description" => 'STATUS DO APROVADO DO ALUNO É INVÁLIDO',
                    "action" => 'MARQUE COMO APROVADO OU REPROVADO NO STATUS'
                ];
            }       

            $inconsistencies = array_merge($inconsistencies, $this->validationStudent($enrollment->getAluno(),  $schoolId));

       }

       return $inconsistencies;
    }
    
    public function validationStudent($student, $schoolId)
    {
        $strlen = 5;
        $inconsistencies = [];

        if(!$this->validaCPF($student->getCpfAluno()) || is_null($student->getCpfAluno())){
            $inconsistencies[] = [
                "enrollment" => 'ESTUDANTE',
                "school" => $schoolId,
                "description" => 'CPF DO ESTUDANTE É INVÁLIDO',
                "action" => 'INFORME UM CPF VÁLIDO PARA O ESTUDANTE'
            ];
        }

        if(!$this->validateDate($student->getDataNascimento()->format("Y-m-d"))){
            $inconsistencies[] = [
                "enrollment" => 'ESTUDANTE',
                "school" => $schoolId,
                "description" => 'DATA NO FORMATO INVÁLIDO',
                "action" => 'ADICIONE UMA DATA NO FORMATO VÁLIDA'
            ];
        }

        if(strlen($student->getNome()) < $strlen){
            $inconsistencies[] = [
                "enrollment" => 'ESTUDANTE',
                "school" => $schoolId,
                "description" => 'TAMANHO DO NOME DO ESTUDANTE MENOR QUE 5 CARACTERES',
                "action" => 'ADICIONE UM NOME PARA O ESTUDANTE COM PELO MENOS 5 CARACTERES'
            ];
        }

        if(!is_bool($student->getPcd())){
            $inconsistencies[] = [
                "enrollment" => 'ESTUDANTE',
                "school" => $schoolId,
                "description" => 'CÓDIGO PCD É INVÁLIDO',
                "action" => 'ADICIONE UM VALOR VÁLIDO PARA O PCD'
            ];
        }

        if(!in_array($student->getSexo(), [1, 2, 3])){
            $inconsistencies[] = [
                "enrollment" => 'ESTUDANTE',
                "school" => $schoolId,
                "description" => 'SEXO NÃO É VÁLIDO',
                "action" => 'ADICIONE UM SEXO VÁLIDO PARA O ESTUDANTE'
            ];
        }
        
        return $inconsistencies;
    }

// Função de callback personalizada para filtrar arrays vazios
function filterEmptyArrays($value) {
    return !empty($value);
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