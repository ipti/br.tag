<?php
use Respect\Validation\Validator;
use Yii;

class SagresValidations
{

    public function validator($schools, $professionals, $finalClass)
    {
        $query = "delete from inconsistency_sagres";
        Yii::app()->db->createCommand($query)->execute();

        $inconsistencyList = [];

        foreach ($schools as $school) {

            $inconsistencyList = array_merge($inconsistencyList, $this->validatorSchoolDirector($school));
            $inconsistencyList = array_merge($inconsistencyList, $this->validatorMenu($school));
            $inconsistencyList = array_merge($inconsistencyList, $this->validatorClass($school, $finalClass));
            $inconsistencyList = array_merge($inconsistencyList, $this->validatorProfessionals($professionals, $school));
        }

        return $inconsistencyList;
    }

    public function validatorProfessionals($professionals, $school)
    {
        $inconsistencies = [];

        foreach ($professionals as $professional) {
            if (!$this->validaCPF($professional->getCpfProfissional())) {
                $inconsistencies[] = [
                    "enrollment" => 'PROFESSIONAL',
                    "school" => $school->getIdEscola(),
                    "description" => 'CPF INVÁLIDO: ' . $professional->getCpfProfissional(),
                    "action" => 'INFORMAR UM CPF VÁLIDO'
                ];
            }

            $inconsistencies = array_merge($inconsistencies,$this->validatorAttendance($professional, $school));
        }

        return array_unique($inconsistencies);
    }

    public function validatorAttendance($professional, $school)
    {
        $inconsistencies = [];
        $professionals = $professional->getAtendimento();

        foreach ($professionals as $professional) {       
            $dateOfAttendance = intval($professional->getData()->format("Y"));
            $currentDate = date('Y');
            
            if($dateOfAttendance <= ($currentDate - 3)){
                $inconsistencies[] = [
                    "enrollment" => 'ATENDIMENTO',
                    "school" => $school->getIdEscola(),
                    "description" => 'ANO DO ATENDIMENTO: ' . $professional->getData()->format("d/m/Y"). ' MENOR QUE: ' . ($currentDate - 3),
                    "action" => 'INFORMAR UM ANO PARA O ATENDIMENTO MAIOR QUE: ' . ($currentDate - 3)
                ];
            }
        }

        return $inconsistencies;
    }

    function validatorSchoolDirector($school)
    {
        $inconsistencies = [];

        if ($school->getDiretor()->getNrAto() == null) {
            $inconsistencies[] = [
                "enrollment" => 'DIRETOR',
                "school" => $school->getIdEscola(),
                "description" => 'NÚMERO DO ATO DE NOMEAÇÃO NÃO PODE SER VAZIO',
                "action" => 'INFORMAR UM NÚMERO DO ATO DE NOMEAÇÃO PARA O DIRETOR'
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

        if(is_null($inconsistencies)){
            $inconsistencies[] = [
                "enrollment" => 'DIRETOR',
                "school" => $school->getIdEscola(),
                "description" => 'NÃO EXISTE DIRETOR CADASTRADO PARA A ESCOLA: ' . $school->getIdEscola(),
                "action" => 'ADICIONE UM DIRETOR PARA A ESCOLA: ' . $school->getIdEscola()
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

    public function validatorClass($school, $finalClass)
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
                    "action" => 'ADICIONE UM VALOR VÁLIDO PARA O PERÍODO'
                ];
            }

            if (strlen($class->getDescricao()) <= $strlen && !is_null($class->getDescricao())) {
                $inconsistencies[] = [
                    "enrollment" => 'TURMA',
                    "school" => $school->getIdEscola(),
                    "description" => 'DESCRIÇÃO PARA A TURMA MENOR QUE 3 CARACTERES',
                    "action" => 'ADICIONE UMA DESCRIÇÃO MAIS DETALHADA, CONTENDO MAIS DE 5 CARACTERES'
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

            if(!is_bool($class->getFinalTurma())){
                $inconsistencies[] = [
                    "enrollment" => 'TURMA',
                    "school" => $school->getIdEscola(),
                    "description" => 'VALOR INVÁLIDO PARA O FINAL TURMA',
                    "action" => 'SELECIONE UM VALOR VÁLIDO PARA O ENCERRAMENTO DO PERÍODO'
                ];
            }

            $inconsistencies = array_merge($inconsistencies, $this->validationSeries($class, $schoolId));
            $inconsistencies = array_merge($inconsistencies, $this->validatorEnrollments($class, $schoolId, $finalClass));
            $inconsistencies = array_merge($inconsistencies, $this->validatorSchedules($class, $schoolId));
        }

        return $inconsistencies;
    }


    public function validationSeries($class, $schoolId)
    {
        $strlen = 2;
        $inconsistencies = [];
        $series = $class->getSerie();

        if(empty($series)){
            $inconsistencies[] = [
                "enrollment" => 'SÉRIE',
                "school" => $schoolId,
                "description" => 'NÃO HÁ SÉRIE PARA A ESCOLA: ' . $schoolId,
                "action" => 'ADICIONE UMA SÉRIE PARA A TURMA: ' . $class->getDescricao() .' DA ESCOLA: ' . $schoolId
            ];
        }else{
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
                        "action" => 'SELECIONE UMA MODALIDADE VÁLIDA PARA A SÉRIE'
                    ];
                }
            };
        }

        return $inconsistencies;
    }

