<?php

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

    private static function exportStudentIdentification($student)
    {
        $register = [];

        $identification = $student['identification'];
        $documents = $student['documents'];


        $register[self::EDCENSO_COD_NA_UNIDADE] = $identification['id'];
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

        foreach ($classrooms as $iclass => $attributes) {
            $students = self::getStudents($attributes, $students);
        }

        foreach ($students as $student) {
            $register = [];
            $register = self::exportStudentIdentification($student);
            array_push($registers, implode('|', $register));
        }

        return $registers;
    }

    public static function validarMatriculaRegistroCivil(string $matricula): bool
    {
        // Remove caracteres não numéricos
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
