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
        $total = 0;
        $total2 = 0;

        foreach ($result as $r) {
            while ($cont >= 0) {
                $calc = ($cont * $r);
                $total = $total + $calc;
                $cont--;
                if ($cont < 0) {
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
        $return = $digUm . $digDois;

        return $return;
    }

    private static function getInstructors($instructorsTeachingDatas, $instructors, $classroom)
    {
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

        foreach ($instructorsTeachingDatas as $iteaching => $teachingData) {
            if (!isset($instructors[$teachingData->instructor_fk])) {
                $teachingData->instructorFk->documents->school_inep_id_fk = $school->inep_id;
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
                if ($variabledata == null) {
                    $variabledata = InstructorVariableData::model()->findByPk($teachingData->instructorFk->id);
                }
                $variabledata->id = $teachingData->instructorFk->id;
                $variabledata->inep_id = $teachingData->instructorFk->inep_id;
                $variabledata->school_inep_id_fk = $school->inep_id;
                $instructors[$teachingData->instructor_fk]['variable'] = $variabledata->attributes;
            }

            $teachingData->instructor_inep_id = $teachingData->instructorFk->inep_id;
            $teachingData->school_inep_id_fk = $school->inep_id;
            $instructors[$teachingData->instructor_fk]['teaching'][$classroom->id] = $teachingData->attributes;
        }

        return $instructors;
    }

    private static function getStudents($classroom, $students, $school)
    {
        

        foreach ($classroom->activeStudentEnrollments as $ienrollment => $enrollment) {
            if (!isset($students[$enrollment->student_fk])) {
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

    private static function exportStudentIdentification($student, $register, $year, $aliases)
    {
        $student['register_type'] = '30';

        
        foreach ($aliases as $kord => $ord) {
            $register[$ord->corder] = $ord->default;
        }

        if (!empty($student['inep_id'])) {
            if (strlen($student['inep_id']) < 9) {
                $student['inep_id'] = '';
            }
        }

        $student['name'] = trim(strtoupper(self::fixName($student['name'])));
        if ($student['filiation'] != '0') {
            $student['filiation_1'] = trim(strtoupper(self::fixName($student['filiation_1'])));
            $student['filiation_2'] = trim(strtoupper(self::fixName($student['filiation_2'])));
        } else {
            $student['filiation_1'] = '';
            $student['filiation_2'] = '';
        }

        if ($student['deficiency'] == 0) {
            $student['deficiency_type_blindness'] = '';
            $student['deficiency_type_low_vision'] = '';
            $student['deficiency_type_monocular_vision'] = '';
            $student['deficiency_type_deafness'] = '';
            $student['deficiency_type_disability_hearing'] = '';
            $student['deficiency_type_deafblindness'] = '';
            $student['deficiency_type_phisical_disability'] = '';
            $student['deficiency_type_intelectual_disability'] = '';
            $student['deficiency_type_multiple_disabilities'] = '';
            $student['deficiency_type_autism'] = '';
            $student['deficiency_type_gifted'] = '';
            $student['resource_none'] = '';
            $student['resource_aid_lector'] = '';
            $student['resource_aid_transcription'] = '';
            $student['resource_interpreter_guide'] = '';
            $student['resource_interpreter_libras'] = '';
            $student['resource_lip_reading'] = '';
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
                    if (empty($student[$i])) {
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
                $student['resource_zoomed_test_18'] = '';
                $student['resource_zoomed_test_24'] = '';
                $student['resource_braille_test'] = '';
            }
        }

        if ($student['nationality'] == '1' && !isset($student['edcenso_city_fk'])) {
            $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
            $student['edcenso_city_fk'] = $school->edcenso_city_fk;
        }

        foreach ($student as $key => $attr) {
            
            $alias_index = array_search($key, array_column($aliases, 'attr'));
            $alias = $aliases[$alias_index];

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

    private static function exportStudentDocuments($student, $register, $year, $aliases)
    {
        $student['register_type'] = '30';

        if (empty($student['cep']) && isset($student['edcenso_city_fk'])) {
            $student['edcenso_city_fk'] = '';
        }

        if (!empty($student['cep']) && !isset($student['edcenso_city_fk'])) {
            $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
            $student['edcenso_city_fk'] = $school->edcenso_city_fk;
        }

        $student['civil_register_enrollment_number'] = strtoupper($student['civil_register_enrollment_number']);

        if ($student['civil_certification'] != 2) {
            $student['civil_register_enrollment_number'] = '';
        }

        if ($student["residence_zone"] == "1" && $student["diff_location"] == "1") {
            $student["diff_location"] = "";
        }

        $student["id_email"] = '';

        foreach ($student as $key => $attr) {
            $alias_index = array_search($key, array_column($aliases, 'attr'));
            $alias = $aliases[$alias_index];
            if (isset($alias->corder)) {
                if ($key == 'edcenso_city_fk') {
                    $register[43] = $attr;
                } else {
                    $register[$alias->corder] = $attr;
                }
            }
        }

        return $register;
    }

    private static function exportInstructorIdentification($instructor, $register, $resetEmail, $aliases)
    {
        $instructor['register_type'] = '30';

        
        foreach ($aliases as $kord => $ord) {
            $register[$ord->corder] = $ord->default;
        }

        $instructor['name'] = trim(strtoupper(self::fixName($instructor['name'])));
        $instructor['filiation_1'] = trim(strtoupper(self::fixName($instructor['filiation_1'])));
        $instructor['filiation_2'] = trim(strtoupper(self::fixName($instructor['filiation_2'])));

        if (!empty($instructor['filiation_1']) && $instructor['filiation'] == 0) {
            $instructor['filiation'] = 1;
        } else if ($instructor['filiation'] == 0) {
            $instructor['filiation_1'] = '';
            $instructor['filiation_2'] = '';
        }

        if ($instructor['deficiency'] == 0) {
            $instructor['deficiency_type_blindness'] = '';
            $instructor['deficiency_type_low_vision'] = '';
            $instructor['deficiency_type_monocular_vision'] = '';
            $instructor['deficiency_type_deafness'] = '';
            $instructor['deficiency_type_disability_hearing'] = '';
            $instructor['deficiency_type_deafblindness'] = '';
            $instructor['deficiency_type_phisical_disability'] = '';
            $instructor['deficiency_type_intelectual_disability'] = '';
            $instructor['deficiency_type_multiple_disabilities'] = '';
            $instructor['deficiency_type_autism'] = '';
            $instructor['deficiency_type_gifted'] = '';
        }

        if ($resetEmail) {
            $instructor['email'] = '';
        }

        if ($instructor['nationality'] == '1' && !isset($instructor['edcenso_city_fk'])) {
            $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
            $instructor['edcenso_city_fk'] = $school->edcenso_city_fk;
        }

        foreach ($instructor as $key => $attr) {
            
            $alias_index = array_search($key, array_column($aliases, 'attr'));
            $alias = $aliases[$alias_index];
            
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

    private static function exportInstructorDocuments($instructor, $register, $aliases)
    {
        $instructor['register_type'] = '30';

        if (empty($instructor['cep']) && isset($instructor['edcenso_city_fk'])) {
            $instructor['edcenso_city_fk'] = '';
        }

        if (!empty($instructor['cep']) && !isset($instructor['edcenso_city_fk'])) {
            $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
            $instructor['edcenso_city_fk'] = $school->edcenso_city_fk;
        }

        if (empty($instructor['area_of_residence'])) {
            $register[44] = '1';
            if ($instructor['documents']['diff_location'] == '1') {
                $register[45] = '';
            }
        } else if ($instructor["area_of_residence"] == "1" && $instructor["diff_location"] == "1") {
            $instructor["diff_location"] = "";
        }

        foreach ($instructor as $key => $attr) {

            $alias_index = array_search($key, array_column($aliases, 'attr'));
            $alias = $aliases[$alias_index];
            
            if (isset($alias->corder)) {
                if ($key == 'edcenso_city_fk') {
                    $register[43] = $attr;
                } else {
                    $register[$alias->corder] = $attr;
                }
            }
        }

        return $register;
    }

    private static function exportInstructorVariable($instructor, $register, $highEducationCourses, $aliases)
    {
        $instructor['register_type'] = '30';

        if ($instructor['scholarity'] == '3' || $instructor['scholarity'] == '4') {
            $instructor['scholarity'] = '7';
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

        $hasCourse1 = false;
        $hasCourse2 = false;
        if (empty($instructor['high_education_course_code_1_fk'])
            || (isset($instructor['high_education_course_code_1_fk'])
                && (empty($instructor['high_education_situation_1']) || $instructor['high_education_situation_1'] == '2' || empty($instructor['high_education_final_year_1'])))) {
            $instructor['scholarity'] = 7;
        } else {
            $hasCourse1 = true;
            if (empty($instructor['high_education_institution_code_1_fk'])) {
                $instructor['high_education_institution_code_1_fk'] = '9999999';
            }
        }
        if ($hasCourse1) {
            if (isset($instructor['high_education_course_code_2_fk'])
                && $instructor['high_education_course_code_2_fk'] !== $instructor['high_education_course_code_1_fk']
                && $instructor['high_education_situation_2'] == '1'
                && !empty($instructor['high_education_final_year_2'])) {
                $hasCourse2 = true;
                if (empty($instructor['high_education_institution_code_2_fk'])) {
                    $instructor['high_education_institution_code_2_fk'] = '9999999';
                }
            } else {
                $instructor['high_education_course_code_2_fk'] = '';
                $instructor['high_education_final_year_2'] = '';
                $instructor['high_education_institution_code_2_fk'] = '';
                $instructor['high_education_course_code_3_fk'] = '';
                $instructor['high_education_final_year_3'] = '';
                $instructor['high_education_institution_code_3_fk'] = '';
            }
        }
        if ($hasCourse2) {
            if (isset($instructor['high_education_course_code_3_fk'])
                && $instructor['high_education_course_code_3_fk'] !== $instructor['high_education_course_code_1_fk']
                && $instructor['high_education_course_code_3_fk'] !== $instructor['high_education_course_code_2_fk']
                && $instructor['high_education_situation_3'] == '1'
                && !empty($instructor['high_education_final_year_3'])) {
                if (empty($instructor['high_education_institution_code_3_fk'])) {
                    $instructor['high_education_institution_code_3_fk'] = '9999999';
                }
            } else {
                $instructor['high_education_course_code_3_fk'] = '';
                $instructor['high_education_final_year_3'] = '';
                $instructor['high_education_institution_code_3_fk'] = '';
            }
        }

        if ($instructor['scholarity'] != 6) {
            $instructor['post_graduation_none'] = '';
            $instructor['high_education_course_code_1_fk'] = '';
            $instructor['high_education_final_year_1'] = '';
            $instructor['high_education_institution_code_1_fk'] = '';
            $instructor['high_education_course_code_2_fk'] = '';
            $instructor['high_education_final_year_2'] = '';
            $instructor['high_education_institution_code_2_fk'] = '';
            $instructor['high_education_course_code_3_fk'] = '';
            $instructor['high_education_final_year_3'] = '';
            $instructor['high_education_institution_code_3_fk'] = '';
        } else {
            $instructor['post_graduation_none'] = '1';
        }

        if ($instructor['high_education_course_code_1_fk'] !== null && $instructor['high_education_course_code_1_fk'] !== "") {
            $instructor['high_education_course_code_1_fk'] = self::convertCourseCodes($instructor['high_education_course_code_1_fk'], $highEducationCourses);
        }
        if ($instructor['high_education_course_code_2_fk'] !== null && $instructor['high_education_course_code_2_fk'] !== "") {
            $instructor['high_education_course_code_2_fk'] = self::convertCourseCodes($instructor['high_education_course_code_2_fk'], $highEducationCourses);
        }
        if ($instructor['high_education_course_code_3_fk'] !== null && $instructor['high_education_course_code_3_fk'] !== "") {
            $instructor['high_education_course_code_3_fk'] = self::convertCourseCodes($instructor['high_education_course_code_3_fk'], $highEducationCourses);
        }

        foreach ($instructor as $key => $attr) {
            
            $alias_index = array_search($key, array_column($aliases, 'attr'));
            $alias = $aliases[$alias_index];
            
            if (isset($alias->corder)) {
                $register[$alias->corder] = $attr;
            }
            if ($key == 'scholarity' && $attr == '7') {
                $register[47] = '1';
            }
        }

        return $register;
    }

    private static function convertCourseCodes($code, $highEducationCourses)
    {
        $found_key = array_search($code, array_column($highEducationCourses, 'id'));
        return $highEducationCourses[$found_key]['cine_id'];
    }

    public static function export($year)
    {
        $registers = [];
        
        
        $classrooms = Classroom::model()->with([
            "activeStudentEnrollments.studentFk.documentsFk",
            "instructorTeachingDatas.instructorFk.instructorVariableData"
            
        ])->findAllByAttributes(['school_inep_fk' => yii::app()->user->school, 'school_year' => Yii::app()->user->year]);
        $managerIdentification = ManagerIdentification::model()->findByAttributes(['school_inep_id_fk' => yii::app()->user->school]);
        $highEducationCourses = EdcensoCourseOfHigherEducation::model()->findAll();
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school); 

        $aliasesStudent = EdcensoAlias::model()->findAllByAttributes(['register' => '301', 'year' => $year]);
        $aliasesInstructor = EdcensoAlias::model()->findAllByAttributes(['register' => '302', 'year' => $year]);

        $instructors = [];
        $students = [];

        foreach ($classrooms as $iclass => $attributes) {
            $students = self::getStudents($attributes, $students, $school);
            $instructors = self::getInstructors($attributes->instructorTeachingDatas, $instructors, $attributes);
            
        }

        foreach ($students as $student) {
            $register = [];

            $register = self::exportStudentIdentification($student['identification'], $register, $year, $aliasesStudent);
            $register = self::exportStudentDocuments($student['documents'], $register, $year, $aliasesStudent);

            ksort($register);
            array_push($registers, implode('|', $register));
        }

        $managerIsAnInstructor = false;
        foreach ($instructors as $instructor) {
            $id = (String)'90' . $instructor['identification']['id'];
            $instructor['identification']['id'] = $id;
            $instructor['documents']['id'] = $id;
            $instructor['variable']['id'] = $id;

            $resetEmail = true;
            if ($instructor["documents"]["cpf"] == $managerIdentification["cpf"]) {
                $managerIsAnInstructor = true;
                $resetEmail = false;
            }

            $register = [];

            $register = self::exportInstructorIdentification($instructor['identification'], $register, $resetEmail, $aliasesInstructor);
            $register = self::exportInstructorDocuments($instructor['documents'], $register, $aliasesInstructor);
            $register = self::exportInstructorVariable($instructor['variable'], $register, $highEducationCourses, $aliasesInstructor);

            ksort($register);
            array_push($registers, implode('|', $register));
        }

        if (!$managerIsAnInstructor) {
            $managerIdentification['name'] = trim(strtoupper(self::fixName($managerIdentification['name'])));
            $managerIdentification['filiation_1'] = trim(strtoupper(self::fixName($managerIdentification['filiation_1'])));
            $managerIdentification['filiation_2'] = trim(strtoupper(self::fixName($managerIdentification['filiation_2'])));

            if (!empty($managerIdentification['filiation_1']) && $managerIdentification['filiation'] == 0) {
                $managerIdentification['filiation'] = 1;
            } else if ($managerIdentification['filiation'] == 0) {
                $managerIdentification['filiation_1'] = '';
                $managerIdentification['filiation_2'] = '';
            }

            array_push($registers, '30|' . Yii::app()->user->school . '|90999||' // 1 a 4
                . $managerIdentification["cpf"] . '|' . $managerIdentification["name"] . '|' . $managerIdentification["birthday_date"] . '|' . $managerIdentification["filiation"] . '|' // 5 a 8
                . $managerIdentification["filiation_1"] . '|' . $managerIdentification["filiation_2"] . '|' . $managerIdentification["sex"] . '|' . $managerIdentification["color_race"] . '|' // 9 a 12
                . $managerIdentification["nationality"] . '|' . $managerIdentification["edcenso_nation_fk"] . '|' . $managerIdentification["edcenso_city_fk"] . '|' // 13 a 15
                . '0||||||||||||||||||||||||||||||' // 16 a 45 (deficiencias, certidão e dados de residência (ignoráveis para o registro 40)
                . '6||0113P011|2008|3||||||||||||||||||||||||||||1|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|1|' // 46 a 97 (escolaridade, cursos e pós-graduações)
                . $managerIdentification["email"]
            );
        }

        return $registers;
    }
}