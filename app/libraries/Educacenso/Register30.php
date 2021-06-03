<?php
class Register30
{
    private static function fixName($name)
    {
        return preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($name));
    }

    private static function certVerify($codigo)
    {
        $result = str_split($codigo);
        $result = array_reverse($result);
        $cont = 9;

        foreach ($result as $r) {
            while ($cont >= 0) {
                $calculo = "$cont * $r";
                $calc = ($cont * $r);
                $total = $total + $calc;
                $cont--;
                if ($cont < 0){
                    $cont = 10;
                    break;
                }
                break;
            }
        }

        $cont = 9;
        $valor = 0;
        $digUm = $total % 11;

        if ($digUm == 10) {
            $digUm = 1;
        }

        foreach ($result as $r) {
            while ($cont >= 0) {
                if ($valor == 0) {
                    $valor = 1;
                    $calc = ($cont * $digUm);
                    $total2 = $total2 + $calc;
                    $cont--;
                    if ($cont < 0) {
                        $cont = 10;
                        break;
                    }
                }
                $calc = ($cont * $r);
                $total2 = $total2 + $calc;
                $cont--;
                if ($cont < 0) {
                    $cont = 10;
                    break;
                }
                break;
            }
        }

        $digDois = $total2 % 11;
        if ($digDois == 10) {
            $digDois = 1;
        }
        $return = $digUm.$digDois;

        return $return;
    }

    private static function getInstructors($instructorsTeachingDatas, $instructors)
    {
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

        foreach ($instructorsTeachingDatas as $iteaching => $teachingData) {
            if (!isset($instructors[$teachingData->instructor_fk])) {
                $teachingData->instructorFk->documents->school_inep_id_fk =  $school->inep_id;
                $teachingData->instructorFk->instructorVariableData->school_inep_id_fk = $school->inep_id;
                $teachingData->instructorFk->school_inep_id_fk = $school->inep_id;

                $instructors[$teachingData->instructor_fk]['identification'] = $teachingData->instructorFk->attributes;
                $instructors[$teachingData->instructor_fk]['documents'] = $teachingData->instructorFk->documents->attributes;

                $instructor_inepid_id = isset($teachingData->instructorFk->inep_id) && !empty($teachingData->instructorFk->inep_id) ? $teachingData->instructorFk->inep_id : $teachingData->instructorFk->id;
                if (isset($teachingData->instructorFk->inep_id) && !empty($teachingData->instructorFk->inep_id)) {
                    $variabledata = InstructorVariableData::model()->findByAttributes(['inep_id' => $instructor_inepid_id]);
                } else {
                    $variabledata = InstructorVariableData::model()->findByPk($instructor_inepid_id);
                }
                $variabledata->id = $teachingData->instructorFk->id;
                $variabledata->inep_id = $teachingData->instructorFk->inep_id;
                $variabledata->school_inep_id_fk = $school->inep_id;
                $instructors[$teachingData->instructor_fk]['variable'] =  $variabledata->attributes;
            }

            $teachingData->instructor_inep_id = $teachingData->instructorFk->inep_id;
            $teachingData->school_inep_id_fk = $school->inep_id;
            $instructors[$teachingData->instructor_fk]['teaching'][$classroom->id] = $teachingData->attributes;
        }

        return $instructors;
    }

    private static function getStudents($classroom, $students)
    {
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

        foreach ($classroom->studentEnrollments as $ienrollment => $enrollment) {
            if(!isset($students[$enrollment->student_fk])){
                $enrollment->studentFk->school_inep_id_fk = $school->inep_id;
                $enrollment->studentFk->documentsFk->school_inep_id_fk = $school->inep_id;
                $students[$enrollment->student_fk]['identification'] = $enrollment->studentFk->attributes;
                $students[$enrollment->student_fk]['documents'] = $enrollment->studentFk->documentsFk->attributes;
                $students[$enrollment->student_fk]['classroom'] = $classroom->attributes;
            }

            $enrollment->school_inep_id_fk = $school->inep_id;
            $students[$enrollment->student_fk]['enrollments'][$ienrollment] = $enrollment->attributes;
        }

        return $students;
    }

    private static function exportStudentIdentification($student, $register)
    {
        $student['register_type'] = '30';

        $aliases = EdcensoAlias::model()->findAllByAttributes(['register' => '301', 'year' => 2021]);
        foreach ($aliases as $kord => $ord) {
            $register[$ord->corder] = $ord->default;
        }

        if (!empty($student['inep_id'])) {
            if (strlen($student['inep_id']) < 9) {
                $student['inep_id'] = '';
            }
        }

        $student['name'] = strtoupper(self::fixName($student['name']));
        $student['filiation_1'] = strtoupper(self::fixName($student['filiation_1']));
        $student['filiation_2'] = strtoupper(self::fixName($student['filiation_2']));

        if ($student['deficiency'] == 0) {
            $student['deficiency_type_blindness'] = '';
            $student['deficiency_type_low_vision'] = '';
            $student['deficiency_type_deafness'] = '';
            $student['deficiency_type_disability_hearing'] = '';
            $student['deficiency_type_deafblindness'] = '';
            $student['deficiency_type_phisical_disability'] = '';
            $student['deficiency_type_intelectual_disability'] = '';
            $student['deficiency_type_multiple_disabilities'] = '';
            $student['deficiency_type_autism'] = '';
            $student['deficiency_type_aspenger_syndrome'] = '';
            $student['deficiency_type_rett_syndrome'] = '';
            $student['deficiency_type_childhood_disintegrative_disorder'] = '';
            $student['deficiency_type_gifted'] = '';
            $student['resource_none'] = '';
            $student['resource_aid_lector'] = '';
            $student['resource_aid_transcription'] = '';
            $student['resource_interpreter_guide'] = '';
            $student['resource_interpreter_libras'] = '';
            $student['resource_lip_reading'] = '';
            $student['resource_zoomed_test_16'] = '';
            $student['resource_zoomed_test_20'] = '';
            $student['resource_zoomed_test_24'] = '';
            $student['resource_braille_test'] = '';
            $student['resource_zoomed_test_18'] = '';
            $student['resource_cd_audio'] = '';
            $student['resource_proof_language'] = '';
            $student['resource_video_libras'] = '';
        } else {
            $existone = false;
            foreach ($student as $i => $attr) {
                $pos = strstr($i, 'deficiency_');
                if ($pos) {
                    if(empty($student[$i])){
                        $student[$i] = '0';
                    }
                }
                $pos2 = strstr($i, 'resource_');
                if ($pos2) {
                    if (empty($student[$i])) {
                        $student[$i] = '0';
                        if (!$existone) {
                            $student['resource_none'] = '1';
                        }
                    } else {
                        if ($i != 'resource_none') {
                            $existone = true;
                            $student['resource_none'] = '0';
                        }
                    }
                }
            }

            if (!empty($student['deficiency_type_gifted'])) {
                $student['resource_none'] = '';
                $student['resource_aid_lector'] = '';
                $student['resource_aid_transcription'] = '';
                $student['resource_interpreter_guide'] = '';
                $student['resource_interpreter_libras'] = '';
                $student['resource_lip_reading'] = '';
                $student['resource_zoomed_test_16'] = '';
                $student['resource_zoomed_test_20'] = '';
                $student['resource_zoomed_test_24'] = '';
                $student['resource_braille_test'] = '';
            }
        }

        foreach ($student as $key => $attr) {
            $alias = EdcensoAlias::model()->findByAttributes(['register' => '301', 'attr' => $key, 'year' => 2021]);
            if (isset($alias->corder)) {
                if ($key == 'edcenso_city_fk') {
                    $register[15] = $attr;
                } else {
                    $register[$alias->corder] = $attr;
                }
            }
        }

        return $register;
    }

    private static function exportStudentDocuments($student, $register)
    {
        $student['register_type'] = '30';

        if (empty($student['address'])) {
            $student['edcenso_uf_fk'] = '';
            $student['number'] = '';
            $student['complement'] = '';
            $student['neighborhood'] = '';
        }

        if (empty($student['cep'])) {
            $student['address'] = '';
            $student['edcenso_uf_fk'] = '';
            $student['number'] = '';
            $student['complement'] = '';
            $student['neighborhood'] = '';
        }

        if (empty($student['cep']) && isset($student['edcenso_city_fk'])) {
            $student['edcenso_city_fk'] = '';
        }

        if (!empty($student['cep']) && !isset($student['edcenso_city_fk'])) {
            $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
            $student['edcenso_city_fk'] = $school->edcenso_city_fk;
        }

        if (!empty($student['cep'])&&!isset($student['edcenso_uf_fk'])) {
            $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
            $student['edcenso_uf_fk'] = $school->edcenso_uf_fk;
        }

        $student['civil_register_enrollment_number'] = strtoupper($student['civil_register_enrollment_number']);

        if ($student['civil_certification'] == 1) {
            if (empty($student['civil_certification_type'])) {
                $student['civil_certification_type'] = '1';
            }
            $student['civil_register_enrollment_number'] = '';
        } else if ($student['civil_certification'] == 2) {
            $student['civil_certification_type'] = '';
            $student['civil_certification_term_number'] = '';
            $student['civil_certification_sheet'] = '';
            $student['civil_certification_book'] = '';
            $student['civil_certification_date'] = '';
        } else {
            $student['civil_register_enrollment_number'] = '';
            $student['civil_certification_type'] = '';
            $student['civil_certification_term_number'] = '';
            $student['civil_certification_sheet'] = '';
            $student['civil_certification_book'] = '';
            $student['civil_certification_date'] = '';
        }

        if (empty($student['civil_certification'])) {
            $student['civil_certification_type'] = '';
        }

        if (empty($student['rg_number'])) {
            $student['rg_number_edcenso_organ_id_emitter_fk'] = '';
            $student['rg_number_edcenso_uf_fk'] = '';
            $student['rg_number_expediction_date'] = '';
        }

        if ($student['civil_certification'] == '2') {
            $cert = substr($student['civil_register_enrollment_number'],0, 30);
            $testX = str_split($student['civil_register_enrollment_number']);

            if ($testX[29] == 'x') {
                $cert = substr($student['civil_register_enrollment_number'],0, 29);
                $cert = $cert . '0';
            }

            $certDv = self::certVerify($cert);
            $student['civil_register_enrollment_number'] = $cert . '' . $certDv;
        }

        if (empty($student['nis']) && empty($student['cpf']) && empty($student['civil_register_enrollment_number'])) {
            $student['no_document_desc'] = 2;
        }

        foreach ($student as $key => $attr) {
            $alias = EdcensoAlias::model()->findByAttributes(['register' => '301', 'attr' => $key, 'year' => 2021]);
            if (isset($alias->corder)) {
                if ($key == 'edcenso_city_fk') {
                    $register[44] = $attr;
                } else {
                    $register[$alias->corder] = $attr;
                }
            }
        }

        return $register;
    }

    private static function exportInstructorIdentification($instructor, $register)
    {
        $instructor['register_type'] = '30';

        $aliases = EdcensoAlias::model()->findAllByAttributes(['register' => '302', 'year' => 2021]);
        foreach ($aliases as $kord => $ord) {
            $register[$ord->corder] = $ord->default;
        }

        $instructor['name'] = strtoupper(self::fixName($instructor['name']));
        $instructor['filiation_1'] = strtoupper(self::fixName($instructor['filiation_1']));
        $instructor['filiation_2'] = strtoupper(self::fixName($instructor['filiation_2']));

        if (!empty($instructor['filiation_1']) && $instructor['filiation'] == 0){
            $instructor['filiation'] = 1;
        } else if ($instructor['filiation'] == 0) {
            $instructor['filiation_1'] = '';
            $instructor['filiation_2'] = '';
        }

        if ($instructor['deficiency'] == 0) {
            $instructor['deficiency_type_blindness'] = '';
            $instructor['deficiency_type_low_vision'] = '';
            $instructor['deficiency_type_deafness'] = '';
            $instructor['deficiency_type_disability_hearing'] = '';
            $instructor['deficiency_type_deafblindness'] = '';
            $instructor['deficiency_type_phisical_disability'] = '';
            $instructor['deficiency_type_intelectual_disability'] = '';
            $instructor['deficiency_type_multiple_disabilities'] = '';
            $instructor['deficiency_type_autism'] = '';
            $instructor['deficiency_type_gifted'] = '';
        }

        $instructor['nis'] = '';
        $instructor['email'] = '';

        foreach ($instructor as $key => $attr) {
            $alias = EdcensoAlias::model()->findByAttributes(['register' => '302', 'attr' => $key, 'year' => 2021]);
            if (isset($alias->corder)) {
                if ($key == 'edcenso_city_fk') {
                    $register[15] = $attr;
                } else {
                    $register[$alias->corder] = $attr;
                }
            }
        }

        return $register;
    }

    private static function exportInstructorDocuments($instructor, $register)
    {
        $instructor['register_type'] = '30';

        if (empty($instructor['cep'])) {
            $instructor['address'] = '';
            $instructor['edcenso_city_fk'] = '';
            $instructor['edcenso_uf_fk'] = '';
        }

        if (!empty($instructor['cep'])&&!isset($instructor['edcenso_city_fk'])) {
            $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
            $instructor['edcenso_city_fk'] = $school->edcenso_city_fk;
        }

        if (!empty($instructor['cep'])&&!isset($instructor['edcenso_uf_fk'])) {
            $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
            $instructor['edcenso_uf_fk'] = $school->edcenso_uf_fk;
        }

        $instructor['cep'] = '';
        $instructor['edcenso_city_fk'] = '';

        foreach ($instructor as $key => $attr) {
            $alias = EdcensoAlias::model()->findByAttributes(['register' => '302', 'attr' => $key, 'year' => 2021]);
            if (isset($alias->corder)) {
                if ($key == 'edcenso_city_fk') {
                    $register[44] = $attr;
                } else {
                    $register[$alias->corder] = $attr;
                }
            }
        }

        return $register;
    }

    private static function exportInstructorVariable($instructor, $register)
    {
        $instructor['register_type'] = '30';

        $course1 = EdcensoCourseOfHigherEducation::model()->findByPk($attributes['high_education_course_code_1_fk']);
        if ($course1->degree == 'Licenciatura') {
            $instructor['high_education_formation_1'] = '';
        }
        $course2 = EdcensoCourseOfHigherEducation::model()->findByPk($instructor['high_education_course_code_2_fk']);
        if ($course2->degree == 'Licenciatura') {
            $instructor['high_education_formation_2'] = '';
        }
        if (isset($instructor['high_education_course_code_1_fk']) && empty($instructor['high_education_institution_code_1_fk'])) {
            $instructor['high_education_institution_code_1_fk'] = '9999999';
        }

        $setothers = false;
        foreach ($instructor as $i => $attr) {
            $pos = strstr($i, 'other_courses_');
            if ($pos) {
                if (empty($instructor[$i])) {
                    $instructor[$i] = '0';
                } else {
                    if ($i != 'other_courses_none') {
                        $setothers = true;
                    }
                }
            }
        }

        if ($setothers) {
            $instructor['other_courses_none'] = '0';
        } else {
            $instructor['other_courses_none'] = '1';
        }

        if ($instructor['high_education_situation_1'] == '2') {
            $instructor['scholarity'] = 7;
        }

        if ($instructor['scholarity'] == 7) {
            $instructor['high_education_situation_1'] = '1';
        } else {
            $instructor['high_education_situation_1'] = '';
        }

        if ($instructor['scholarity'] != 6) {
            $instructor['post_graduation_specialization'] = '';
            $instructor['post_graduation_master'] = '';
            $instructor['post_graduation_doctorate'] = '';
            $instructor['post_graduation_none'] = '';
            $instructor['high_education_course_code_1_fk'] = '';
            $instructor['high_education_final_year_1'] = '';
            $instructor['high_education_institution_code_1_fk'] = '';
        } else {
            if (empty($instructor['post_graduation_specialization'])) {
                $instructor['post_graduation_specialization'] = 0;
            } elseif (empty($instructor['post_graduation_master'])) {
                $instructor['post_graduation_master'] = 0;
            } elseif (empty($instructor['post_graduation_doctorate'])) {
                $instructor['post_graduation_doctorate'] = 0;
            }

            if (empty($instructor['post_graduation_specialization'])&&empty($instructor['post_graduation_master'])&&empty($instructor['post_graduation_doctorate'])) {
                $instructor['post_graduation_none'] = '1';
            }
        }

        if ($instructor['post_graduation_none'] == '1') {
            $instructor['post_graduation_specialization'] = '0';
            $instructor['post_graduation_master'] = '0';
            $instructor['post_graduation_doctorate'] = '0';
        }

        if (empty($instructor['high_education_course_code_1_fk']) || empty($instructor['high_education_final_year_1'])) {
            $instructor['post_graduation_specialization'] = '';
            $instructor['post_graduation_master'] = '';
            $instructor['post_graduation_doctorate'] = '';
            $instructor['post_graduation_none'] = '';
            $instructor['high_education_formation_1'] = '';
        } else {
            if (empty($instructor['post_graduation_specialization'])) {
                $instructor['post_graduation_specialization'] = '0';
            } elseif (empty($instructor['post_graduation_master'])) {
                $instructor['post_graduation_master'] = '0';
            } elseif (empty($instructor['post_graduation_doctorate'])) {
                $instructor['post_graduation_doctorate'] = '0';
            } elseif (empty($instructor['post_graduation_none'])) {
                $instructor['post_graduation_none'] = '1';
            }
        }

        if ($instructor['high_education_situation_2'] == 2 || empty($instructor['high_education_situation_2'])) {
            $instructor['high_education_formation_2'] = '';
        }

        if ($instructor['high_education_situation_3'] == 2 || empty($instructor['high_education_situation_3'])) {
            $instructor['high_education_formation_3'] = '';
        }

        foreach ($instructor as $key => $attr) {
            $alias = EdcensoAlias::model()->findByAttributes(['register' => '302', 'attr' => $key, 'year' => 2021]);
            if (isset($alias->corder)) {
                $register[$alias->corder] = $attr;
            }
        }

        return $register;
    }

    public static function export()
    {
        $registers = [];

        $classrooms = Classroom::model()->findAllByAttributes(['school_inep_fk' => yii::app()->user->school, 'school_year' => Yii::app()->user->year]);
        $instructors = [];
        $students = [];

        foreach ($classrooms as $iclass => $attributes) {
            $students = self::getStudents($attributes, $students);
            $instructors = self::getInstructors($attributes->instructorTeachingDatas, $instructors);
        }

        foreach ($students as $student) {
            $register = [];

            $register = self::exportStudentIdentification($student['identification'], $register);
            $register = self::exportStudentDocuments($student['documents'], $register);

            if (!empty($student['identification']['birthday'])) {
                list($dia, $mes, $ano) = explode('/', $student['identification']['birthday']);
                $date = new DateTime("{$ano}-{$mes}-{$dia}");
                $now = new DateTime();
                $interval = $now->diff($date);
                $idade = $interval->y;

                foreach ($student['enrollments'] as $studentEnrollment) {
                    // O aluno não pode ter mais de 50 anos e pertencer as etapas abaixo
                    if ($idade > 50 && in_array($studentEnrollment['edcenso_stage_vs_modality_fk'], [14, 15, 16, 17, 18, 19, 20, 21, 41])) {
                        $register[7] = '';
                    }

                    // O aluno não pode ter mais de 58 anos e pertencer as etapas abaixo
                    if ($idade > 58 && in_array($studentEnrollment['edcenso_stage_vs_modality_fk'], [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39])) {
                        $register[7] = '';
                    }

                    // O aluno não pode ter menos de 13 anos e pertencer as etapas abaixo
                    if ($idade < 13 && in_array($studentEnrollment['edcenso_stage_vs_modality_fk'], [40])) {
                        $register[7] = '';
                    }

                    // O aluno não pode ter mais de 75 anos e pertencer as etapas abaixo
                    if ($idade > 75 && in_array($studentEnrollment['edcenso_stage_vs_modality_fk'], [40])) {
                        $register[7] = '';
                    }

                    // O aluno não pode ter mais de 94 anos e pertencer as etapas abaixo
                    if ($idade > 94 && in_array($studentEnrollment['edcenso_stage_vs_modality_fk'], [67, 68, 69, 70, 71, 72, 73, 74])) {
                        $register[7] = '';
                    }
                }
            }

            // Deve ser preenchido com o valor 1 caso a modalidade da turma seja preenchida com o valor 2 (Educação Especial)
            if ($student['classroom']['modality'] == 2) {
                $register[16] = 1;
            }

            ksort($register);
            array_push($registers, implode('|', $register));
        }

        foreach ($instructors as $instructor) {
            $id = (String) '90' . $instructor['identification']['id'];
            $instructor['identification']['id'] = $id;
            $instructor['documents']['id'] = $id;
            $instructor['variable']['id'] = $id;

            $register = [];

            $register = self::exportInstructorIdentification($instructor['identification'], $register);
            $register = self::exportInstructorDocuments($instructor['documents'], $register);
            $register = self::exportInstructorVariable($instructor['variable'], $register);

            ksort($register);
            array_push($registers, implode('|', $register));
        }

        array_push($registers, '30|'.Yii::app()->user->school.'|909999|183258253160|84278560591|RUANCELI DO NASCIMENTO SANTOS|23/05/1988|1|TANIA MARIA DO NASCIMENTO||2|3|1|76|2800670|0|||||||||||||||||||||||||||||1||6||145F01|2008|3||||||||||1|0|0|0|0|0|0|1|0|0|0|0|0|0|0|0|0|0|0|1|0|RUAN@IPTI.ORG.BR');

        return $registers;
    }
}