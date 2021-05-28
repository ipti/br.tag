<?php
class Register20
{
    private static function sanitizeString($string)
    {
        $what = ['ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º'];
        $by = ['a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','',''];

        return str_replace($what, $by, $string);
    }

    private static function fixName($name)
    {
        return preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($name));
    }

    private static function findDisc($id)
    {
        $teachingDataDisciplines = [];

        $modelTeachingData = Classroom::model()->findByPk($id)->instructorTeachingDatas;
        foreach ($modelTeachingData as $key => $model) {
            $disciplines = ClassroomController::teachingDataDiscipline2array($model);
            foreach ($disciplines as $discipline) {
                if ($discipline->id > 99) {
                    $teachingDataDisciplines[99] = 99;
                }
                $teachingDataDisciplines[$discipline->id] = $discipline->id;
            }
        }

        return $teachingDataDisciplines;
    }

    private static function classroomDisciplines()
    {
        $disciplines = [];
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
        $disciplines['discipline_pedagogical'] =  25;
        $disciplines['discipline_social_study'] = 28;
        $disciplines['discipline_sociology'] = 29;
        $disciplines['discipline_foreign_language_franch'] = 30;
        $disciplines['discipline_others'] = 99;

        return $disciplines;
    }

    public static function export()
    {
        $registers = [];

        $classrooms = Classroom::model()->findAllByAttributes(['school_inep_fk' => yii::app()->user->school, 'school_year' => Yii::app()->user->year]);

        foreach ($classrooms as $iclass => $attributes) {
            $register = [];

            $attributes['name'] = strtoupper(self::sanitizeString($attributes['name']));

            $dteacher = self::findDisc($attributes['id']);
            $dclass = self::classroomDisciplines();
            $classroom = Classroom::model()->findByPk($attributes['id']);

            foreach ($attributes as $i => $attr) {
                $pos = strstr($i, 'discipline');
                if ($pos) {
                    $attributes[$i] = '';
                    if (isset($dteacher[$dclass[$i]])) {
                        $attributes[$i] = '1';
                    }

                }
            }

            if ($attributes['assistance_type'] != '5') {
                foreach ($attributes as $i => $attr){
                    $pos = strstr($i, 'aee_');
                    if ($pos) {
                        $attributes[$i] = '';
                    }
                }
            }

            $stage = EdcensoStageVsModality::model()->findByPk($attributes['edcenso_stage_vs_modality_fk']);
            if ($stage->stage == '6'){
                $attributes['mais_educacao_participator'] = '';
            }

            if ($attributes['edcenso_stage_vs_modality_fk'] <= 4 &&
                $attributes['edcenso_stage_vs_modality_fk'] >= 38 &&
                $attributes['edcenso_stage_vs_modality_fk'] != 41){
                $attributes['mais_educacao_participator'] = '';
            }

            if ($attributes['edcenso_stage_vs_modality_fk'] == 1 || $attributes['edcenso_stage_vs_modality_fk'] == 2 || $attributes['edcenso_stage_vs_modality_fk'] == 3 || $attributes['edcenso_stage_vs_modality_fk'] == 65) {
                foreach ($attributes as $i => $attr) {
                    $pos = strstr($i, 'discipline');
                    if ($pos) {
                        $attributes[$i] = '';
                    }
                }
                $attributes['mais_educacao_participator'] = '';
            } else {
                if (!isset($attributes['mais_educacao_participator'])) {
                    $attributes['mais_educacao_participator'] = 0;
                }

                foreach ($attributes as $i => $attr) {
                    $pos = strstr($i, 'discipline');
                    if ($pos) {
                        if (empty($attributes[$i])) {
                            $attributes[$i] = '0';
                        }
                    }
                }
            }

            // O campo Modalidade só pode conter os valores abaixo
            if (!in_array($attributes['modality'], [1, 2, 3, 4])) {
                $attributes['modality'] = '';
            }

            // O campo Modalidade só pode ser preenchido com os valores 2 (Educação Especial) ou 3 (EJA) caso o campo Mediação didático-pedagógica tenha sido preenchido com o valor 2 (Semipresencial)
            if ($attributes['pedagogical_mediation_type'] == '2' && !in_array($attributes['modality'], [2, 3])) {
                $attributes['modality'] = '';
            }

            if ($attributes['assistance_type'] == '5') {
                $attributes['mais_educacao_participator'] = '';
                $attributes['edcenso_stage_vs_modality_fk'] = '';
                $attributes['modality'] = '';

                foreach ($attributes as $i => $attr) {
                    $pos = strstr($i, 'discipline');
                    if ($pos) {
                        $attributes[$i] = '';
                    }
                }
            }

            // O campo Etapa só pode conter os valores abaixo caso a modalidade seja preenchida com o valor 2 (Educação Especial)
            if ($attributes['modality'] == '2' && !in_array($attributes['edcenso_stage_vs_modality_fk'], [1, 2, 3, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 56, 64, 67, 68, 69, 70, 71, 72, 73, 74])) {
                $attributes['edcenso_stage_vs_modality_fk'] = '';
            }

            foreach ($attributes as $i => $attr) {
                if ($attr == '') {
                    $ordem = EdcensoAlias::model()->findByAttributes(['year' => 2021, 'register' => '20', 'attr' => $i]);
                    $attributes[$i] = $ordem->default;
                }
            }

            if (isset($classroom) && (count($classroom->instructorTeachingDatas) < 1 && count($classroom->studentEnrollments) < 1)) {
                $attributes = [];
            } else {
                foreach ($attributes as $column => $value) {
                    $alias = EdcensoAlias::model()->findByAttributes(['year' => 2021, 'register' => '20', 'attr' => $column]);
                    if (isset($alias->corder)) {
                        $register[$alias->corder] = $value;
                    }
                }

                ksort($register);
                array_push($registers, implode('|', $register));
            }
        }

        return $registers;
    }
}