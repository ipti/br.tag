<?php

/**
 * Static method stub kept for backward compatibility.
 * All controller actions have been moved to app/modules/classroom/controllers/DefaultController.php.
 * This class remains to serve the many callers of ClassroomController::classroomDisciplineLabelArray()
 * and other static helpers across the codebase.
 */
class ClassroomController
{
    public static function classroomDisciplineLabelArray()
    {
        $labels = [];
        $disciplines = EdcensoDiscipline::model()->findAll(['select' => 'id, name']);
        foreach ($disciplines as $value) {
            $labels[$value->id] = $value->name;
        }
        return $labels;
    }

    public static function classroomDiscipline2array2()
    {
        $disciplines['discipline_chemistry'] = 1;
        $disciplines['discipline_physics'] = 2;
        $disciplines['discipline_mathematics'] = 3;
        $disciplines['discipline_biology'] = 4;
        $disciplines['discipline_science'] = 5;
        $disciplines['discipline_language_portuguese_literature'] = 6;
        $disciplines['discipline_foreign_language_english'] = 7;
        $disciplines['discipline_foreign_language_spanish'] = 8;
        $disciplines['discipline_foreign_language_other'] = 9;
        $disciplines['discipline_arts'] = 10;
        $disciplines['discipline_physical_education'] = 11;
        $disciplines['discipline_history'] = 12;
        $disciplines['discipline_geography'] = 13;
        $disciplines['discipline_philosophy'] = 14;
        $disciplines['discipline_informatics'] = 16;
        $disciplines['discipline_professional_disciplines'] = 17;
        $disciplines['discipline_special_education_and_inclusive_practices'] = 20;
        $disciplines['discipline_sociocultural_diversity'] = 21;
        $disciplines['discipline_libras'] = 23;
        $disciplines['discipline_religious'] = 26;
        $disciplines['discipline_native_language'] = 27;
        $disciplines['discipline_pedagogical'] = 25;
        $disciplines['discipline_social_study'] = 28;
        $disciplines['discipline_sociology'] = 29;
        $disciplines['discipline_foreign_language_franch'] = 30;
        $disciplines['discipline_others'] = 99;
        return $disciplines;
    }

    public static function classroomDiscipline2array($classroom)
    {
        $disciplines = [];
        $classroomModel = Classroom::model()
            ->with('edcensoStageVsModalityFk.curricularMatrixes.disciplineFk')
            ->find('t.id = :classroom', [':classroom' => $classroom->id]);

        foreach ($classroomModel->edcensoStageVsModalityFk->curricularMatrixes as $matrix) {
            $disciplines[$matrix->disciplineFk->id] = $matrix->disciplineFk->name;
        }

        return $disciplines;
    }

    public static function teachingDataDiscipline2array($instructor)
    {
        $disciplines = [];

        if (isset($instructor->discipline_1_fk)) {
            array_push($disciplines, $instructor->discipline1Fk);
        }
        if (isset($instructor->discipline_2_fk)) {
            array_push($disciplines, $instructor->discipline2Fk);
        }
        if (isset($instructor->discipline_3_fk)) {
            array_push($disciplines, $instructor->discipline3Fk);
        }
        if (isset($instructor->discipline_4_fk)) {
            array_push($disciplines, $instructor->discipline4Fk);
        }
        if (isset($instructor->discipline_5_fk)) {
            array_push($disciplines, $instructor->discipline5Fk);
        }
        if (isset($instructor->discipline_6_fk)) {
            array_push($disciplines, $instructor->discipline6Fk);
        }
        if (isset($instructor->discipline_7_fk)) {
            array_push($disciplines, $instructor->discipline7Fk);
        }
        if (isset($instructor->discipline_8_fk)) {
            array_push($disciplines, $instructor->discipline8Fk);
        }
        if (isset($instructor->discipline_9_fk)) {
            array_push($disciplines, $instructor->discipline9Fk);
        }
        if (isset($instructor->discipline_10_fk)) {
            array_push($disciplines, $instructor->discipline10Fk);
        }
        if (isset($instructor->discipline_11_fk)) {
            array_push($disciplines, $instructor->discipline11Fk);
        }
        if (isset($instructor->discipline_12_fk)) {
            array_push($disciplines, $instructor->discipline12Fk);
        }
        if (isset($instructor->discipline_13_fk)) {
            array_push($disciplines, $instructor->discipline13Fk);
        }

        return $disciplines;
    }
}