    public function validatorEnrollments($class, $schoolId, $finalClass)
    {
        $inconsistencies = [];
        $enrollments = $class->getMatricula();

        if(empty($enrollments)){
            $inconsistencies[] = [
                "enrollment" => 'MATRÍCULA',
                "school" => $schoolId,
                "description" => 'NÃO HÁ MATRÍCULA PARA A TURMA',
                "action" => 'ADICIONE UMA MATRÍCULA PARA A TURMA: ' . $class->getDescricao() . ' DA ESCOLA: ' . $schoolId 
            ];
        }else{
            foreach ($enrollments as $enrollment) {
                if(!$this->validateDate($enrollment->getDataMatricula()->format("Y-m-d"))){
                    $inconsistencies[] = [
                        "enrollment" => 'MATRÍCULA',
                        "school" => $schoolId,
                        "description" => 'DATA NO FORMATO INVÁLIDO',
                        "action" => 'ADICIONE UMA DATA NO FORMATO VÁLIDO'
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
                if(filter_var($finalClass,  FILTER_VALIDATE_BOOLEAN)){
                    if(!is_bool($enrollment->getAprovado())){
                        $inconsistencies[] = [
                            "enrollment" => 'MATRÍCULA',
                            "school" => $schoolId,
                            "description" => 'VALOR INVÁLIDO PARA O STATUS APROVADO',
                            "action" => 'ADICIONE UM VALOR VÁLIDO PARA O CAMPO APROVADO DO ALUNO '.$enrollment->getAluno()->getNome().' NA TURMA: '. $class->getDescricao()
                        ];
                    }
                }

                $inconsistencies = array_merge($inconsistencies, $this->validationStudent($enrollment->getAluno(), $schoolId, $class->getDescricao()));
            }
        }

        return $inconsistencies;
    }

    public function validationStudent($student, $schoolId, $class)
    {
        $strlen = 5;
        $inconsistencies = [];

        if(!is_null($student->getCpfAluno())){
            if(!$this->validaCPF($student->getCpfAluno())){
                $inconsistencies[] = [
                    "enrollment" => 'ESTUDANTE',
                    "school" => $schoolId,
                    "description" => 'CPF DO ESTUDANTE É INVÁLIDO' ,
                    "action" => 'INFORME UM CPF VÁLIDO PARA O ESTUDANTE: '.$student->getCpfAluno()
                ];
            }
        }
        
        if(!$this->validateDate($student->getDataNascimento()->format("Y-m-d"))){
            $inconsistencies[] = [
                "enrollment" => 'ESTUDANTE',
                "school" => $schoolId,
                "description" => 'DATA NO FORMATO INVÁLIDO: ' . $student->getDataNascimento()->format("d/m/Y"),
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

        if (!is_bool(boolval($student->getPcd()))) {
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

        if(is_null($inconsistencies)){
            $inconsistencies[] = [
                "enrollment" => 'ESTUDANTE',
                "school" => $schoolId,
                "description" => 'ESTUDANTE NÃO EXISTE PARA A MATRÍCULA DA TURMA: ' . $class->getDescricao(),
                "action" => 'ADICIONE UM ESTUDANTE À TURMA DA ESCOLA: ' . $schoolId
            ];
        }

        return $inconsistencies;
    }



    public function validatorSchedules($class, $schoolId)
    {
        $strlen = 5;
        $maxLength = 50;
        $inconsistencies = [];
        $schedules = $class->getHorario();

        if(empty($schedules)){
            $inconsistencies[] = [
                "enrollment" => 'HORÁRIO',
                "school" => $schoolId,
                "description" => 'NÃO HÁ UM PROFESSOR, HORÁRIOS OU COMPONETES CURRICULARES PARA A TURMA: ' . $class->getDescricao() . ' DA ESCOLA: ' . $schoolId,
                "action" => 'ADICIONE UM PROFESSOR OU COMPONENTES CURRICULARES À TURMA'
            ];
        }else{
            foreach ($schedules as $schedule) {
                if (!in_array($schedule->getDiaSemana(), [1, 2, 3, 4, 5, 6, 7])) {
                    $inconsistencies[] = [
                        "enrollment" => 'HORÁRIO',
                        "school" => $schoolId,
                        "description" => 'DIA DA SEMANA INVÁLIDO',
                        "action" => 'ADICIONE UM DIA DA SEMANA VÁLIDO PARA A DISCIPLINA'
                    ];
                }
    
                if (!is_int($schedule->getDuracao())) {
                    $inconsistencies[] = [
                        "enrollment" => 'HORÁRIO',
                        "school" => $schoolId,
                        "description" => 'DURAÇÃO INVÁLIDA',
                        "action" => 'ADICIONE UMA DURAÇÃO VÁLIDA PARA DISCIPLINA'
                    ];
                }
    
                $cpfInstructor = $schedule->getCpfProfessor();
                if (!$this->validaCPF($cpfInstructor[0])) {
                    $inconsistencies[] = [
                        "enrollment" => 'HORÁRIO',
                        "school" => $schoolId,
                        "description" => 'CPF DO PROFESSOR É INVÁLIDO',
                        "action" => 'INFORMAR UM CPF VÁLIDO PARA O PROFESSOR'
                    ];
                }
                
                if (strlen($schedule->getDisciplina()) < $strlen) {
                    $inconsistencies[] = [
                        "enrollment" => 'HORÁRIO',
                        "school" => $schoolId,
                        "description" => 'NOME DA DISCIPLINA MUITO CURTA',
                        "action" => 'ADICIONE UM NOME PARA A DISCIPLINA COM PELO MENOS 5 CARACTERES'
                    ];
                }

                if (strlen($schedule->getDisciplina()) > $maxLength) {
                    $inconsistencies[] = [
                        "enrollment" => 'HORÁRIO',
                        "school" => $schoolId,
                        "description" => 'NOME DA DISCIPLINA COM MAIS DE 50 CARACTERES',
                        "action" => 'ADICIONE UM NOME PARA A DISCIPLINA COM ATÉ 50 CARACTERES'
                    ];
                }
            }
        }
        
        return $inconsistencies;
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
        if(intval($d->format('Y')) <= 1900)
            return false;
           
        return $d && $d->format($format) == $date;
    }
}