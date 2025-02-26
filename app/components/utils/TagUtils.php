<?php

class TagUtils extends CApplicationComponent
{


    public static function isInstructor()
    {
        return (bool) Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id);
    }

    public static function isCoordinator(){
        return (bool)Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id);
    }

    public static function isManager(){
        $criteria = new CDbCriteria();
        $criteria->condition = 't.userid = :id';
        $criteria->params = array(':id' => Yii::app()->user->loginInfos->id);
        $authAssignment = AuthAssignment::model()->find($criteria);

        if ($authAssignment->itemname == "manager") {
            return true;
        }
        return false;
    }

    public static function isStageMinorEducation($stage)
    {
        $refMinorStages = [
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '14',
            '15',
            '16',
            '17',
            '18'
        ];
        $stages = new CList($refMinorStages, true);
        return $stages->contains(strval($stage));
    }
    public static function isStageChildishEducation($stage)
    {
        $refMinorStages = [
            '1',
            '2',
            '3'
        ];
        $stages = new CList($refMinorStages, true);
        return $stages->contains(strval($stage));
    }

    public static function isStageEJA($stage): bool
    {
        $refMinorStages = ["43", "44", "45", "46", "47", "48", "51", "58", "60", "61", "62", "63", "65", "66", "69", "70", "71", "72", "73", "74"];
        $stages = new CList($refMinorStages, true);
        return $stages->contains(strval($stage));
    }

    public static function isMultiStage($stage)
    {
        $refMinorStages = [
            '12',
            '13',
            '22',
            '23',
            '24',
            '56',
            '83',
            '84'
        ];
        $stages = new CList($refMinorStages, true);
        return $stages->contains(strval($stage));
    }
    public static function convertDateFormat($date)
    {
        // Remove espaços em branco do início e do fim da string
        $date = trim($date);

        // Verifica se a date é vazia ou nula
        if (empty($date) || is_null($date)) {
            return $date;
        }

        // Verifica se a date está no formato dd/mm/yyyy
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            return $date;
        }

        // Verifica se a date está no formato yyyy-mm-dd
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $dateParts = explode('-', $date);
            $dia = $dateParts[2];
            $mes = $dateParts[1];
            $ano = $dateParts[0];
            return "$dia/$mes/$ano";
        }

        // Retorna a date original se não corresponder a nenhum formato conhecido
        return $date;
    }

    public static function isSubstituteInstructor($classroom){

        $instructor = InstructorIdentification::model()->findByAttributes(array("users_fk" => Yii::app()->user->loginInfos->id));
        $teachingData = InstructorTeachingData::model()->findByAttributes(
            array(
                "instructor_fk" => $instructor->id,
                "classroom_id_fk" => $classroom->id
            )
        );

        $refTeachingData = ["9"];

        $roles = new CList($refTeachingData, true);
        return $roles->contains(strval($teachingData->role));
    }


    public static function isInstance($instance)
    {
        if (is_array($instance)) {
            $instances = array_map(function ($element) {
                return strtoupper($element);
            }, $instance);

            return in_array(strtoupper(INSTANCE), $instances);
        }

        return strtoupper(INSTANCE) === strtoupper($instance);
    }

    public static function generateCacheKey($prefix = 'cache'): string
    {
        $schoolId = Yii::app()->user->getState('school');
        $year = Yii::app()->user->getState('year');
        $roles = Yii::app()->authManager->getRoles(Yii::app()->user->id);
        $role = !empty($roles) ? key($roles) : 'default_role';  // Valor padrão

        return $prefix . "_" .$role . "_" . $year . "_" . $schoolId;
    }

    /**
     * @var CActiveRecord $record
     */
    public static function stringfyValidationErrors($record)
    {
        $errors = $record->getErrors();
        $result = array_map(function ($key, $messages) use ($record) {
            $message = Yii::t("default", $record->getAttributeLabel($key)) . ": \n";
            foreach ($messages as $value) {
                $message .= "- " . $value . "\n";
            }
            return $message;
        }, array_keys($errors), array_values($errors));

        return implode("\n", $result);
    }
}

?>
