<?php

enum RegisterIdentificationType
{
    case STUDENT;
    case INSTRUCTOR;
}

class RegisterIdentification
{
    private const EDCENSO_COD_NA_UNIDADE = 1;
    private const EDCENSO_CPF = 2;
    private const EDCENSO_CERT_NASCIMENTO = 3;
    private const EDCENSO_NOME = 4;
    private const EDCENSO_DATA_NASCIMENTO = 5;
    private const EDCENSO_FILIATION_1 = 6;
    private const EDCENSO_FILIATION_2 = 7;
    private const EDCENSO_MUN_NASCIMENTO = 8;
    private const EDCENSO_INEP_ID = 9;

    private static function fixName($name)
    {
        if (!isset($name)) {
            return null;
        }

        return preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($name));
    }


    private static function getStudents($classroom, $students)
    {
        if (count($classroom->instructorTeachingDatas) >= 1) {
            foreach ($classroom->studentEnrollments as $ienrollment => $enrollment) {
                if ($enrollment->isActive()) {
                    if (!isset($students[$enrollment->student_fk])) {
                        $students[$enrollment->student_fk]['identification'] = $enrollment->studentFk->attributes;
                        $students[$enrollment->student_fk]['documents'] = $enrollment->studentFk->documentsFk->attributes;
                    }
                }
            }
        }
        return $students;
    }

    private static function getInstructors($instructorsTeachingDatas, $instructors)
    {
        foreach ($instructorsTeachingDatas as $iteaching => $teachingData) {
            if (!isset($instructors[$teachingData->instructor_fk])) {
                $instructors[$teachingData->instructor_fk]['identification'] = $teachingData->instructorFk->attributes;
                $instructors[$teachingData->instructor_fk]['documents'] = $teachingData->instructorFk->documents->attributes;
            }
        }

        return $instructors;
    }

    private static function exportPerson($person, $type)
    {
        $register = [];

        $identification = $person['identification'];
        $documents = $person['documents'];


        if($type === RegisterIdentificationType::INSTRUCTOR){
            $register[self::EDCENSO_COD_NA_UNIDADE] = 'II' . $identification['id'];
        } else {
            $register[self::EDCENSO_COD_NA_UNIDADE] = $identification['id'];
        }

        $register[self::EDCENSO_CPF] = $documents['cpf'];
        $register[self::EDCENSO_CERT_NASCIMENTO] = self::validarMatriculaRegistroCivil($documents['civil_register_enrollment_number']) ? $documents['civil_register_enrollment_number'] : null;
        $register[self::EDCENSO_NOME] = self::fixName($identification['name']);
        $register[self::EDCENSO_DATA_NASCIMENTO] = $identification['birthday'];
        $register[self::EDCENSO_FILIATION_1] = self::fixName($identification['filiation_1']);
        $register[self::EDCENSO_FILIATION_2] = self::fixName($identification['filiation_2']);
        $register[self::EDCENSO_MUN_NASCIMENTO] = $identification['edcenso_city_fk'];
        $register[self::EDCENSO_INEP_ID] = null;



        return $register;
    }



    public static function export()
    {
        $registers = [];

        $classrooms = Classroom::model()->findAllByAttributes(['school_year' => Yii::app()->user->year]);

        $students = [];
        $instructors = [];
        foreach ($classrooms as $iclass => $attributes) {
            $students = self::getStudents($attributes, $students);
            $instructors = self::getInstructors($attributes->instructorTeachingDatas, $instructors);
        }

        foreach ($instructors as $instructor) {
            $register = self::exportPerson($instructor, RegisterIdentificationType::INSTRUCTOR);
            array_push($registers, implode('|', $register));
        }

        foreach ($students as $student) {
            $register = self::exportPerson($student, RegisterIdentificationType::STUDENT);
            array_push($registers, implode('|', $register));
        }

        sort($registers, SORT_STRING);

        return $registers;
    }

    public static function import($identificationFile)
    {
        set_time_limit(0);
        ignore_user_abort();

        $errors = [];
        $success = 0;
        $fail = 0;

        $file = fopen($identificationFile, 'r');
        if (!$file) {
            return [
                'success' => 0,
                'fail' => 0,
                'errors' => ['O arquivo não pôde ser aberto.']
            ];
        }

        $lineNumber = 0;

        while (($line = fgets($file)) !== false) {
            $lineNumber++;

            if (trim($line) === '')
                continue;

            $fields = array_map('trim', explode('|', $line));

            if (self::updateStudents($fields, $errors, $lineNumber, $line)) {
                $success++;
            } else {
                $fail++;
            }
        }

        fclose($file);

        return [
            'success' => $success,
            'fail' => $fail,
            'errors' => $errors
        ];
    }

    public static function updateStudents(array $register, array &$errors = [], int $lineNumber = 0, string $rawLine = '')
    {
        if (!isset($register[self::EDCENSO_COD_NA_UNIDADE], $register[self::EDCENSO_INEP_ID])) {
            $errors[] = "Linha {$lineNumber}: campos obrigatórios ausentes. Conteúdo: {$rawLine}";
            return false;
        }

        $student = StudentIdentification::model()->findByPk($register[self::EDCENSO_COD_NA_UNIDADE]);

        if ($student === null) {
            $errors[] = "Linha {$lineNumber}: aluno com código {" . $register[self::EDCENSO_COD_NA_UNIDADE] . "} não encontrado. Conteúdo: {$rawLine}";
            return false;
        }

        $student->inep_id = $register[self::EDCENSO_INEP_ID];

        if (!$student->save()) {
            $errorDetails = CVarDumper::dumpAsString($student->getErrors());
            $errors[] = "Linha {$lineNumber}: erro ao salvar aluno ID {$student->id}. Erros: {$errorDetails}. Conteúdo: {$rawLine}";
            return false;
        }

        return true;
    }

    public static function validarMatriculaRegistroCivil($matricula): bool
    {
        // Remove caracteres não numéricos
        if($matricula == null){
            return false;
        }

        $matricula = preg_replace('/\D/', '', $matricula);

        // Verifica se tem exatamente 32 dígitos
        if (strlen($matricula) !== 32) {
            return false;
        }

        return self::validarCodigoAcervo($matricula);
    }

    public static function validarCodigoAcervo(string $matricula): bool
    {

        $codigoAcervo = substr($matricula, 6, 2);
        $anoRegistro = intval(substr($matricula, 10, 4));

        // Acervo "02" só permitido até 2009
        if ($codigoAcervo == '02' && $anoRegistro > 2009) {
            return false;
        }

        return true;
    }
}
